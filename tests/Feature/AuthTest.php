<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    const URI = 'api/auth';

    /**
     *
     * @return void
     */
    public function test_login()
    {
        $email = config('api.apiEmail');
        $password = config('api.apiPassword');

        $response = $this->json('POST', self::URI . '/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                                      'access_token',
                                      'token_type',
                                      'expires_in',
                                  ])
        ;
    }

    /**
     *
     * @return void
     */
    public function test_logout()
    {
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->json('POST', self::URI . "/logout?token=$token");

        $response
            ->assertStatus(200)
            ->assertExactJson([
                                  'message' => 'User successfully signed out',
                              ])
        ;
    }

    /**
     *
     * @return void
     */
    public function test_refresh()
    {
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->json('POST', self::URI . "/refresh?token=$token");

        //  dd($response);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                                      'access_token',
                                      'token_type',
                                      'expires_in',
                                  ])
        ;
    }

    /**
     *
     * @return void
     */
    public function test_register()
    {
        $user['name'] = 'Test User';
        $user['email'] = Str::random(5) . '@mail.com';
        $user['password'] = 'password';
        $user['password_confirmation'] = 'password';

        $response = $this->json('POST', self::URI . '/register', $user);

        $this->assertDatabaseHas('users', [
            'id' => $response['user_id'],
            'name' => $response['name'],
            'email' => $response['email'],
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                                      'access_token',
                                      'token_type',
                                      'expires_in',
                                  ])
        ;
    }
}
