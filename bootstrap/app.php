<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

if (!file_exists($app->environmentFilePath())) {
    $src = database_path('install.php');
    $dest = base_path('install.php');

    if (file_exists($src)) {
        copy($src, $dest);
    } else {
        echo 'Not Found install.php';
        exit;
    }
    header('Location: /install.php');
    exit;
} else {
    if (file_exists(base_path('install.php'))) unlink(base_path('install.php'));
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append([
            App\Http\Middleware\TrustProxies::class,     
        ]);
        $middleware->alias([
            'admin' => App\Http\Middleware\AdminOnly::class,
            'member' => App\Http\Middleware\MemberOnly::class,
            'guest' => App\Http\Middleware\GuestOnly::class,
            'rate_limit' => App\Http\Middleware\RateLimit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
