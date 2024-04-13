<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

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
    $thumbnail_path = fake()->randomElement([
      "reviews/brooke-lark-kXQ3J7_2fpc-unsplash.jpg",
      "reviews/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg",
      "reviews/victoria-shes-4MEL9XS-3JQ-unsplash.jpg"
    ]);

    $thumbnail_url = "https://static.vegevery.my-raga-bhakti.com/" . $thumbnail_path;

    return [
      'star' => fake()->numberBetween($min = 1, $max = 5),
      'thumbnail_path' => $thumbnail_path,
      'thumbnail_url' => $thumbnail_url,
      'text' => fake()->realText($maxNbChars = 200),
      'number_of_likes' => fake()->numberBetween($min = 0, $max = 500),
      'restaurant_id' => fake()->randomElement([1, 2, 3]),
      'user_id' => User::factory()
    ];
  }
}
