<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Обрабатывает входящий запрос и логирует всю информацию.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = uniqid('req_', true);
        
        // Логируем входящий запрос
        Log::channel('requests')->info('=== ВХОДЯЩИЙ ЗАПРОС ===', [
            'request_id' => $requestId,
            'timestamp' => now()->toDateTimeString(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'route' => $request->route()?->getName(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
            'params' => $request->all(),
            'headers' => $this->sanitizeHeaders($request->headers->all()),
        ]);

        $startTime = microtime(true);
        
        try {
            // Обрабатываем запрос
            $response = $next($request);
            
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            // Логируем ответ
            Log::channel('requests')->info('=== ОТВЕТ ===', [
                'request_id' => $requestId,
                'status' => $response->getStatusCode(),
                'duration_ms' => $duration,
                'content_type' => $response->headers->get('Content-Type'),
                'content_length' => strlen($response->getContent()),
            ]);
            
            return $response;
            
        } catch (\Throwable $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);
            
            // Логируем ошибку
            Log::channel('requests')->error('=== ОШИБКА В ЗАПРОСЕ ===', [
                'request_id' => $requestId,
                'duration_ms' => $duration,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw $e;
        }
    }
    
    /**
     * Очищает заголовки от чувствительной информации
     */
    private function sanitizeHeaders(array $headers): array
    {
        $sensitive = ['authorization', 'cookie', 'x-csrf-token'];
        
        foreach ($sensitive as $key) {
            if (isset($headers[$key])) {
                $headers[$key] = ['***СКРЫТО***'];
            }
        }
        
        return $headers;
    }
}
