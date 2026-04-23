# folder-gallery

Point it at a folder, get a sorted image grid and a keyboard-navigable lightbox. No dependencies.

Built from a real-world catalog page where images are dropped into per-group folders and need to appear on a page without a CMS.

## Install

```bash
composer require nattie-nkosi/folder-gallery
```

Requires PHP 8.0+.

## Quick start — single gallery

```php
use SM\FolderGallery\Gallery;

$gallery = new Gallery(
    path:    __DIR__ . '/assets/products/mindray-probes',
    urlBase: 'assets/products/mindray-probes',
    options: ['alt_prefix' => 'Mindray probe'],
);

echo $gallery->renderGrid();
echo $gallery->renderLightbox();   // inlines CSS + JS by default
```

## Multiple galleries on one page

```php
use SM\FolderGallery\GalleryPage;

$page = new GalleryPage();
$page->addGallery('mindray',   $mindrayPath,   $mindrayUrl,   ['alt_prefix' => 'Mindray probe']);
$page->addGallery('samsung',   $samsungPath,   $samsungUrl,   ['alt_prefix' => 'Samsung HS30']);
$page->addGallery('sonoscape', $sonoscapePath, $sonoscapeUrl, ['alt_prefix' => 'Sonoscape probe']);

foreach ($page->galleries() as $gallery) {
    echo '<h2>' . htmlspecialchars($gallery->title()) . '</h2>';
    echo $gallery->renderGrid();
}

echo $page->renderLightbox();   // ONE lightbox, serves every grid
```

## Options

Passed as the third argument to `new Gallery(...)`:

| Key            | Default                                      | Notes                                  |
|----------------|----------------------------------------------|----------------------------------------|
| `formats`      | `['jpg','jpeg','png','webp','gif']`          | Extensions to scan (case-insensitive). |
| `sort`         | `'natural'`                                  | `natural` \| `alpha` \| `mtime`.       |
| `alt_prefix`   | `'Image'`                                    | Used in each thumbnail's `alt` text.   |
| `aspect`       | `'1/1'`                                      | CSS aspect ratio, e.g. `4/3`, `auto`.  |
| `class_prefix` | `'fg'`                                       | CSS class prefix (reserved for v1.1).  |
| `title`        | derived from folder name                     | Shown by `Gallery::title()`.           |

## Production asset loading

By default, `renderLightbox()` inlines the CSS and JS. For production, copy the asset files once:

```bash
cp vendor/nattie-nkosi/folder-gallery/assets/gallery.css public/assets/
cp vendor/nattie-nkosi/folder-gallery/assets/gallery.js  public/assets/
```

Then:

```html
<link rel="stylesheet" href="/assets/gallery.css">
<script defer src="/assets/gallery.js"></script>
<?= $page->renderLightbox(inline: false) ?>
```

## Iterating manually

```php
foreach ($gallery->images() as $image) {
    echo "<img src='{$image->url}' alt='{$image->alt}'>";
}
```

`$image` exposes `filename`, `url`, `alt`.

## Example

See [`examples/basic.php`](examples/basic.php). Run it in any local PHP server:

```bash
composer install
php -S localhost:8000 -t examples
# open http://localhost:8000/basic.php
```

## Testing

```bash
composer install
composer test
```

## License

MIT
