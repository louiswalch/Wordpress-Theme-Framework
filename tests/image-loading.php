<?php
/**
 * Standalone renderer regression checks: php tests/image-loading.php
 * WordPress attachment/configuration fixtures isolate HTML behavior from the database.
 * Uses the real renderer, singleton, and attribute formatter; no WordPress bootstrap needed.
 */
$config = [
    'default_lazy' => true, 'default_responsive' => true, 'default_decoding' => 'async',
    'default_size' => 'full', 'default_wrap_size' => '', 'default_wrap_class' => 'image-wrap',
    'loading_mode' => 'native', 'native_sizes' => 'auto, 100vw', 'native_class' => 'lazy',
    'lazy_class' => 'lazyload lazyload-persist', 'lazy_img_srcset' => 'data-srcset',
    'lazy_div_srcset' => 'data-bgset', 'lazy_sizes' => 'data-sizes',
];
function HelloFrameworkConfig($key) { return $GLOBALS['config'][substr($key, strlen('render/image/'))] ?? false; }
function wp_get_attachment_image_src($id, $size) { return ['/image.jpg', 1200, 800]; }
function wp_get_attachment_image_url($id, $size) { return '/image.jpg'; }
function wp_get_attachment_image_srcset($id, $size) { return '/small.jpg 600w, /image.jpg 1200w'; }
function get_post_mime_type($id) { return $id === 999 ? false : 'image/jpeg'; }
function get_post_meta($id, $key, $single) { return 'Example image'; }
require __DIR__ . '/../app/Singleton.php';
require __DIR__ . '/../public/output.php';
require __DIR__ . '/../public/render/Image.php';
require __DIR__ . '/../public/render/ImageInstance.php';

$checks = 0;
function check($condition, $label) {
    if (!$condition) throw new RuntimeException($label);
    $GLOBALS['checks']++;
}
function has($html, $attribute) { return strpos($html, $attribute) !== false; }

$html = IMAGE()->img(1);
check(has($html, 'loading="lazy"') && !has($html, 'fetchpriority='), 'Default lazy, no priority');
check(has($html, 'sizes="auto, 100vw"') && has($html, 'class="lazy"'), 'Lazy sizing and class');
$html = IMAGE()->loading('eager')->fetchpriority('high')->wrap(true)->img(1);
check(has($html, 'loading="eager"') && has($html, 'fetchpriority="high"'), 'Eager high priority');
check(has($html, 'width="1200"') && has($html, 'height="800"') && has($html, 'sizes="100vw"'), 'Eager dimensions and fallback sizes');
check(has($html, 'src="/image.jpg"') && has($html, 'srcset=') && !has($html, 'class="lazy"'), 'Eager sources and class');
check(has($html, 'decoding="async"'), 'Priority leaves decoding unchanged');
$wrapper = substr($html, 0, strpos($html, '>'));
check(!has($wrapper, 'loading=') && !has($wrapper, 'fetchpriority='), 'Hints belong only to IMG');
$html = IMAGE()->img(1);
check(has($html, 'loading="lazy"') && !has($html, 'fetchpriority='), 'Settings reset after render');
check(!has(IMAGE()->lazy(false)->img(1), 'loading='), 'Legacy lazy(false) omission');
check(has(IMAGE()->loading('eager')->lazy(true)->img(1), 'loading="lazy"'), 'Last lazy call wins');
check(has(IMAGE()->lazy(false)->loading('lazy')->img(1), 'loading="lazy"'), 'Last loading call wins');
$html = IMAGE()->above_fold()->img(1);
check(has($html, 'loading="eager"') && has($html, 'fetchpriority="high"') && has($html, 'decoding="sync"'), 'Existing above_fold behavior');
$html = IMAGE()->above_fold()->loading('lazy')->fetchpriority(false)->decoding('async')->img(1);
check(has($html, 'loading="lazy"') && !has($html, 'fetchpriority=') && has($html, 'decoding="async"'), 'Override above_fold hints');
check(has(IMAGE()->above_fold(false)->img(1), 'loading="lazy"'), 'Disabled above_fold is a no-op');
foreach (['high', 'low', 'auto'] as $priority) {
    $html = IMAGE()->fetchpriority($priority)->img(1);
    check(has($html, 'fetchpriority="' . $priority . '"') && has($html, 'loading="lazy"'), 'Independent priority: ' . $priority);
}
$html = IMAGE()->loading('eager')->loading('invalid')->fetchpriority('low')->fetchpriority('invalid')->img(1);
check(has($html, 'loading="eager"') && has($html, 'fetchpriority="low"'), 'Invalid values ignored');
$html = IMAGE()->attr(['loading' => 'eager', 'fetchpriority' => 'low'])->loading('lazy')->fetchpriority('high')->img(1);
check(has($html, 'loading="eager"') && has($html, 'fetchpriority="low"'), 'Raw attributes retain final precedence');
foreach ([false, 999] as $missing) {
    IMAGE()->loading('eager')->fetchpriority('high')->img($missing);
    $html = IMAGE()->img(1);
    check(has($html, 'loading="lazy"') && !has($html, 'fetchpriority='), 'Missing image resets hints');
}
IMAGE()->loading('eager')->fetchpriority('high')->src(1);
check(!has(IMAGE()->img(1), 'fetchpriority='), 'Source-only output resets hints');
check(IMAGE()->loading('eager')->fetchpriority('high')->div(1) === false, 'Native DIV fallback');
check(!has(IMAGE()->img(1), 'fetchpriority='), 'DIV fallback resets hints');
$config['native_sizes'] = 'auto';
check(has(IMAGE()->loading('eager')->img(1), 'sizes="100vw"'), 'Bare auto fallback');
$config['native_sizes'] = '(max-width: 767px) 100vw, 50vw';
check(has(IMAGE()->loading('eager')->img(1), 'sizes="(max-width: 767px) 100vw, 50vw"'), 'Explicit responsive sizes preserved');
check(!has(IMAGE()->responsive(false)->loading('eager')->img(1), 'sizes='), 'Non-responsive output');
$config['loading_mode'] = 'lazysizes';
$html = IMAGE()->loading('lazy')->img(1);
check(has($html, 'data-srcset=') && has($html, 'data:image/gif') && !has($html, 'loading='), 'Legacy lazy backend preserved');
$html = IMAGE()->loading('eager')->fetchpriority('high')->img(1);
check(has($html, 'src="/image.jpg"') && has($html, 'loading="eager"') && !has($html, 'data-srcset=') && !has($html, 'lazyload'), 'Legacy eager bypasses lazy backend');
$html = IMAGE()->loading('eager')->fetchpriority('high')->div(1);
check(!has($html, 'loading=') && !has($html, 'fetchpriority='), 'DIV excludes IMG-only hints');
$config['default_lazy'] = false;
new HelloFramework\Render\ImageRender();
check(!has(IMAGE()->img(1), 'loading='), 'Existing global lazy opt-out');
echo "Passed $checks image rendering checks.\n";
