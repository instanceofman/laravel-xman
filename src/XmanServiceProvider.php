<?php
namespace Isofman\LaravelXman;

use Illuminate\Support\ServiceProvider as BaseProvider;

/**
 * Class XmanServiceProvider
 * @package Isofman\LaravelXman
 */
class XmanServiceProvider extends BaseProvider
{
    public function boot()
    {
        if($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/xman.php' => config_path('xman.php')
            ], 'xman-config');
        }
    }

    public function register()
    {
        $this->app->singleton('xman', function() {
            return new ExecutionManager;
        });

        // Register commands
        $this->commands([
            Commands\Setup::class,
        ]);
    }
}