Laravel Carousel
================

A Laravel 4 package for adding a carousel to a website

## Features

* Supports carousel slides with text panel that is typically a background image overlaid with title, intro para and
link, and also supports optional carousel navigation with icon image and title.
* Bundled FrozenNode/Administrator config file to manage the panel data, including custom actions for reordering
* Bundled view that you can include as a partial for example on your site's homepage, and a model method for getting
all the data to populate that view.

## Installation

Add the following to you composer.json file

    "fbf/laravel-carousel": "dev-master"

Run

    composer update

Add the following to app/config/app.php

    'Fbf\LaravelCarousel\LaravelCarouselServiceProvider'

Publish the config

    php artisan config:publish fbf/laravel-carousel

Run the migration

    php artisan migrate --package="fbf/laravel-carousel"

Create the relevant image upload directories that you specify in your config, e.g.

    public/uploads/packages/fbf/laravel-carousel/background/original
    public/uploads/packages/fbf/laravel-carousel/background/resized
    public/uploads/packages/fbf/laravel-carousel/icon/original
    public/uploads/packages/fbf/laravel-carousel/icon/resized

## Usage

In your controller

```php
$panels = Fbf\LaravelCarousel\Panel::getData();
return View::make('home')->with(compact('download', 'panels'));
```

In your blade template:

```html
@inlcude('laravel-carousel::carousel')
```

## Administrator

You can use the excellent Laravel Administrator package by frozennode to administer your carousel.

http://administrator.frozennode.com/docs/installation

A ready-to-use model config file for the Panel model (carousel_panels.php) is provided in the src/config/administrator directory of the package, which you can copy into the app/config/administrator directory (or whatever you set as the model_config_path in the administrator config file).