<?php

declare(strict_types=1);

namespace SM\FolderGallery;

use SM\FolderGallery\Renderer\LightboxRenderer;

final class GalleryPage
{
    /** @var Gallery[] */
    private array $galleries = [];

    public function addGallery(string $id, string $path, string $urlBase, array $options = []): Gallery
    {
        $gallery = new Gallery($path, $urlBase, $options, $id);
        $this->galleries[$id] = $gallery;
        return $gallery;
    }

    public function add(Gallery $gallery): self
    {
        $this->galleries[$gallery->id()] = $gallery;
        return $this;
    }

    /** @return Gallery[] */
    public function galleries(): array
    {
        return $this->galleries;
    }

    public function get(string $id): ?Gallery
    {
        return $this->galleries[$id] ?? null;
    }

    public function renderLightbox(bool $inline = true): string
    {
        return (new LightboxRenderer())->render($inline);
    }
}
