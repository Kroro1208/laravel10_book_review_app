<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //$faker = \Faker\Factory::create('ja_JP');
        return [
            'book_id' => null,
            'review' => $this->faker->realText(200),
            'rating' => fake()->numberBetween(1, 5),
            'created_at' => fake()->dateTimeBetween('-2 years'),
            'updated_at' => fake()->dateTimeBetween('created_at', now()),
        ];
    }

    /**
     * $this->state()はLaravelのファクトリクラス内で使用され、モデルインスタンスに対して特定の状態を定義する
     * モデルの属性を変更するクロージャ（無名関数）を引数として受け取ることがポイント
     */

    public function good()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(4, 5)
            ];
        });
    }

    public function average()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(3, 5)
            ];
        });
    }

    public function bad()
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(1, 2)
            ];
        });
    }
}
