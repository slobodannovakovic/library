<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookResourceCollection;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (
            $request->user() 
            && $request->filled('filter')
            && method_exists(Book::class, 'scope' . ucfirst($request->get('filter')))
        ) {
            return BookResource::collection(
                Book::{$request->get('filter')}()->paginate(Book::PAGINATION_COUNT)
            );
        }

        return BookResource::collection(Book::paginate(Book::PAGINATION_COUNT));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return new BookResource($book);
    }
}
