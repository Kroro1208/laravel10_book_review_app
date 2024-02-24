@extends('layouts.app')

@section('content')
<h1 class="mb-10 text-2xl">Books</h1>
<form action="">

</form>
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