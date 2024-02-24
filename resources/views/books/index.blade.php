@extends('layouts.app')

@section('content')
<h1 class="mb-10 text-2xl">Books</h1>
<form class="mb-4 flex items-center gap-3 max-w-4xl" method="GET" action="{{route('books.index')}}">
    <input class="input h-10" type="text" name="title" placeholder="検索したい本のキーワードを入力" value="{{request('title')}}" />
    <input type="hidden" name="filter" value="{{request('filter')}}" />
    <button class="text-gray-900 bg-gradient-to-r from-red-200 via-red-300 to-yellow-200 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-red-100 dark:focus:ring-red-400 font-medium rounded-xl text-sm w-20 px-4 py-2.5 text-center" type="submit">検索</button>
    <a href="{{route('books.index')}}" class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-xl text-sm w-20 px-4 py-2 text-center dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">クリア</a>
</form>

<div class="filter-container mb-4 flex">
    @php
    $filters = [
    'latest' => '最新',
    'popular_last_month' => '先月の人気',
    'popular_last_6months' => '過去6ヶ月で人気',
    'highest_rated_last_month' => '先月の最高評価',
    'highest_rated_last_6months' => '過去6ヶ月の最高評価',
    ];
    @endphp

    @foreach($filters as $key => $label)
    <a href="{{route('books.index', [...request()->query(), 'filter'=> $key])}}" class="{{request('filter')===$key || (request('filter')===null && $key === '') ? 'filter-item-active' : 'filter-item'}}">
        {{$label}}
    </a>
    @endforeach

</div>
<ul>
    @forelse($books as $book)
    <li class="mb-4">
        <div class="book-item">
            <div class="flex flex-wrap items-center justify-between">
                <div class="w-full flex-grow sm:w-auto">
                    <a href="{{route('books.show', $book)}}" class="book-title">{{$book->title}}</a>
                    <span class="book-author">著者 {{$book->author}}</span>
                </div>
                <div>
                    <div class="book-rating">
                        {{number_format($book->reviews_avg_rating, 1)}}
                    </div>
                    <div class="book-review-count">
                        {{$book->reviews_count}} {{Str::plural('review', $book->review_count)}}
                    </div>
                </div>
            </div>
        </div>
    </li>
    @empty
    <li class="mb-4">
        <div class="empty-book-item">
            <p class="empty-text">本が見つかりませんでした</p>
            <a href="{{route('books.index')}}" class="reset-link">検索基準をリセット</a>
        </div>
    </li>
    @endforelse

</ul>
@endsection