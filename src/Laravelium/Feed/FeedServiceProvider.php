<?php

namespace Laravelium\Feed;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Routing\ResponseFactory;

class FeedServiceProvider extends ServiceProvider implements DeferrableProvider
{
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
        $this->app->bind('feed', function (Container $app) {
            $params = [
                'cache' => $app['Illuminate\Cache\Repository'],
                'config' => $app['config'],
                'files' => $app['files'],
                'response' => $app[ResponseFactory::class],
                'view' => $app['view']
            ];

            return new Feed($params);
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
