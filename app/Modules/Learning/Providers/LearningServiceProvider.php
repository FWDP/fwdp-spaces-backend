<?php
namespace App\Modules\Learning\Providers;

use Illuminate\Support\ServiceProvider;

class LearningServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}