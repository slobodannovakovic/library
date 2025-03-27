<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use Exception;
use Illuminate\Http\JsonResponse;

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
    public function store(BookStoreRequest $request): JsonResponse
    {
        try {
            $book = Book::create($request->all());
        } catch (Exception $exception) {
            return response()->json(['message' => 'Book already exists.'], 422);
        }

        $book->refresh();

        return response()->json(new BookResource($book));
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): JsonResponse
    {
        return response()->json(new BookResource($book));
    }
}
