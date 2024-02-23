<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder; // namsespaceに同じ名前は不可
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
    public function scopePopular(Builder $query, $from = null, $to = null): Builder | QueryBuilder
    {
        return $query->withCount([
            'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ])->orderBy('reviews_count', 'desc');
    }

    // 評価が高い順にソートする
    // 'reviews_avg_rating'はwithAvg()により自動で作成される
    public function scopeHighestRated(Builder $query,  $from = null, $to = null): Builder | QueryBuilder
    {
        return $query->withAvg([
            'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
        ], 'rating')->orderBy('reviews_avg_rating', 'desc');
    }

    // having句が集計関数の結果に基づいてフィルタリングを行うSQLの特性があるのでwithCount('reviews')と一緒に使用する
    // $books = Book::withCount('reviews')->minReviews(5)->get();
    // 例えばこれは各書籍のレビュー数を計算し、レビュー数が5以上の書籍のみをフィルタリング可能
    public function scopeMinReviews(Builder $query, int $minReviews): Builder | QueryBuilder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }


    private function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        // レビューの作成日に基づいてフィルタリング
        //$fromが指定されて、$toが指定されていない場合、$from以降に作成されたレビューのみをカウント
        // $fromが指定なし、$toが指定されている場合、$to以前に作成されたレビューのみをカウント
        // $fromと$toの両方が指定されている場合、その期間内に作成されたレビューのみをカウント
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }
}
