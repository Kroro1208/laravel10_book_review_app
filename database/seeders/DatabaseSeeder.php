<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * 33件のBookインスタンスを生成し、各本に対して5から30の間でランダムに選ばれた数の
     * 「良い」「平均」「悪い」評価を持つReviewインスタンスを関連付けて生成。
     * つまり、各評価のレビュー本データが5～30で生成される。
     */
    public function run(): void
    {
        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
                ->good()
                ->for($book) // for()は親モデルインスタンスに子モデルインスタンスを関連付ける
                ->create();
        });

        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
                ->average()
                ->for($book) // for()は親モデルインスタンスに子モデルインスタンスを関連付ける
                ->create();
        });

        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 30);

            Review::factory()->count($numReviews)
                ->bad()
                ->for($book) // for()は親モデルインスタンスに子モデルインスタンスを関連付ける
                ->create();
        });
    }
}
