<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @testdox Deve permitir que um usuário faça login com credenciais válidas.
     * Verifica se o login retorna um token e os dados do usuário com status 200.
     * @return void
     */
    public function test_it_should_login_a_user_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);
    }

    /**
     * @testdox Deve falhar ao tentar fazer login com credenciais inválidas.
     * Verifica se o sistema retorna um erro de validação (422) para credenciais incorretas.
     * @return void
     */
    public function test_it_should_fail_to_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * @testdox Deve permitir que um usuário autenticado faça logout.
     * Verifica se o logout invalida o token do usuário e retorna status 200.
     * @return void
     */
    public function test_it_should_logout_an_authenticated_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->postJson('/api/logout', [], ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logout successful']);

        // Verify token is revoked
        $this->assertCount(0, $user->tokens);
    }
}
