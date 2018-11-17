⬅ [Return to main README](../README.md)

# Wordpress Theme Framework

### Frontend Library

Frontend Libraries are a collection of utilities meant to streamline your template authoring. This includes utilities to simplify image output (with a variety of options), render an include file (supporting proper data and caching), and automating a modular layout.

1. [Includes](#frontend-library--includes-render)
1. [Modules](#frontend-library--modules-render)
1. [Image](#frontend-library---image-render)
1. [SVG](#frontend-library--svg-render)
1. ~~[Metatag](#frontend-library--meta-tag-render)~~

<br/>

------

### Frontend Library : Includes Render

Includes allow you to separate some of your site's components and render then within a page when needed. The main advantage over the native `get_template_part()` is that you can pass data directly into the include while rendering it in addition an include can be cached either globally or page-specific. 

##### Example Usage - Display a simple include file:
```php
INCLUDES()->show('share_icons');
```
XXXXXXXXX

> - TODO: Document
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

<br/>

------

### Frontend Library : Modules Render

Module are similar to Includes except they are designed to be used with the ACF Flexible Content field, automatically rendering the correct module element based on what the page has been set up with.

##### Example Usage:
```php
// Automatically load modules from current page. Default field name is 'modules'.
MODULES()->auto();

// You can have two sets of modules on one page like this:
MODULES()->auto('content_modules');
MODULES()->auto('sidebar_modules');

// Automatically load modules from a different page.
MODULES()->from(1234)->auto();

// Manually load a specified module while passing in the data to display.
MODULES()->show('text_content', [
    'title' => 'Test Module',
    'text' => 'Testing out manually including a module on this page. Did it work?',
    ]);
```

Inside your module file you can call `get_module_var` to access all data from the current Flexible Content block.

```php
get_module_var($key, $default = false, $group = null)
```

<br/>

---

### Frontend Library :  Image Render

This allows you to easily output a Wordpress image, either coming from an ACF Image field or the post thumbnail. They can either be rendered as a standard IMG element a DIV with background image. In both cases it supports lazy loading as well as responsive src-set.

##### Example Usage - Output IMG element from custom field value:

```php
// <img src="../foo.jpg" alt="Image Title" />
echo IMAGE()->img(get_field('image'));

```

The library suports a variety of parameters to customize how the image should be rendered. These methods are designed to be chained and would be passed in prior to your final output call.

> - TODO: Document
> - srcset($incoming=true)
> - wrap($incoming=false)
> - caption($incoming=null)
> - classes($incoming=false)
> - lazy($incoming=true)
> - size($incoming=false)
> - alpha($incoming=false)
> - attributes($incoming=false) 
> - attr($one=false, $two=false)
> - pinnable($incoming=false

Once you have defined any parameters, then you call your final output - either an IMG, DIV, or to retrieve the SRC.

> - TODO: Document
> - div($image=false, $size=false)
> - img($image=false, $size=false, $showcaption=false)
> - src($image=false, $size=false)

##### More Examples:

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

> - TODO: Document
> - img($key, $attributes=array()) 
> - div($key, $attributes=array())
> - show($key)

<br/>

------

### ~~Frontend Library : Meta Tag Render~~

Not yet.