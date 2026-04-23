<?php

declare(strict_types=1);

namespace SM\FolderGallery;

final class Image
{
    public function __construct(
        public string $filename,
        public string $url,
        public string $alt,
    ) {
    }
}
