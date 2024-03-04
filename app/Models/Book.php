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
    public function scopeTitle(Builder $query, string $title): Builder // title()
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null) // withReviewsCount()
    {
        return $query->withCount('reviews');
        // return $query->withCount([ // モデルに関連するコメントの数を取得
        //     'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
        // ]);
    }

    public function scopeWithAvgRating(Builder $query, $from = null, $to = null) // withAvgRating()
    {
        return $query->withAvg('reviews', 'rating');
        // return $query->withAvg([ // 関連するモデルの特定のカラムに対する平均値を計算し、その結果を元のモデルのロード時にカラムとして追加
        //     'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to)
        // ], 'rating');
    }

    // レビューの数が多い順にソートする
    // 'reviews_count'はwithCount()により自動で作成される
    public function scopePopular(Builder $query): Builder | QueryBuilder // popular()
    {
        return $query->withReviewsCount()->orderBy('reviews_count', 'desc');
    }

    // 評価が高い順にソートする
    // 'reviews_avg_rating'はwithAvg()により自動で作成される
    public function scopeHighestRated(Builder $query): Builder | QueryBuilder // highestRated()
    {
        return $query->withAvgRating()->orderBy('reviews_avg_rating', 'desc');
    }

    // having句が集計関数の結果に基づいてフィルタリングを行うSQLの特性があるのでwithCount('reviews')と一緒に使用する
    // $books = Book::withCount('reviews')->minReviews(5)->get();
    // 例えばこれ↑は各書籍のレビュー数を計算し、レビュー数が5以上の書籍のみをフィルタリング可能
    public function scopeMinReviews(Builder $query, int $minReviews): Builder | QueryBuilder // minReviews()
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }


    // private function dateRangeFilter(Builder $query, $from = null, $to = null)
    // {
    //     // レビューの作成日に基づいてフィルタリング
    //     //$fromが指定されて、$toが指定されていない場合、$from以降に作成されたレビューのみをカウント
    //     // $fromが指定なし、$toが指定されている場合、$to以前に作成されたレビューのみをカウント
    //     // $fromと$toの両方が指定されている場合、その期間内に作成されたレビューのみをカウント
    //     if ($from && !$to) {
    //         $query->where('created_at', '>=', $from);
    //     } elseif (!$from && $to) {
    //         $query->where('created_at', '<=', $to);
    //     } elseif ($from && $to) {
    //         $query->whereBetween('created_at', [$from, $to]);
    //     }
    // }

    public function scopePopularLastMonth(Builder $query): Builder | QueryBuilder // popularLatestMonth()
    {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);
    }

    public function scopePopularLast6Months(Builder $query): Builder | QueryBuilder // popularLast6Months()
    {
        return $query->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(4);
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder | QueryBuilder // highestRatedLastMonth()
    {
        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(2);
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder | QueryBuilder // highestRatedLast6Months()
    {
        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(4);
    }

    protected static function booted()
    { // 特定のモデルイベント（例えば、更新や削除）が発生した際にキャッシュをクリア(これで作成したレビューが最上位に来る)
        static::updated(fn (Book $book) => cache()->forget('book:' . $book->id));
        static::deleted(fn (Book $book) => cache()->forget('book:' . $book->id));
    }
}
