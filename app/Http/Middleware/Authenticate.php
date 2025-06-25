<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            // Customize redirect based on route prefix or path
            if ($request->is('admin/*')) {
                return route('admin.login'); // create this route if not existing
            }


            return route('login'); // default user login
        }

        return null;
    }
}
