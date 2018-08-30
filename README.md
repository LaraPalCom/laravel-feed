# [Laravelium Feed](https://laravelium.com)

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
or (development branch)
```json
"laravelium/feed": "3.0.x-dev"
```

#### For Laravel 5.6
```json
"laravelium/feed": "2.12.*"
```
or (development branch)
```json
"laravelium/feed": "2.12.x-dev"
```

#### For Laravel 5.5
```json
"laravelium/feed": "2.11.*"
```
or (development branch)
```json
"laravelium/feed": "2.11.x-dev"
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
