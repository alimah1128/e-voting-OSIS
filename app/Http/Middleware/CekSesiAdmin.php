<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekSesiAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('admin_id')) {
            return redirect('/admin/login')->withErrors(['username' => 'Silakan login terlebih dahulu.']);
        }

        return $next($request);
    }
}