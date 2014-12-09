# [laravel-feed](http://roumen.it/projects/laravel-feed)

[![Latest Stable Version](https://poser.pugx.org/roumen/feed/version.png)](https://packagist.org/packages/roumen/feed) [![Total Downloads](https://poser.pugx.org/roumen/feed/d/total.png)](https://packagist.org/packages/roumen/feed) [![Build Status](https://travis-ci.org/RoumenDamianoff/laravel-feed.png?branch=master)](https://travis-ci.org/RoumenDamianoff/laravel-feed) [![License](https://poser.pugx.org/roumen/feed/license.png)](https://packagist.org/packages/roumen/feed)

A simple feed generator for Laravel 4.


## Installation

Run the following command and provide the latest stable version (e.g v2.7.4) :

```bash
composer require roumen/feed
```

or add the following to your `composer.json` file :

```json
"roumen/feed": "~2.7"
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

and more in the [Wiki](https://github.com/RoumenDamianoff/laravel-feed/wiki)

### Custom View

If you're not happy with the default views that come with the package or need to add new tags, you can render your own views but be sure that you use the correct xml and correct format(atom/rss) etc.

Call the `setCustomView($viewName)` method on the feed object **BEFORE** you call the `render()` method. The `setCustomView($viewName)` method takes one parameter and it's the name of the view. However, if the view does not exist, it will automagically default to the package view.

```php
$feed->setCustomView('pages.rss');
```
