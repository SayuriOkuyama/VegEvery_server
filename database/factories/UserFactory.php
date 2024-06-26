<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
   * The current password being used by the factory.
   */
  protected static ?string $password;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $iconStoragePath = fake()->randomElement(
      [
        "recipes/user_icon/IMG_3223.PNG",
        "recipes/user_icon/outside.jpeg",
        "recipes/user_icon/tabitha-turner-qtr0Lw4fMGc-unsplash.jpg",
        "recipes/user_icon/user_icon.png",
      ]
    );

    $iconUrl = "https://static.vegevery.my-raga-bhakti.com/" . $iconStoragePath;

    $faker = \Faker\Factory::create();

    // 一意な15文字の英数字の文字列を生成
    $uniqueString = $faker->unique()->regexify('[A-Za-z0-9]{15}');

    return [
      "account_id" => $uniqueString,
      'name' => fake()->name(),
      'password' => static::$password ??= Hash::make('password'),
      'secret_question' => fake()->realText(10),
      'answer_to_secret_question' => fake()->realText(10),
      'vegetarian_type' => fake()->randomElement([
        'vegan',
        'oriental_vegetarian',
        'ovo_vegetarian',
        'pescatarian',
        'lacto_vegetarian',
        'pollo_vegetarian',
        'fruitarian',
        'other_vegetarian'
      ]),
      'icon_url' => $iconUrl,
      "icon_storage_path" => $iconStoragePath
    ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   */
  public function unverified(): static
  {
    return $this->state(fn (array $attributes) => [
      'email_verified_at' => null,
    ]);
  }
}
