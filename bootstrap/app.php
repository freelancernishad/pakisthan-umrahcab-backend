<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',  // This loads routes with custom prefix defined in `api.php`
        commands: __DIR__ . '/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \App\Http\Middleware\AttachJwtFromCookie::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\AttachJwtFromCookie::class,
        ]);

        $middleware->append(\App\Http\Middleware\ApiResponse::class);
        $middleware->append(\App\Http\Middleware\CompressionMiddleware::class);
        $middleware->append(\App\Http\Middleware\WhitelistOriginMiddleware::class);
        $middleware->validateCsrfTokens(except: [
            '/api/stripe/webhook',
            '/api/payment/stripe/webhook', // Exclude Stripe webhook
        ]);
        $middleware->encryptCookies(except: [
            'admin_token',
            'token',
            'user_token',
            'company_token',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
            return response()->json([
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
            ], 422);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            return response()->json([
                'message' => 'The requested resource was not found.',
            ], 404);
        });

        $exceptions->render(function (\Throwable $e, $request) {
            return response()->json([
                'message' => $e->getMessage() ?: 'Server Error',
            ], 500);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'message' => 'The ' . $request->getMethod() . ' method is not supported for this route. Supported methods: ' . (method_exists($e, 'getAllowedMethods') ? implode(', ', $e->getAllowedMethods()) : 'N/A') . '.',
            ], 405);
        });

        $exceptions->shouldRenderJsonWhen(function ($request, $e) {
            return true;
        });
    });

return $app->create();
