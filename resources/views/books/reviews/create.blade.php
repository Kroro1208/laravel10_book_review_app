@extends('layouts.app')

@section('content')
<div class="flex justify-between gap-5">
    <h1 class="mb-10 text-2xl">本「{{$book->title}}」に対するレビューを書く</h1>
    <button class="text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 rounded-xl text-sm w-15 h-10 px-4 text-center dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">
        <a href="{{route('books.show', $book)}}">戻る</a>
    </button>
</div>



<form method="POST" action="{{route('books.reviews.store', $book)}}">
    @csrf
    @error('review')
    <div class="alert alert-danger text-red-500">{{ $message }}</div>
    @enderror
    <label for="review">レビュー内容を入力してください</label>
    <textarea name="review" id="review" cols="30" rows="10" required class="input mb-4" placeholder="10文字以上で記載してください"></textarea>

    <label for="rating"></label>
    <select name="rating" id="rating" class="input mb-4" required>
        <option value="">星を選択してください</option>
        @for($i=1; $i <=5; $i++) <option value="{{$i}}">{{$i}}</option>@endfor
    </select>
    <button type="submit" class="text-gray-900 bg-gradient-to-r from-red-200 via-red-300 to-yellow-200 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-red-100 dark:focus:ring-red-400 font-medium rounded-xl text-sm w-25 px-4 py-2.5 text-center">レビューを送る</button>
</form>

@endsection