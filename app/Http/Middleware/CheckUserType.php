<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserType
{
    public function handle(Request $request, Closure $next, string ...$types)
    {
        $user = $request->user();

        if (!$user || !in_array($user->type, $types)) {
            abort(403, 'Acesso negado.');
        }

        return $next($request);
    }
}
