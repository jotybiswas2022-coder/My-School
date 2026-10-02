<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'guest' => RedirectIfAuthenticated::class,
        ]);

        // Apply the visitor's chosen language on every web request.
        $middleware->web(append: [SetLocale::class]);

        // Send unauthenticated visitors to the correct login screen.
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('student') || $request->is('student/*')) {
                return route('student.login');
            }

            return route('admin.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
