<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if the client supports gzip and zlib is enabled
        if (in_array('gzip', $request->getEncodings()) && function_exists('gzencode') && !config('app.debug')) {
            $content = $response->getContent();
            
            // Only compress if the content is large enough to justify it
            if (strlen($content) > 1024) {
                $response->setContent(gzencode($content, 9));
                $response->headers->set('Content-Encoding', 'gzip');
            }
        }

        return $response;
    }
}
