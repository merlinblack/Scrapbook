<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Inertia\Inertia;

class ArticleNotFound extends \Exception
{
    public function render($request)
    {
        return Inertia::render('ArticleNotFound')
            ->toResponse($request)->setStatusCode(404);
    }
}
