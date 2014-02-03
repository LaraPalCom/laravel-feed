# [laravel-feed](http://roumen.it/projects/laravel-feed)

[![Latest Stable Version](https://poser.pugx.org/roumen/feed/version.png)](https://packagist.org/packages/roumen/feed) [![Total Downloads](https://poser.pugx.org/roumen/feed/d/total.png)](https://packagist.org/packages/roumen/feed) [![Build Status](https://travis-ci.org/RoumenDamianoff/laravel-feed.png?branch=master)](https://travis-ci.org/RoumenDamianoff/laravel-feed)

A simple feed generator for Laravel 4.


## Installation

Add the following to your `composer.json` file :

```json
"roumen/feed": "dev-master"
```

Then register this service provider with Laravel :

```php
'Roumen\Feed\FeedServiceProvider',
```

And add an alias to app.php:

```php
'Feed' => 'Roumen\Feed\Facades\Feed',
```

## Example

```php
Route::get('feed', function(){

    // creating rss feed with our most recent 20 posts
    $posts = DB::table('posts')->orderBy('created', 'desc')->take(20)->get();

    $feed = Feed::make();

    // set your feed's title, description, link, pubdate and language
    $feed->title = 'Your title';
    $feed->description = 'Your description';
    $feed->logo = 'http://yoursite.tld/logo.jpg';
    $feed->link = URL::to('feed');
    $feed->pubdate = $posts[0]->created;
    $feed->lang = 'en';

    foreach ($posts as $post)
    {
        // set item's title, author, url, pubdate and description
        $feed->add($post->title, $post->author, URL::to($post->slug), $post->created, $post->description);
    }

    // show your feed (options: 'atom' (recommended) or 'rss')
    return $feed->render('atom');

});
```