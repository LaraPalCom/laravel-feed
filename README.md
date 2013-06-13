# [laravel4-feed](http://roumen.me/projects/laravel4-feed)

[![Latest Stable Version](https://poser.pugx.org/roumen/feed/version.png)](https://packagist.org/packages/roumen/feed) [![Total Downloads](https://poser.pugx.org/roumen/feed/d/total.png)](https://packagist.org/packages/roumen/feed)

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

## Example

```php
Route::get('feed', function(){

    // creating rss feed with our most recent 20 posts
    $posts = DB::table('posts')->order_by('created', 'desc')->take(20)->get();

    $feed = App::make("feed");

    // set your feed's title, description, link, pubdate and language
    $feed->title = 'Your title';
    $feed->description = 'Your description';
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