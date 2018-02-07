# [laravel-feed](https://laravelium.com)

[![Latest Stable Version](https://poser.pugx.org/roumen/feed/version.png)](https://packagist.org/packages/roumen/feed) [![Total Downloads](https://poser.pugx.org/roumen/feed/d/total.png)](https://packagist.org/packages/roumen/feed) [![Build Status](https://travis-ci.org/Laravelium/laravel-feed.png?branch=master)](https://travis-ci.org/Laravelium/laravel-feed) [![License](https://poser.pugx.org/roumen/feed/license.png)](https://packagist.org/packages/roumen/feed)

A simple feed generator for Laravel 5.

## Notes

Branch dev-master is for development and is UNSTABLE

## Installation

Run the following command and provide the latest stable version (e.g v2.12.1) :

```bash
composer require roumen/feed
```

or add the following to your `composer.json` file :

#### For Laravel 5.6
```json
"roumen/feed": "2.12.*"
```

#### For Laravel 5.5
```json
"roumen/feed": "2.11.*"
```


#### For Laravel 5.4 and lower
```json
"roumen/feed": "2.10.*"
```

If you are using laravel 5.5 or higher you can skip the service provider registration!

#### for Laravel 5.4 and lower register this service provider with Laravel :

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

[How to generate basic feed (with optional caching)](https://github.com/Laravelium/laravel-feed/wiki/basic-feed)

[How to generate multiple feeds](https://github.com/Laravelium/laravel-feed/wiki/multiple-feeds)

[How to add images to your feed](https://github.com/Laravelium/laravel-feed/wiki/How-to-add-images-to-your-feed)

[How to use custom view for your feed](https://github.com/Laravelium/laravel-feed/wiki/How-to-use-custom-view)

[How to use custom content-type for your feed](https://github.com/Laravelium/laravel-feed/wiki/How-to-use-custom-content-type)

and more in the [Wiki](https://github.com/Laravelium/laravel-feed/wiki)
