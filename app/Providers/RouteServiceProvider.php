<?php

namespace App\Providers;

/**
 * Laravel 11+ no longer registers routes through a service provider (see
 * bootstrap/app.php), but the laravel/ui auth scaffolding controllers still
 * reference RouteServiceProvider::HOME, so this small holder class is kept
 * around purely to provide that constant.
 */
class RouteServiceProvider
{
    /**
     * The path to the "home" route for the application.
     */
    public const HOME = '/';
}
