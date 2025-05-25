<?php

// app/Http/Middleware/EnsureCustomer.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class EnsureCustomer
{
    public function handle($request, Closure $next)
    {
        if (Session::get('login_mode') !== 'customer') {
            return redirect()->route('landingpage');
        }
        return $next($request);
    }
}
