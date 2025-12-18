<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Modules\Auth\Http\Middleware\Api\CheckTelegramChannelAccessMiddleware;
use Modules\Auth\Http\Middleware\CheckTelegramKeyMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        /*
        |--------------------------------------------------------------------------
        | TRUST PROXIES (RENDER / HTTPS)  🔴 ENG MUHIM QISM
        |--------------------------------------------------------------------------
        | Render HTTPS’ni reverse proxy orqali beradi.
        | Bu sozlama Laravel’ga HTTPS’da ekanini to‘g‘ri tushuntiradi.
        */
        $middleware->trustProxies(
            at: '*',
            headers:
                Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
        );

        /*
        |--------------------------------------------------------------------------
        | WEB MIDDLEWARE
        |--------------------------------------------------------------------------
        */
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | API MIDDLEWARE
        |--------------------------------------------------------------------------
        */
        $middleware->api(append: [
            EnsureFrontendRequestsAreStateful::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | MIDDLEWARE ALIASES
        |--------------------------------------------------------------------------
        */
        $middleware->alias([
            'check.telegram.key' => CheckTelegramKeyMiddleware::class,
            'api.check.telegram.access' => CheckTelegramChannelAccessMiddleware::class,
        ]);

        /*
        |--------------------------------------------------------------------------
        | COOKIE ENCRYPTION
        |--------------------------------------------------------------------------
        */
        $middleware->encryptCookies(except: [
            '__token',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
