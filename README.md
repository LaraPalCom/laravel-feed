# [laravel-feed](https://roumen.it/projects/laravel-feed)

[![Latest Stable Version](https://poser.pugx.org/roumen/feed/version.png)](https://packagist.org/packages/roumen/feed) [![Total Downloads](https://poser.pugx.org/roumen/feed/d/total.png)](https://packagist.org/packages/roumen/feed) [![Build Status](https://travis-ci.org/RoumenDamianoff/laravel-feed.png?branch=master)](https://travis-ci.org/RoumenDamianoff/laravel-feed) [![License](https://poser.pugx.org/roumen/feed/license.png)](https://packagist.org/packages/roumen/feed)

A simple feed generator for Laravel 5.

## Notes

Latest supported version for Laravel 4 is 2.8.* (e.g v2.8.5)

Branch dev-master is for development and is unstable

## Installation

Run the following command and provide the latest stable version (e.g v2.9.7) :

```bash
composer require roumen/feed
```

or add the following to your `composer.json` file :

```json
"roumen/feed": "~2.9"
```

Then register this service provider with Laravel :

```php
'Roumen\Feed\FeedServiceProvider',
```

And add an alias to app.php:

```php
'Feed' => 'Roumen\Feed\Facades\Feed',
```

## Examples

[How to generate basic feed (with optional caching)](https://github.com/RoumenDamianoff/laravel-feed/wiki/basic-feed)

[How to add images to your feed](https://github.com/RoumenDamianoff/laravel-feed/wiki/How-to-add-images-to-your-feed)

[How to use custom view for your feed](https://github.com/RoumenDamianoff/laravel-feed/wiki/How-to-use-custom-view)

[How to use custom content-type for your feed](https://github.com/RoumenDamianoff/laravel-feed/wiki/How-to-use-custom-content-type)

and more in the [Wiki](https://github.com/RoumenDamianoff/laravel-feed/wiki)
