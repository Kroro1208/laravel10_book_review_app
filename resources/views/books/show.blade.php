@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="sticky top-0 mb-2 text-2xl">{{ $book->title }}</h1>

    <div class="book-info">
        <div class="book-author mb-4 text-lg font-semibold">by {{ $book->author }}</div>
        <div class="book-rating flex items-center">
            <div class="mr-2 text-sm font-medium text-slate-700">
                <!-- 小数点1以下まで表示 -->
                {{ number_format($book->reviews_avg_rating, 1) }} 
                <x-star-rating :rating="$book->reviews_avg_rating" />
            </div>
            <span class="book-review-count text-sm text-gray-500">
                {{ $book->reviews_count }} {{ Str::plural('review', 5) }}
            </span>
        </div>
    </div>
</div>


<div class="flex gap-5">
    <div class="mb-4">
        <a href="{{route('books.reviews.create', $book)}}" class="reset-link">レビューを作成する</a>
    </div>
    <div class="mb-4">
        <a href="{{route('books.index')}}" class="reset-link">一覧ページに戻る</a>
    </div>
</div>


<div>
    <h2 class="mb-4 text-xl font-semibold">レビュー</h2>
    <!-- <form action="{{ route('books.index') }}" method="GET">
        <div class="flex space-x-4 items-center mb-4">
            <div>
                <label for="from" class="block text-sm font-medium text-gray-700">開始日</label>
                <input type="date" id="from" name="from" value="{{ request('from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="to" class="block text-sm font-medium text-gray-700">終了日</label>
                <input type="date" id="to" name="to" value="{{ request('to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    フィルタリング
                </button>
            </div>
        </div>
    </form> -->
    <ul>
        @forelse ($book->reviews as $review)
        <li class="book-item mb-4">
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <div class="font-semibold">
                        {{ $review->rating }}
                        <x-star-rating :rating="$review->rating" />
                    </div>
                    <div class="book-review-count">
                        {{ $review->created_at->format('M j, Y') }}
                    </div>
                </div>
                <p class="text-gray-700">{{ $review->review }}</p>
            </div>
        </li>
        @empty
        <li class="mb-4">
            <div class="empty-book-item">
                <p class="empty-text text-lg font-semibold">No reviews yet</p>
            </div>
        </li>
        @endforelse
    </ul>
</div>
@endsection