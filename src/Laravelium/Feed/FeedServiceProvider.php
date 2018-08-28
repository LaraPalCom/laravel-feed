<?php namespace Laravelium\Feed;

use Illuminate\Support\ServiceProvider;
use Laravelium\Feed\Feed;

class FeedServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

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

        $config_file = __DIR__ . '/../../config/config.php';

        $this->mergeConfigFrom($config_file, 'feed');

        $this->publishes([
            $config_file => config_path('feed.php')
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('feed', function ($app) {
            $config = config('feed');

            return new Feed(
                $config,
                $app['Illuminate\Cache\Repository'],
                $app['config'],
                $app['files'],
                $app['Illuminate\Contracts\Routing\ResponseFactory'],
                $app['view']
            );
        });

        $this->app->alias('feed', Feed::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['feed', Feed::class];
    }
}
