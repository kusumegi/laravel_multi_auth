<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Requestlogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('logging.enable_request_log')) {
            $this->writeLog($request);
        }

        return $next($request);
    }
    /**
     * ログ出力
     * @param Request $request
     * @return void
     */
    private function writeLog(Request $request): void
    {
        Log::debug('■リクエスト■ ' . $request->method(), ['url' => $request->fullUrl(), 'request' => $request->all()]);
    }
}
