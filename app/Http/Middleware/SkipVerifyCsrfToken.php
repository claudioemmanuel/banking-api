<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class SkipVerifyCsrfToken extends Middleware
{
    protected $except = [
        '/reset',
        '/event',
    ];
}
