<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(Book $book)
    {
        return view('books.reviews.create', ['book' => $book]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Book $book)
    {
        $data = $request->validate([
            'review' => 'required|min:10',
            'rating' => 'required|min:1|max:5|integer',
        ], [
            'review.min' => 'レビューは10文字以上で入力してください。',
        ]);

        $book->reviews()->create($data);
        return to_route('books.show', $book);
    }
}
