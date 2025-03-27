<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\BookResource;
use App\Http\Requests\BooksBorrowRequest;
use App\Http\Resources\BorrowedBooksResource;

class BooksBorrowController extends Controller
{
    public function borrow(BooksBorrowRequest $request): JsonResponse
    {
        $book = Book::available()
            ->where('id', $request->get('bookId'))
            ->first();

        if (empty($book)) {
            return response()->json([
                'message' => 'Book not available.'
            ], 422);
        }

        $book->users()->attach($request->user()->id);

        $book->update(['borrowed' => 1]);
        
        return response()->json(new BookResource($book), 201);
    }

    public function return(BooksBorrowRequest $request): JsonResponse
    {
        $user = User::find($request->user()->id);
        $bookId = $request->get('bookId');
        $borrowedBookIds = $user->books->pluck('id')->toArray();

        if (! in_array($bookId, $borrowedBookIds)) {
            return response()->json([
                'message' => 'Book not borrowed by user.'
            ], 422);
        }

        $book = Book::findOrFail($bookId);

        $book->users()->detach($user->id);

        $book->update(['borrowed' => 0]);

        return response()->json([
            'message' => 'User successfully returned the book.'
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        $user = User::find($request->user()->id);

        return response()->json(
            BorrowedBooksResource::collection($user->books)
        );
    }
}
