# Laravel Wordpress Plugin

[TODO: add description, cases when it is helpful]

## Installation

First change to plugin directory.

```
$ cd wp-content/plugins/laravel-wordpress/ 
```

Then download the Laravel installer using Composer. Composer is included to plugin directory, 
so do not worry if you don't have it installed globally.

```
$ composer require "laravel/installer"
```

Install Laravel via Composer `create-project` command. Please do not mention any project name, because it 
is needed to have the default name `laravel`. 

```
$ composer create-project --prefer-dist laravel/laravel
``` 

Finally visit WordPress admin panel and you will be able to see new item in left admin menu. 

[TODO: add screenshot]

## Localization

Add l10 files to `laravel-wordpress/languages/` folder and uncomment 
`laravel_wordpress_load_textdomain()` function in plugin initialization 
file `laravel-wordpress/index.php` to change the default plugin's caption in  WordPress admin menu.    

## Caveats

Just after framework installation WordPress admin panel CSS will be overridden by the Laravel starter page which
includes default styling. Removing CSS block within starter page will fix this.
 
Also note that using Laravel "under WordPress hood" as a plugin does not require to wrap Laravel views by default html 
tags like \<html\>, \<body\>. Hence `layout.blade.php` file could look like this:

```
<div class="wrap">
    @yield('content')
</div>
```    

## License 

This plugin is released under the [GPLv3 License](LICENSE).