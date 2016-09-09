# [laravel-feed](https://roumen.it/projects/laravel-feed)

[![Latest Stable Version](https://poser.pugx.org/roumen/feed/version.png)](https://packagist.org/packages/roumen/feed) [![Total Downloads](https://poser.pugx.org/roumen/feed/d/total.png)](https://packagist.org/packages/roumen/feed) [![Build Status](https://travis-ci.org/RoumenDamianoff/laravel-feed.png?branch=master)](https://travis-ci.org/RoumenDamianoff/laravel-feed) [![License](https://poser.pugx.org/roumen/feed/license.png)](https://packagist.org/packages/roumen/feed)

A simple feed generator for Laravel 5.

## Notes

Branch dev-master is for development and is UNSTABLE

## Installation

Run the following command and provide the latest stable version (e.g v2.10.5) :

```bash
composer require roumen/feed
```

or add the following to your `composer.json` file :

```json
"roumen/feed": "~2.10"
```

Then register this service provider with Laravel :

```php
Roumen\Feed\FeedServiceProvider::class,
```

and add class alias :

```php
'Feed' => Roumen\Feed\Feed::class,
```

Publish package views (OPTIONAL) :

```bash
php artisan vendor:publish --provider="Roumen\Feed\FeedServiceProvider"
```

## Examples

[How to generate basic feed (with optional caching)](https://github.com/RoumenDamianoff/laravel-feed/wiki/basic-feed)

[How to generate multiple feeds](https://github.com/RoumenDamianoff/laravel-feed/wiki/multiple-feeds)

[How to add images to your feed](https://github.com/RoumenDamianoff/laravel-feed/wiki/How-to-add-images-to-your-feed)

[How to use custom view for your feed](https://github.com/RoumenDamianoff/laravel-feed/wiki/How-to-use-custom-view)

[How to use custom content-type for your feed](https://github.com/RoumenDamianoff/laravel-feed/wiki/How-to-use-custom-content-type)

and more in the [Wiki](https://github.com/RoumenDamianoff/laravel-feed/wiki)
