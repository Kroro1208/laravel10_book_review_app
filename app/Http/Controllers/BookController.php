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

        $books = Book::when(
            $title,
            fn ($query, $title) => $query->title($title) // title()はBookモデルで実装したscopeTitle()のこと
        )->get();

        return view('books.index', compact('books')); // ['books'=>[]]
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
