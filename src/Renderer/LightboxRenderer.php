<?php

declare(strict_types=1);

namespace SM\FolderGallery\Renderer;

final class LightboxRenderer
{
    private const MARKUP = <<<'HTML'
<div class="fg-lightbox" id="fg-lightbox" role="dialog" aria-modal="true" aria-label="Image viewer" hidden>
  <button type="button" class="fg-lightbox__close" aria-label="Close">&times;</button>
  <button type="button" class="fg-lightbox__nav fg-lightbox__nav--prev" aria-label="Previous image">&#10094;</button>
  <figure class="fg-lightbox__figure">
    <img class="fg-lightbox__image" src="" alt="">
    <figcaption class="fg-lightbox__caption"></figcaption>
  </figure>
  <button type="button" class="fg-lightbox__nav fg-lightbox__nav--next" aria-label="Next image">&#10095;</button>
</div>
HTML;

    public function render(bool $inline = true): string
    {
        $out = self::MARKUP;
        if ($inline) {
            $out .= "\n<style>" . $this->css() . "</style>";
            $out .= "\n<script>" . $this->js() . "</script>";
        }
        return $out;
    }

    public function css(): string
    {
        return (string) file_get_contents(__DIR__ . '/../../assets/gallery.css');
    }

    public function js(): string
    {
        return (string) file_get_contents(__DIR__ . '/../../assets/gallery.js');
    }
}
