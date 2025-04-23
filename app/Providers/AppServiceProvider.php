<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        if (!defined('ASSET_VERSION')) {
            define('ASSET_VERSION', config('constants.asset_version'));
        }

        if (!defined('MAIL_HOST')) {
            define('MAIL_HOST', config('constants.mail_host'));
        }

        if (!defined('MAIL_PORT')) {
            define('MAIL_PORT', config('constants.mail_port'));
        }

        if (!defined('MAIL_ENCRYPTION')) {
            define('MAIL_ENCRYPTION', config('constants.mail_encryption'));
        }

        if (!defined('MAIL_USERNAME')) {
            define('MAIL_USERNAME', config('constants.mail_username'));
        }

        if (!defined('MAIL_PASSWORD')) {
            define('MAIL_PASSWORD', config('constants.mail_password'));
        }

        if (!defined('MAIL_FROM_ADDRESS')) {
            define('MAIL_FROM_ADDRESS', config('constants.mail_from_address'));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
