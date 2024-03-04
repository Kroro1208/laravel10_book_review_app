<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**titleスコープを利用して、inputで入力された値が含まれる本を表示するメソッドを実装
     * whenメソッドは、第一引数がtrueの場合（つまり、$titleがnullや空文字列でない場合）に、
     * 第二引数で指定されたクロージャ（無名関数）を実行します。
     * これにより、指定されたタイトルを含む書籍を検索するための条件がクエリに追加される
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter', '');
        $books = Book::when(
            $title,
            fn ($query, $title) => $query->title($title) // title()はBookモデルで実装したscopeTitle()のこと
        );

        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()->withAvgRating()->withReviewsCount()
        };

        // クエリを実行して結果を取得
        $cacheKey = 'books:' . $filter . ':' . $title;
        $books =
            cache()->remember(
                $cacheKey,
                3600,
                fn () =>
                $books->get()
            );

        return view('books.index', compact('books')); // ['books'=>[]]
    }

    /**
     * 詳細ページは最新順に取得して表示させた
     */
    public function show(int $id)
    {
        $cacheKey = 'book:' . $id;
        $book =  cache()->remember(
            $cacheKey,
            3600,
            fn () => Book::with([
                'reviews' => fn ($query) => $query->latest()
            ])->withAvgRating()->withReviewsCount()->findOrFail($id)
        );
        return view('books.show', ['book' => $book]);
    }
}
