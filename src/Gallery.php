<?php

declare(strict_types=1);

namespace SM\FolderGallery;

use SM\FolderGallery\Renderer\GridRenderer;
use SM\FolderGallery\Renderer\LightboxRenderer;

final class Gallery
{
    public const DEFAULT_OPTIONS = [
        'formats'      => ['jpg', 'jpeg', 'png', 'webp', 'gif'],
        'sort'         => 'natural',
        'alt_prefix'   => 'Image',
        'aspect'       => '1/1',
        'class_prefix' => 'fg',
        'title'        => null,
    ];

    private array $options;
    /** @var Image[] */
    private array $images = [];

    public function __construct(
        private string $path,
        private string $urlBase,
        array $options = [],
        private ?string $id = null,
    ) {
        $this->options = array_merge(self::DEFAULT_OPTIONS, $options);
        $this->scan();
    }

    public function id(): string
    {
        return $this->id ?? basename($this->path);
    }

    public function title(): string
    {
        return $this->options['title'] ?? ucwords(str_replace(['-', '_'], ' ', $this->id()));
    }

    /** @return Image[] */
    public function images(): array
    {
        return $this->images;
    }

    public function count(): int
    {
        return count($this->images);
    }

    public function isEmpty(): bool
    {
        return $this->images === [];
    }

    public function option(string $key): mixed
    {
        return $this->options[$key] ?? null;
    }

    public function renderGrid(): string
    {
        return (new GridRenderer($this))->render();
    }

    public function renderLightbox(bool $inline = true): string
    {
        return (new LightboxRenderer())->render($inline);
    }

    private function scan(): void
    {
        if (!is_dir($this->path)) {
            return;
        }

        $files = $this->collectFiles();
        $this->applySort($files);

        $urlBase = rtrim($this->urlBase, '/');
        foreach ($files as $idx => $file) {
            $filename = basename($file);
            $this->images[] = new Image(
                filename: $filename,
                url: $urlBase . '/' . rawurlencode($filename),
                alt: $this->options['alt_prefix'] . ' — image ' . ($idx + 1),
            );
        }
    }

    /** @return string[] */
    private function collectFiles(): array
    {
        $found = [];
        foreach (scandir($this->path) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $full = $this->path . DIRECTORY_SEPARATOR . $entry;
            if (!is_file($full)) {
                continue;
            }
            $ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
            if (in_array($ext, $this->options['formats'], true)) {
                $found[] = $full;
            }
        }
        return $found;
    }

    private function applySort(array &$files): void
    {
        match ($this->options['sort']) {
            'natural' => usort($files, [self::class, 'naturalCompare']),
            'alpha'   => sort($files),
            'mtime'   => usort($files, fn ($a, $b) => filemtime($a) <=> filemtime($b)),
            default   => null,
        };
    }

    private static function naturalCompare(string $a, string $b): int
    {
        $na = pathinfo($a, PATHINFO_FILENAME);
        $nb = pathinfo($b, PATHINFO_FILENAME);
        if (ctype_digit($na) && ctype_digit($nb)) {
            return (int) $na <=> (int) $nb;
        }
        return strnatcasecmp($na, $nb);
    }
}
