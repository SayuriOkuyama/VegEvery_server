<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ArticleOfItem;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $image_path = fake()->randomElement(["brooke-lark-kXQ3J7_2fpc-unsplash.jpg", "haseeb-jamil-J9lD6FS6_cs-unsplash.jpg", "victoria-shes-4MEL9XS-3JQ-unsplash.jpg"]);
    if ($image_path === "brooke-lark-kXQ3J7_2fpc-unsplash.jpg") {
      $image_url = "https://sbbfkhueljpgbvhxguip.supabase.co/storage/v1/object/public/VegEvery-backet/recipes/thumbnail/brooke-lark-kXQ3J7_2fpc-unsplash.jpg";
    } elseif ($image_path === "haseeb-jamil-J9lD6FS6_cs-unsplash.jpg") {
      $image_url = "https://sbbfkhueljpgbvhxguip.supabase.co/storage/v1/object/public/VegEvery-backet/recipes/thumbnail/haseeb-jamil-J9lD6FS6_cs-unsplash.jpg";
    } else {
      $image_url = "https://sbbfkhueljpgbvhxguip.supabase.co/storage/v1/object/public/VegEvery-backet/recipes/thumbnail/victoria-shes-4MEL9XS-3JQ-unsplash.jpg";
    }
    return [
      'order' => fake()->unique()->randomNumber(3),
      'image_path' => $image_path,
      'image_url' => $image_url,
      'text' => fake()->realText($maxNbChars = 100),
      'article_of_item_id' => ArticleOfItem::factory()
    ];
  }
}
