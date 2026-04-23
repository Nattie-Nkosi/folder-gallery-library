<?php

declare(strict_types=1);

namespace SM\FolderGallery\Tests;

use PHPUnit\Framework\TestCase;
use SM\FolderGallery\Gallery;

final class GalleryTest extends TestCase
{
    private string $fixtures;

    protected function setUp(): void
    {
        $this->fixtures = __DIR__ . '/fixtures/images';
    }

    public function test_scans_expected_images(): void
    {
        $gallery = new Gallery($this->fixtures, '/img');
        $this->assertSame(3, $gallery->count());
    }

    public function test_natural_sort_puts_10_after_2(): void
    {
        $gallery = new Gallery($this->fixtures, '/img', ['sort' => 'natural']);
        $filenames = array_map(fn ($i) => $i->filename, $gallery->images());
        $this->assertSame(['1.jpeg', '2.jpeg', '10.jpeg'], $filenames);
    }

    public function test_alpha_sort_puts_10_before_2(): void
    {
        $gallery = new Gallery($this->fixtures, '/img', ['sort' => 'alpha']);
        $filenames = array_map(fn ($i) => $i->filename, $gallery->images());
        $this->assertSame(['1.jpeg', '10.jpeg', '2.jpeg'], $filenames);
    }

    public function test_missing_folder_yields_empty_gallery(): void
    {
        $gallery = new Gallery(__DIR__ . '/does-not-exist', '/img');
        $this->assertTrue($gallery->isEmpty());
        $this->assertSame('', $gallery->renderGrid());
    }

    public function test_url_base_is_applied_and_filename_is_encoded(): void
    {
        // URL base is passed through verbatim (caller's responsibility to URL-encode it);
        // the filename portion is always encoded so spaces/unicode in filenames can't break markup.
        $gallery = new Gallery($this->fixtures, 'https://cdn.example/path');
        $first = $gallery->images()[0];
        $this->assertStringStartsWith('https://cdn.example/path/', $first->url);
        $this->assertStringEndsWith(rawurlencode($first->filename), $first->url);
    }

    public function test_format_filter_excludes_non_matching(): void
    {
        $gallery = new Gallery($this->fixtures, '/img', ['formats' => ['png']]);
        $this->assertSame(0, $gallery->count());
    }

    public function test_grid_output_contains_thumbnail_markup(): void
    {
        $gallery = new Gallery($this->fixtures, '/img', [], 'demo');
        $html = $gallery->renderGrid();
        $this->assertStringContainsString('class="fg-grid"', $html);
        $this->assertStringContainsString('data-gallery="demo"', $html);
        $this->assertStringContainsString('data-index="0"', $html);
    }

    public function test_title_derives_from_id(): void
    {
        $gallery = new Gallery($this->fixtures, '/img', [], 'mindray-probes');
        $this->assertSame('Mindray Probes', $gallery->title());
    }
}
