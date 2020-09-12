# **[Laravelium Feed](https://laravelium.com) package**

[![License](https://poser.pugx.org/laravelium/feed/license)](https://packagist.org/packages/laravelium/feed) [![PHP tests](https://github.com/Laravelium/laravel-feed/workflows/PHP%20tests/badge.svg?branch=master)](https://github.com//Laravelium/laravel-feed/actions?query=workflow%3A%22PHP+tests%22) [![Test Coverage](https://api.codeclimate.com/v1/badges/4c293ed962c1328bfcfc/test_coverage)](https://codeclimate.com/github/Laravelium/laravel-feed/test_coverage) [![Maintainability](https://api.codeclimate.com/v1/badges/4c293ed962c1328bfcfc/maintainability)](https://codeclimate.com/github/Laravelium/laravel-feed/maintainability) [![Style Status](https://github.styleci.io/repos/10391723/shield?style=normal&branch=master)](https://github.styleci.io/repos/10391723) [![Latest Stable Version](https://poser.pugx.org/laravelium/feed/v/stable)](https://packagist.org/packages/laravelium/feed) [![Total Downloads](https://poser.pugx.org/laravelium/feed/downloads)](https://packagist.org/packages/laravelium/feed)

*Laravelium Feed package for Laravel.*

## Notes

- Dev branches are for development and are **UNSTABLE** (*use on your own risk*)!

## Installation

Run the following command and provide the latest stable version (e.g v8.\*) :

```bash
composer require laravelium/feed
```

or add the following to your `composer.json` file :

#### For Laravel 8
```json
"laravelium/feed": "8.*"
```
(development branch)
```json
"laravelium/feed": "8.x-dev"
```

#### For Laravel 7
```json
"laravelium/feed": "7.*"
```
(development branch)
```json
"laravelium/feed": "7.x-dev"
```

#### For Laravel 6
```json
"laravelium/feed": "6.*"
```
(development branch)
```json
"laravelium/feed": "6.x-dev"
```

#### For Laravel 5.8
```json
"laravelium/feed": "3.1.*"
```
(development branch)
```json
"laravelium/feed": "3.1.x-dev"
```

#### For Laravel 5.7
```json
"laravelium/feed": "3.0.*"
```
(development branch)
```json
"laravelium/feed": "3.0.x-dev"
```

#### For Laravel 5.6
```json
"laravelium/feed": "2.12.*"
```
(development branch)
```json
"laravelium/feed": "2.12.x-dev"
```

#### For Laravel 5.5
```json
"laravelium/feed": "2.11.*"
```
(development branch)
```json
"laravelium/feed": "2.11.x-dev"
```

Publish package views (OPTIONAL) :

```bash
php artisan vendor:publish --provider="Laravelium\Feed\FeedServiceProvider"
```

## Examples

[How to generate basic feed (with optional caching)](https://github.com/Laravelium/laravel-feed/wiki/basic-feed)

[How to generate multiple feeds](https://github.com/Laravelium/laravel-feed/wiki/Multiple-Feeds)

[How to add images to your feed](https://github.com/Laravelium/laravel-feed/wiki/How-to-add-images-to-your-feed)

[How to use custom view for your feed](https://github.com/Laravelium/laravel-feed/wiki/How-to-use-custom-view)

[How to use custom content-type for your feed](https://github.com/Laravelium/laravel-feed/wiki/How-to-use-custom-content-type)

and more in the [Wiki](https://github.com/Laravelium/laravel-feed/wiki)

## Contribution guidelines

Before submiting new merge request or creating new issue, please read [contribution guidelines](https://github.com/Laravelium/laravel-feed/blob/master/CONTRIBUTING.md).

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
