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
    $thumbnailPath = fake()->randomElement([
      "reviews/brooke-lark-kXQ3J7_2fpc-unsplash.jpg",
      "reviews/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg",
      "reviews/victoria-shes-4MEL9XS-3JQ-unsplash.jpg"
    ]);

    $thumbnailUrl = "https://static.vegevery.my-raga-bhakti.com/" . $thumbnailPath;

    return [
      'star' => fake()->numberBetween(1, 5),
      'thumbnail_path' => $thumbnailPath,
      'thumbnail_url' => $thumbnailUrl,
      'text' => fake()->realText(200),
      'number_of_likes' => fake()->numberBetween(0, 500),
      'restaurant_id' => fake()->randomElement([1, 2, 3]),
      'user_id' => User::factory()
    ];
  }
}
