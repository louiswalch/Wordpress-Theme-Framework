# Wordpress Theme Framework

    
1. [Installation](#installation)
1. [Demo Theme](#getting-started-with-the-demo-theme)
1. [Frontend Libraries ↗](https://github.com/louiswalch/Wordpress-Theme-Framework/blob/master/docs/libraries.md) 
1. [Frontend Helpers ↗](https://github.com/louiswalch/Wordpress-Theme-Framework/blob/master/docs/helpers.md) 
1. [Configuration](#configuration)
1. [Configuration Options ↗](https://github.com/louiswalch/Wordpress-Theme-Framework/blob/master/docs/configuration_options.md) 
1. [Environment Detection](#environment-detection)
1. ~~[Custom Post Types & Taxonomy](#custom-post-types--taxonomy)~~


<br/>

------
   
### TODO

- [ ] Name framework? Something short - 3 to 4 characters.
- [ ] Move Frontend Libraries and Helpers to their own Plugin?
- [ ] Add ACF customizations.
- [ ] Write Product introduction.

<br/>

------   

### Installation

Installation is very simple - install and activate this Plugin on your Wordpress site. Once running, most of the Frontend HTML and Dashboard UI cleanup functionality will happen automatically. If you would like to customize functionality within the framework refer to the [Configuration](#configuration) section of this document.

If you are using any of the Template Libraries or Helper Functions in your theme code, we suggest placing the following in your code `functions.php` file to safeguard against errors if the event the Plugin is deactivated.

```php
#functions.php
// Make sure the Theme Framework plugin is present. Required by this theme.
if (!is_admin() && !defined('HELLO_DIR')) exit('<strong>Whooops.</strong> This theme is meant to be used with the <a href="https://github.com/louiswalch/Wordpress-Theme-Framework">Theme Framework</a>. Please install and activate.');
```

<br/>

---

### Getting Started with the Demo Theme

To familiarize yourself with developing Wordpress themes using this framework I recommend checking out our demo theme. This is a very light implementation showcasing most of the major features.

[https://github.com/louiswalch/Wordpress-Theme-Framework-Demo](https://github.com/louiswalch/Wordpress-Theme-Framework-Demo)

<br/>

---

### Configuration

Customization and control over the functionality contained in the framework is done through configuration files you place in your theme. To manage the configuration of your theme, create a file at `_framework/config.php` with your desired options.

##### Example Usage - Assigning theme CSS & JS files:

```php
# _framework/config.php
CONFIG()->set('frontend/assets/css', ['css/site.min.css']);
CONFIG()->set('frontend/assets/css_print', ['css/print.min.css']);
CONFIG()->set('frontend/assets/js', ['js/vendor.min.js', 'js/site.min.js']);
```

In addition base level configuration file, you can also create environment-specific (development, staging) files which would override all default settings only on that environment. 

##### Example Usage - Assigning alternative CSS & JS on development server:

```php
# _framework/config_development.php
CONFIG()->set('frontend/assets/css', ['css/site.css']);
CONFIG()->set('frontend/assets/css_print', ['css/print.css']);
CONFIG()->set('frontend/assets/js', ['js/vendor.js', 'js/site.js']);
```

To inspect the current framework configuration you can use the following command.

```php
<? pr(CONFIG()->dump(), 'Site Configuration'); ?>
```

<br/>

-----

### Configuration Options

For a list of all available options that be configured through your theme's `config.php` file, refer to [this list ↗](https://github.com/louiswalch/Wordpress-Theme-Framework/blob/master/docs/configuration_options.md).

<br/>

------

### Directory Structure

By default, the framework is configured for the following organization. Most are optional based on what aspects of the system you are using. Paths relative to your theme root.

- `_framework/`
    - `assets/` *Login, Dashboard & Editor CSS files.*
    - `config_development.php` *Development site overrides.*
    - `config_staging.php` *Staging site overrides.*
    - `config.php` *Your custom framework configuration.*
    - `metabox/` *Custom Metabox HTML.*
    - `types/` *Custom post type declarations.*
- `_includes/` *Files being used with Includes.*
- `_modules/` *Blocks being used with Modules.*
- `assets/` *Theme CSS/JS/IMG/etc.*

<br/>

-----

### Environment Detection

Based on the site url, the framework will detect the current environment (development, staging, production). This information is used for loading the appropriate configuration file and adding a CSS class to the BODY. The domain match string is defined in the base configuration file if you need to update it to match your infrastructure.

| Environment | Domain Match |
|--|--|
| Development | .local |
| Staging | staging. |
| Producton | * |


---

### ~~Custom Post Types & Taxonomy~~

Not Yes.

<br/>

------


### License

[GNU General Public License v2.0](https://github.com/louiswalch/Wordpress-Theme-Framework/blob/master/LICENSE)

