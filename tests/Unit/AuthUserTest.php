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
  public function testGetAuthUserWithNoToken(): void
  {
    $response = $this->getJson('/api/user/');

    $response->assertStatus(401);
  }

  public function testAnActionThatRequiresAuthentication(): void
  {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
      ->getJson('/api/user/');

    $response->assertStatus(200);
  }
}
