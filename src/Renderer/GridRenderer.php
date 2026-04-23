<?php

declare(strict_types=1);

namespace SM\FolderGallery\Renderer;

use SM\FolderGallery\Gallery;

final class GridRenderer
{
    public function __construct(private Gallery $gallery)
    {
    }

    public function render(): string
    {
        if ($this->gallery->isEmpty()) {
            return '';
        }

        $prefix = (string) $this->gallery->option('class_prefix');
        $aspect = (string) $this->gallery->option('aspect');
        $galleryId = $this->gallery->id();
        $total = $this->gallery->count();

        $styleAttr = $aspect !== '' && $aspect !== 'auto'
            ? ' style="--' . $this->esc($prefix) . '-aspect: ' . $this->esc($aspect) . ';"'
            : '';

        $out  = '<div class="' . $this->esc($prefix) . '-grid"' . $styleAttr . ' role="list">';
        foreach ($this->gallery->images() as $idx => $image) {
            $caption = sprintf(
                '%s (%d of %d)',
                $this->gallery->title(),
                $idx + 1,
                $total,
            );
            $out .= sprintf(
                '<button type="button" class="%1$s-thumb" role="listitem"'
                . ' data-gallery="%2$s" data-index="%3$d" data-src="%4$s" data-caption="%5$s"'
                . ' aria-label="Open %6$s">'
                . '<img src="%4$s" alt="%6$s" loading="lazy" decoding="async">'
                . '</button>',
                $this->esc($prefix),
                $this->esc($galleryId),
                $idx,
                $this->esc($image->url),
                $this->esc($caption),
                $this->esc($image->alt),
            );
        }
        $out .= '</div>';
        return $out;
    }

    private function esc(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
