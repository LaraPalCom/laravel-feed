# **[Laravelium Feed](https://laravelium.com) package**

*Laravelium Feed package for Laravel.*

## Notes

- Dev branches are for development and are **UNSTABLE** (*use on your own risk*)!

## Installation

Run the following command and provide the latest stable version (e.g v7.0.\*) :

```bash
composer require laravelium/feed
```

or add the following to your `composer.json` file :

#### For Laravel 7.0
```json
"laravelium/feed": "7.0.*"
```
(development branch)
```json
"laravelium/feed": "7.0.x-dev"
```

#### For Laravel 6.0
```json
"laravelium/feed": "6.0.*"
```
(development branch)
```json
"laravelium/feed": "6.0.x-dev"
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

[How to generate basic feed (with optional caching)](https://gitlab.com/Laravelium/Feed/wikis/basic-feed)

[How to generate multiple feeds](https://gitlab.com/Laravelium/Feed/wikis/multiple-feeds)

[How to add images to your feed](https://gitlab.com/Laravelium/Feed/wikis/How-to-add-images-to-your-feed)

[How to use custom view for your feed](https://gitlab.com/Laravelium/Feed/wikis/How-to-use-custom-view)

[How to use custom content-type for your feed](https://gitlab.com/Laravelium/Feed/wikis/How-to-use-custom-content-type)

and more in the [Wiki](https://gitlab.com/Laravelium/Feed/wikis)

## Contribution guidelines

Before submiting new merge request or creating new issue, please read [contribution guidelines](https://gitlab.com/Laravelium/Feed/blob/master/CONTRIBUTING.md).

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
