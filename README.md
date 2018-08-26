# [laravel-feed](https://laravelium.com)

[![Latest Stable Version](https://poser.pugx.org/laravelium/feed/version.png)](https://packagist.org/packages/laravelium/feed) [![Total Downloads](https://poser.pugx.org/roumen/feed/d/total.png)](https://packagist.org/packages/laravelium/feed) [![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://gitlab.com/Laravelium/Feed/blob/master/LICENSE) [![Contributing](https://img.shields.io/badge/PRs-welcome-blue.svg)](https://gitlab.com/Laravelium/Feed/blob/master/CONTRIBUTING.md)

A simple feed generator for Laravel 5.

## Notes

Branch dev-master is for development and is UNSTABLE

## Installation

Run the following command and provide the latest stable version (e.g v3.0.0) :

```bash
composer require laravelium/feed
```

or add the following to your `composer.json` file :

#### For Laravel 5.7
```json
"laravelium/feed": "3.0.*"
```

#### For Laravel 5.6
```json
"laravelium/feed": "2.12.*"
```

#### For Laravel 5.5
```json
"laravelium/feed": "2.11.*"
```

Publish package views (OPTIONAL) :

```bash
php artisan vendor:publish --provider="Laravelium\Feed\FeedServiceProvider"
```

## Examples

[How to generate basic feed (with optional caching)](https://gitlab.com/Laravelium/Feed/wikis/basic-feed)

[How to generate multiple feeds](https://gitlab.com/Laravelium/Feed/wikis/multiple-feeds)

[How to add images to your feed](https://gitlab.com/Laravelium/Feed/wikis/How-to-add-images-to-your-feed)

[How to use custom view for your feed](https://gitlab.com/Laravelium/Feed/wikis/How-to-use-custom-view)

[How to use custom content-type for your feed](https://gitlab.com/Laravelium/Feed/wikis/How-to-use-custom-content-type)

and more in the [Wiki](https://gitlab.com/Laravelium/Feed/wikis)
