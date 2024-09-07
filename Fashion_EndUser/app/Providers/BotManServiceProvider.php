<?php

namespace App\Providers;

use BotMan\BotMan\BotManFactory;
use Illuminate\Support\ServiceProvider;
use BotMan\BotMan\Drivers\DriverManager;

class BotManServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);
    }


    public function boot(): void
    {
        $this->app->singleton('botman', function ($app) {
            $config = [
                'driver' => 'web',
            ];
            return BotManFactory::create($config);
        });
    }
}
