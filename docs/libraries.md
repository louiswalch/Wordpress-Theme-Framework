⬅ [Return to main README](../README.md)

# Wordpress Theme Framework

### Frontend Library

Frontend Libraries are a collection of utilities meant to streamline your template authoring. This includes utilities to simplify image output (with a variety of options), render an include file (supporting proper data and caching), and automating a modular layout.

1. [Cache](#frontend-library--cache)
1. [Includes](#frontend-library--includes-render)
1. [Modules](#frontend-library--modules-render)
1. [Image](#frontend-library---image-render)
1. [SVG](#frontend-library--svg-render)
1. [DataStore](#frontend-library--data-store)

<br/>

------

### Frontend Library : Cache

```php
// Simplest usage:
$data = CACHE()->load('test123', function() {
    // Big query or expensive process to load $data
    return $data;    
});


// Store for longer than default:
$data = CACHE()->time(DAY_IN_SECONDS)->load('test123', function() {
    // Big query or expensive process to load $data
    return $data;    
});

// By default, cache are stored at the page/object level, you cam override that to store/fetch globally:
$data = CACHE()->global(true)->load('footer-evnets', function() {
    // Big query or expensive process to load $data
    return $data;    
});
```

------

### Frontend Library : Includes Render

Includes allow you to separate some of your site's components and render then within a page when needed. The main advantage over the native `get_template_part()` is that you can pass data directly into the include while rendering it in addition an include can be cached either globally or page-specific. 


##### Example Usage - Display a simple include file:
```php
INCLUDES()->show('share_icons');
```

##### Params:

The library supports a variety of parameters to customize how the image should be rendered. These methods are designed to be chained and would be passed in prior to your final output call.

> - cache($global=false, $set=true, $key=null)
> - dir($dir=null)
> - life($life=null)
> - data($one=null, $two=null)
> - debug($incoming=null)
> - key($incoming=false)
> - fetch($key, $data=false)     // Load and output the contents of a snippet, either live or from cache.
> - show($key, $data=false)

##### More Examples: 
```php
// Display an include file. Basic usage.
INCLUDES()->show('share_icons');

// Display an include file and cache the output.
// Useful for areas which perform a lot of database calls but hardly change (e.g. your site's header).
INCLUDES()->cache()->show('header');

// Display an include file and cache the output, globally.
// Normally cache is page-specific, but if your include doesn't change between pages you can set it to be site-wide.
INCLUDES()->cache('global')->show('header_global');

// Send data to include when generating it.
INCLUDES()->data(['section'=>'about', 'background'=>'blue'])->show('header');

// Get the contents of an include and store in variable.
$include_contents = INCLUDES()->fetch('share_icons');
```

Inside your include file you can call `get_include_var` to access any data passed in.

```php
get_include_var($key, $default = false, $group = null)
```

For debugging purposes or if you don't know what data is accessible to you, you can output the `$data` variable.

```php
pr($data, 'Include Data');
```
<br/>

------

### Frontend Library : Modules Render

Module are similar to Includes except they are designed to be used with the ACF Flexible Content field, automatically rendering the correct module element based on what the page has been set up with.

##### Example Usage:
```php
// Automatically load modules from current page. Default field name is 'modules'.
MODULES()->auto();

// Automatically load all modules passing the module (Flexible Content) value.
MODULES()->auto( $my_modules );

// You can have two sets of modules on one page like this:
MODULES()->auto('content_modules');
MODULES()->auto('sidebar_modules');

// When automatically loading modules, you can specify certain ones to ignore.
MODULES()->auto('content_modules', ['unwanted_module_name', 'ugly_thing']);
// Manually load certain modules passing the module (Flexible Content) value, and an array of layout to skip.
MODULES()->auto( $my_modules, ['unwanted_module_name', 'ugly_thing'] );

// Automatically load modules from a different page, or from the ACF Options.
MODULES()->from(1234)->auto('content_modules');
MODULES()->from('options')->auto('footer_modules');

// Alter the name of module file to be included: Adding a prefix.
// For a module named 'gallery' in the CMS, this would include 'exhibition_gallery'.
MODULES()->filename('exhibition_')->auto('content_modules');

// You can also use the `filename()` prefix option to map to a subdirectory of _modules.
MODULES()->filename('exhibition/')->auto('content_modules');

// Alter the name of module file to be included: Using `sprintf` command.
// For a module named 'gallery' in the CMS, this would include 'exhibition_gallery_preview'.
MODULES()->filename('exhibition_%s_preview')->auto('content_modules');

// Manually load a specified module while passing in the data to display.
MODULES()->show('text_content', [
    'title' => 'Test Module',
    'text' => 'Testing out manually including a module on this page. Did it work?',
    ]);
```

Inside your module file you can call `get_module_var` to access all data from the current Flexible Content block.

```php
get_module_var($key, $default = false, $group = null);
```

For debugging purposes or if you don't know what data is accessible to you, you can output the `$data` variable.

```php
pr($data, 'Module Data');
```

##### Special Variables: 

The following built-in variables are made available within your module file. These can be referenced directly (e.g. `$_name`) or using `get_module_var()`.

- `_name` *(String)* Name specified for this module within the CMS.
- `_index` *(Int)* Index of the current module within iteration.
- `_first` *(Bool)* Whether or not the current module is first.
- `_last` *(Bool)* Whether or not the current module is last.

<br/>

---

### Frontend Library :  Image Render

This allows you to easily output a Wordpress image, either coming from an ACF Image field or the post thumbnail. Images can be rendered as a standard IMG element or, when using the legacy lazysizes mode, as a DIV with a background image.

##### Example Usage - Output IMG element from custom field value:

```php
// <img src="../foo.jpg" alt="Image Title" />
echo IMAGE()->img(get_field('image'));

```

##### Params:

The library supports a variety of parameters to customize how the image should be rendered. These methods are designed to be chained and would be passed in prior to your final output call.

> - wrap($incoming=false)
> - autosize($incoming=false)
> - caption($incoming=null)
> - description($incoming=null)
> - classes($incoming=false)
> - lazy($incoming=false) // existing boolean switch
> - loading($incoming=null) // lazy or eager
> - fetchpriority($incoming=null) // high, low, auto, or false
> - responsive($incoming=true)
> - decoding($incoming=null) // async, sync, auto, or false
> - above_fold($incoming=true)
> - size($incoming=false)
> - fallback($incoming=false)
> - pinnable($incoming=false)
> - debug($incoming=false)
> - attributes($incoming=false)
> - attr($one=false, $two=false)

Once you have defined any parameters, then you call your final output - either an IMG, DIV, or to retrieve the SRC.

> - div($image=false, $size=false)
> - img($image=false, $size=false)
> - src($image=false, $size=false)

Additional read-only helpers are available after an image has been rendered:

> - get_caption($image=false, $format=false)
> - get_description($image=false, $format=false)
> - get_dimensions($image=false)
> - get_orientation($image=false)
> - get_autosize($image=false)

##### Basic Rendering Examples:

```php
// Output IMG element from custom field value.
echo IMAGE()->img(get_field('image'));
    
// Output IMG element from Featured Image with class attribute.
echo IMAGE()->classes('thumbnail')->div(get_post_thumbnail_id()); 
    
// Output IMG for lazy loading from custom field. 
echo IMAGE()->classes('lazy image')->alpha(true)->img(get_field('image'));
    
// Output IMG from a custom field, adding custom attributes, example 1.
echo IMAGE()->attr(array('data-test1'=>'value1', 'data-test2'=>'value2'))->img(get_field('image'));
    
// Output IMG from a custom field, adding custom attributes, example 2.
echo IMAGE()->attr('data-test1', 'value1')->img(get_field('image'));
    
// Output a DIV element with from image in a custom field with class attribute.
echo IMAGE()->classes('thumbnail')->div(get_field('image'));
    
// Output a DIV element from image in a custom field, setting it's base size to something smaller.
echo IMAGE()->size(600)->div(get_field('image'));
    
// Output just the image src (e.g. for use in Meta Tag), passing in the size we want.
echo IMAGE()->src(get_post_thumbnail_id(), 800); 
```

##### Image Render Library - Loading Modes

The loading mode is controlled globally through the theme's `_framework/config.php` file:

```php
// Preserve the legacy JavaScript/lazysizes implementation (default).
CONFIG()->set('render/image/loading_mode', 'lazysizes');

// Use browser-native lazy loading for IMG elements.
CONFIG()->set('render/image/loading_mode', 'native');
```

The `lazysizes` mode outputs the existing `data-srcset`, `data-sizes`, and placeholder behavior. It also supports the `div()` renderer and background-image loading.

The `native` mode outputs an IMG element with real image attributes:

```html
<img class="lazy" src="image-1600.jpg"
     srcset="image-480.jpg 480w, image-768.jpg 768w, image-1600.jpg 1600w"
     sizes="100vw" loading="lazy" decoding="async" />
```

Native mode does not output lazysizes `data-*` attributes and does not require lazysizes to load an image. The `div()` renderer is not supported in native mode and returns the configured fallback instead.

For an image that should load eagerly and be synchronously decoded, override the defaults for that render:

```php
echo IMAGE()->lazy(false)->decoding('sync')->img($image);
```

The `decoding` attribute is only a browser hint. It does not increase network priority. For a true above-the-fold priority image, `loading` should be eager and `fetchpriority="high"` may also be appropriate.

The `above_fold()` helper applies all of those settings automatically:

```php
echo IMAGE()->above_fold()->img($image);
```

It outputs `loading="eager"`, `decoding="sync"`, and `fetchpriority="high"`. Use it only for the small number of images that are immediately visible when the page loads.

##### Per-image loading and fetch priority

Use `loading()` and `fetchpriority()` before `img()` to control each hint independently:

```php
// Native is the rendering backend; lazy remains the default.
CONFIG()->set('render/image/loading_mode', 'native');
CONFIG()->set('render/image/default_lazy', true);

// Ordinary image: loading="lazy", no fetchpriority attribute.
echo IMAGE()->img($image);

// Visible image: loading="eager", browser chooses its normal priority.
echo IMAGE()->loading('eager')->img($image);

// Important hero: eager loading and high fetch priority.
// Decoding keeps its configured default (normally async).
echo IMAGE()->loading('eager')->fetchpriority('high')->img($hero);

// Lower priority lazy image; priority alone does not change loading.
echo IMAGE()->loading('lazy')->fetchpriority('low')->img($image);

// Explicit browser-default priority, or omit the hint entirely.
echo IMAGE()->fetchpriority('auto')->img($image);
echo IMAGE()->fetchpriority(false)->img($image);

// Existing convenience helper: eager + high + sync.
// Later setters can override individual hints.
echo IMAGE()->above_fold()->decoding('async')->fetchpriority(false)->img($hero);

// Existing boolean API: disable lazy loading without an explicit loading attribute.
echo IMAGE()->lazy(false)->img($image);

// Last loading-related method wins: this renders a native lazy image.
echo IMAGE()->loading('eager')->lazy(true)->img($image);
```

`loading()` accepts only `lazy` or `eager`. `fetchpriority()` accepts only `high`, `low`, `auto`, or boolean `false`. Invalid values (including omitted/null arguments) leave the current setting unchanged. Priority does not implicitly change loading or decoding; pair `high` with `eager` when immediate loading is intended.

`loading()`, `lazy()`, and an enabled `above_fold()` share loading state: the last call wins. `above_fold(false)` remains a no-op. Explicit `attr()`/`attributes()` values retain final precedence over generated attributes regardless of chain order; use the dedicated methods to keep loading behavior, sources, and classes consistent. Generic attributes retain their existing wrapper behavior, whereas the new methods emit hints only on the IMG.

In native mode, eager images retain real `src`, responsive `srcset`, configured `sizes`, and `width`/`height`. They do not automatically receive the native lazy CSS class. Because auto-sizes requires lazy loading, eager images remove a leading `auto` from `native_sizes`: `auto, 100vw` becomes `100vw`; bare `auto` falls back to `100vw`. Explicit `attr('sizes', ...)` still overrides this generated value.

In lazysizes mode, `loading('lazy')` keeps JavaScript lazy loading without adding native `loading="lazy"`. `loading('eager')` bypasses placeholders and lazy classes, uses real sources, and emits `loading="eager"` on IMG output. Loading and priority attributes are never emitted by these methods on background DIVs; native `div()` continues to return its configured fallback.

All per-image options reset after `img()`, `div()`, or `src()`, including missing-image/fallback output, so a priority hero does not affect the next image. No new global configuration is required.

The default class for native lazy images is configurable. The class is only a CSS hook; it does not load JavaScript:

```php
CONFIG()->set('render/image/native_class', 'lazy');

// Disable the class.
CONFIG()->set('render/image/native_class', false);
```

##### Image Render Library - Responsive Images

The framework generates `srcset` candidates from the configured `image/sizes` values. For example:

```php
CONFIG()->set('image/sizes', ['480', '768', '1024', '1280', '1680', '2048', '2560', '3840']);
```

These values describe the files available to the browser. They do not describe the rendered CSS width. In native mode, that width is described by `render/image/native_sizes`:

```php
// A full-width image.
CONFIG()->set('render/image/native_sizes', '100vw');

// An image that occupies half the viewport on larger screens.
CONFIG()->set('render/image/native_sizes', '(max-width: 767px) 100vw, 50vw');
```

The browser combines `srcset`, `sizes`, viewport width, and device pixel ratio to select the most suitable candidate. `native_sizes` must match the actual CSS layout. A generic `100vw` value is safe, but can download unnecessarily large images for grid or column layouts.

Wordpress limits responsive candidates to 2048px by default. The framework exposes this limit through `image/max/w`:

```php
CONFIG()->set('image/max/w', 3840);
```

Candidates larger than the original uploaded image, unavailable generated files, or incompatible image ratios may still be omitted by Wordpress.

##### Image Render Library - Wrapper Functionality

Wrapping your image embed with another element is handy when working with responsive images as well as allow for a placeholder while the image is being loaded. Default behavior can be defined in your site's framework `config.php` file, or pass custom parameters in when calling making a call to the library.

- [Image Render Library Config Options ↗](configuration_options)

The framework configuration settings appropriate to Image Render wrapping are (along with their default values):

```php
// Enable or disable the wrapping functionality across all renders.
CONFIG()->set('render/image/default_wrap' , false);

// This class will be added to the wrapping DIV element and would correspond to any CSS you have to managing responsive images.
CONFIG()->set('render/image/default_wrap_class' , 'image_wrapper');

// This is an extra class that gets added to show the correct size for this particular image. 
CONFIG()->set('render/image/default_wrap_size' , '');

// Enable or disable the autosize functionality across all wraps. This will calculate an image's aspect ratio and add CSS to the element matching.
CONFIG()->set('render/image/default_wrap_autosize' , false);
```

In most cases you will be interfacing with this by adding parameters to your call to the Image Render library. The examples below go through some common use cases, they all use DIV render method but wrapping can also be useful for IMG render.

```php
// Enable wrapping and output an image. Pretty simple.
// <div class="image_wrapper">
//      <div style="background-image: url(foo.png);"></div>
// </div>
echo IMAGE()->wrap(true)->div(get_field('image'));

// Enable wrapping, setting a size class to be added and output an image. 
// <div class="image_wrapper square">
//      <div style="background-image: url(foo.png);"></div>
// </div>
echo IMAGE()->wrap('square')->div(get_field('image'));

// Enable wrapping with the 'autosize' functionality turned on and output an image. In this example the image is a square.
// <div class="image_wrapper" style="padding-bottom: 100%;">
//      <div style="background-image: url(foo.png);"></div>
// </div>
echo IMAGE()->wrap('auto')->div(get_field('image'));

// If desired, you also can pass in autosize as it's own parameter in the render request:
echo IMAGE()->wrap(true)->autosize(true)->div(get_field('image'));
```


##### Image Render Library - Working with captions.

All images in Wordpress have the capability to store a caption, this information can then be displayed throught the Image Render Library. Default behavior can be defined in your site's framework `config.php` file, or pass custom parameters in when calling making a call to the library.

###### Caption Configuration:

```php
// Automatically display image caption by default whenever rendering an image.
CONFIG('render/image/default_caption', true);

// Control where the caption is displayed when an image is being wrapped.
// 'inside' : inside the wrapper, following the image
// 'outside' : outside the wrapper
CONFIG('render/image/default_caption_location', 'inside');

// Enable or disable default behavior for image captions.
CONFIG()->set('render/image/default_caption' , true);
```
- [View All Image Library Config Options ↗](configuration_options.md#image-settings)


###### Caption Rendering Examples:

```php
// Output simple IMG with caption.
// <img src="...." /><div class="caption">This is my caption.</div>
echo IMAGE()->caption(true)->img(get_field('image'));

// Output a wrapper IMG with caption and assign location.
// <div class="image-wrapper"><img src="...." /><div class="caption">This is my caption.</div></div>
echo IMAGE()->wrap(true)->caption('inside')->img(get_field('image'));
```

If you'd like more control over where the caption for your image appears, you can fetch it yourself. This helper function returns an image caption (either formatted or not) for you do to as you like.

```php
$caption = IMAGE()->get_caption(get_field('image'));
```

<br/>

------

### Frontend Library : SVG Render

This allows you to easily output an SVG image from your theme's assets directory to the page. Images can either be rendered as a standard IMG element, as a DIV with background image or with the SVG source embedded within the HTML.

##### Example Usage:

```php
// Display an in-line SVG (stored in assets/img/arrow.svg):
// <svg xmlns="http://www.w3.org/2000/svg" width="9" height="7"><path d="M3.235 6.747c-.172 0-.336-.055-.474-.158L.31 4.72A.79.79 0 0 1 .16 3.615c.26-.336.776-.4 1.107-.148L3.15 4.903 5.018 2.86 7.422.246C7.53.128 7.69.04 7.867.01L7.992 0c.297 0 .565.164.702.43.153.3.098.667-.14.908l-4.75 5.167a.79.79 0 0 1-.57.243" fill="#4B4A4B" fill-rule="evenodd"/></svg>
echo SVG()->show('arrow');

// Display an SVG (stored in assets/img/arrow.svg) as IMG with attributes:
// <img src=".../assets/img/test.png" class="testing" alt="Name of the image" />
echo SVG()->img('arrow.svg', array('class'=>'testing', 'alt'=>'Name of the image'))
```

##### Params:

> - img($key, $attributes=array()) 
> - div($key, $attributes=array())
> - show($key)


------

### Frontend Library : Data Store

This allows...

##### Example Usage, Saving:

```php
DATASTORE()->save('example-feed', 'https://example.com/feed.json');
```

```php
DATASTORE()->save('example-array', ['id' => 123, 'title' => 'Hello']);
```

```php
DATASTORE()->save('example-callback', function() {
    return ['foo' => 'bar'];
});
```

```php
DATASTORE()->save('example-html', '<div>Hello</div>', 'text');
```

##### Example Usage, Loading:

```php
$feed = DATASTORE()->load('example-feed');

// $feed['cached_at']
// $feed['data']
```

```php
$data    = DATASTORE()->load_data('example-feed');
$time    = DATASTORE()->load_time('example-feed');
$url     = DATASTORE()->load_url('example-feed');
```
