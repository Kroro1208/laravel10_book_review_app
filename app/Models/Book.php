<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ここで定義しておくと$books = \App\Models\Book::title('きかんを')->get();という風に使用できる
    // scopeTitle()のscopeはprefixなので使用するときは省略する。残ったTitle()がtitle()となる
    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    // レビューの数が多い順にソートする
    // 'reviews_count'はwithCount()により自動で作成される
    public function scopePopular(Builder $query): Builder
    {
        return $query->withCount('reviews')->orderBy('reviews_count', 'desc');
    }

    // 評価が高い順にソートする
    // 'reviews_avg_rating'はwithAvg()により自動で作成される
    public function scopeHighestRated(Builder $query): BUilder
    {
        return $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
    }
}
