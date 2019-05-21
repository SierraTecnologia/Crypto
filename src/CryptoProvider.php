<?php

namespace SierraTecnologia\Crypto;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use SierraTecnologia\Crypto\Services\Crypto;

class CryptoProvider extends ServiceProvider
{
    /**
     * Boot method.
     *
     * @return void
     */
    public function boot()
    {
        // Nothing here
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /*
        |--------------------------------------------------------------------------
        | Register the Services
        |--------------------------------------------------------------------------
        */

        $this->app->singleton('Crypto', function () {
            return new Crypto();
        });

        if (class_exists('Illuminate\Foundation\AliasLoader')) {
            $loader = AliasLoader::getInstance();
            $loader->alias('Crypto', \SierraTecnologia\Crypto\Services\Crypto::class);
        } else {
            class_alias(\SierraTecnologia\Crypto\Services\Crypto::class, 'Crypto');
        }

        /*
        |--------------------------------------------------------------------------
        | Blade Directives
        |--------------------------------------------------------------------------
        */

        // Crypto
        Blade::directive('crypto_encrypt', function ($expression) {
            return "<?php echo Crypto::encrypt($expression); ?>";
        });

        Blade::directive('crypto_decrypt', function ($expression) {
            return "<?php echo Crypto::encrypt($expression); ?>";
        });

        /*
        |--------------------------------------------------------------------------
        | Register the Commands
        |--------------------------------------------------------------------------
        */

        $this->commands([
            \SierraTecnologia\Crypto\Console\KeyMaster::class,
        ]);
    }
}
