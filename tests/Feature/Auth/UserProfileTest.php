<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_authenticated_user_can_get_profile_info(){
        //request with an authenticated user
        $response = $this->apiAs(User::find(1),'post','api/auth/me');

        //validate the response
        $response->assertStatus(200);
        $response->assertJsonStructure(['message', 'data' => ['user'], 'status', 'errors']);
    }

    #[Test]
    public function an_unauthenticated_user_can_get_profile_info(){
        //request with an unauthenticated user
        $response = $this->postJson('api/auth/me');

        //validate the response
        $response->assertStatus(401);
        $response->assertJsonFragment(['message' => 'Unauthenticated.']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create();
    }
}
