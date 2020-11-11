<?php
namespace NominatimLaravel\Providers;

use Illuminate\Support\ServiceProvider;

class NominatimLaravelProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/nominatim.php' => config_path('nominatim.php'),
        ]);
    }
}
