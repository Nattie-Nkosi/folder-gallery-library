<?php
/**
 * Runnable in XAMPP at:
 *   http://localhost/folder-gallery/examples/basic.php
 *
 * Uses test fixtures; drop more images into tests/fixtures/images/ to see them.
 */

require __DIR__ . '/../vendor/autoload.php';

use SM\FolderGallery\Gallery;
use SM\FolderGallery\GalleryPage;

$fixturesFs  = __DIR__ . '/../tests/fixtures/images';
$fixturesUrl = '../tests/fixtures/images';

$page = new GalleryPage();
$page->addGallery('fixtures', $fixturesFs, $fixturesUrl, [
    'alt_prefix' => 'Fixture image',
    'title'      => 'Test Fixtures',
]);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>folder-gallery — basic example</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 960px; margin: 40px auto; padding: 0 20px; color: #0f172a; }
    h1 { font-size: 1.5rem; margin-bottom: 8px; }
    h2 { font-size: 1.15rem; margin: 32px 0 16px; }
    p  { color: #475569; }
  </style>
</head>
<body>
  <h1>folder-gallery — basic example</h1>
  <p>Point a <code>Gallery</code> at a directory; it produces a thumbnail grid and a shared lightbox.</p>

  <?php foreach ($page->galleries() as $gallery): ?>
    <h2><?= htmlspecialchars($gallery->title()) ?> (<?= $gallery->count() ?>)</h2>
    <?= $gallery->renderGrid() ?>
  <?php endforeach; ?>

  <?= $page->renderLightbox() ?>
</body>
</html>
