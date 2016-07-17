<?php namespace Roumen\Feed;

use Illuminate\Support\ServiceProvider;

class FeedServiceProvider extends ServiceProvider
{

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
        $this->loadViewsFrom(__DIR__ . '/../../views', 'feed');

        $this->publishes([
            __DIR__ . '/../../views' => base_path('resources/views/vendor/feed')
        ], 'views');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('feed', function()
        {
            return new Feed();
        });

        $this->app->alias('feed', 'Roumen\Feed\Feed');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['feed'];
    }

}