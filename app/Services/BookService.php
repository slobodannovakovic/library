<?php

namespace App\Services;

use Illuminate\Http\Request;

class BookService
{
    public function requestHasFilter(Request $request): bool
    {
        return $request->filled('filter');
    }
}