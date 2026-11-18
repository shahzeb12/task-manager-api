<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_user_can_login_and_retrieve_token(): void
    {

        // create user 
        $user = User::factory()->create();

        $response = $this->post('/api/auth/login', [
            "email" => $user->email,
            "password" => "password"
        ]);

         $response->assertOk();

        $response->assertJsonStructure(['token', 'user']);

       
    }

    public function test_user_cannot_login_with_incorrect_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-passwrod',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_register_and_receive_token(): void
    {

        $email = 'test@gmail.com';
        $response = $this->postJson('/api/auth/register', [
            'email' => $email,
            'password' => 'some-passwrod',
            'name' => 'test',
            'password_confirmation' => 'some-passwrod'
        ]);

        $response->assertCreated();

        $response->assertJsonStructure(['token', 'user']);

        $this->assertDatabaseHas('users',[
            'email' => $email
        ]);

    }

    public function test_user_cannot_register_with_invalid_data(): void
    {

        $email = 'wrongemaisl@gmail.com';

        $response = $this->postJson('/api/auth/register', [
            'email' => $email,
            'password' => '7767',
            'name' => '',
            'password_confirmation' => 'some-passwrod'
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['name', 'password']);

        

    }


    public function test_user_can_logout_and_token_is_revoked(): void
    {

        $user = User::factory()->create();

        $token  = $user->createToken('laravel_api_token')->plainTextToken;

        // logged in user is logged out
        $response = $this->withHeader('Authorization','Bearer '. $token)->postJson('/api/auth/logout');

        $response->assertNoContent();

        // forget guards after logout

        $this->app['auth']->forgetGuards();

        // try to get the same user by token, should be failed

        $protected = $this->withHeader('Authorization','Bearer '. $token)->getJson('/api/user');

        $protected->assertStatus(401);


    }

    public function test_authenticated_user_can_access_user_endpoint(): void
    {

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson('/api/user');

        $response->assertStatus(200);

        $response->assertJson([
            'id' => $user->id,
            'email' => $user->email,
        ]);

    }
}
