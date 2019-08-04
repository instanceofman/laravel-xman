<?php
namespace Isofman\LaravelXman;

use Illuminate\Support\ServiceProvider;

/**
 * Class XmanServiceProvider
 * @package Isofman\LaravelXman
 */
class XmanServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function boot()
    {

    }

    /**
     *
     */
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