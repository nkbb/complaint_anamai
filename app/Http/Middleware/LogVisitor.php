<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\VisitorLog;
use Illuminate\Support\Facades\Auth;

class LogVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $userId = Auth::id();
        $key = 'visitor_log:' . $ip . ($userId ? ':user_'.$userId : '');

        if (!Cache::has($key)) {
            VisitorLog::create([
                'ip_address' => $ip,
                'user_id' => $userId,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
            ]);

            Cache::put($key, true, now()->addMinutes(10));
        }

        return $next($request);
    }
}
