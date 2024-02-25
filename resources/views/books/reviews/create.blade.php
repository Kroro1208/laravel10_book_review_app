@extends('layouts.app')

@section('content')
<h1 class="mb-10 text-2xl">本「{{$book->title}}」に対するレビューを書く</h1>
<form method="POST" action="{{route('books.reviews.store', $book)}}">
    @csrf
    <label for="review">レビュー</label>
    <textarea name="review" id="review" cols="30" rows="10" required class="input mb-4"></textarea>

    <label for="rating"></label>
    <select name="rating" id="rating" class="input mb-4" required>
        <option value="">星を選択してください</option>
        @for($i=1; $i <=5; $i++) <option value="{{$i}}">{{$i}}</option>
            @endfor
    </select>
    <button type="submit" class="btn">レビューを送る</button>
</form>
@endsection