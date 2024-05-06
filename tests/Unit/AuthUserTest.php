<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class AuthUserTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();

    $this->withMiddleware('auth:sanctum');
  }

  /**
   * トークンがないと（ログアウト状態）認証エラーになることの確認
   * @return void
   */
  public function test_get_auth_user_with_no_token_トークンがないと認証エラーになる(): void
  {
    $response = $this->getJson('/api/user/');

    $response->assertStatus(401);
  }

  public function test_an_action_that_requires_authentication_ログインしていると認証エラーにならい(): void
  {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
      ->getJson('/api/user/');

    $response->assertStatus(200);
  }
}
