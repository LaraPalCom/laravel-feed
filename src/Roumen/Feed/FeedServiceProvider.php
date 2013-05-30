<?php namespace Roumen\Feed;

use Illuminate\Support\ServiceProvider;

class FeedServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('roumen/feed');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //$this->package('roumen/feed');

        $this->app['feed'] = $this->app->share(function($app)
        {
            return new Feed();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}