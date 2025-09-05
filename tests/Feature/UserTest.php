<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @testdox Deve criar um novo usuário com sucesso.
     * Verifica se a API retorna status 201 e a estrutura JSON esperada após a criação de um usuário válido.
     * @return void
     */
    public function test_it_should_create_a_new_user_successfully(): void
    {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'cpf' => '12345678901',
            'phone' => '11987654321',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'cpf',
                ],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    /**
     * @testdox Deve falhar ao criar um usuário com dados inválidos.
     * Verifica se a API retorna status 422 e erros de validação para dados de usuário inválidos.
     * @return void
     */
    public function test_it_should_fail_to_create_a_user_with_invalid_data(): void
    {
        $response = $this->postJson('/api/users', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'cpf' => '12345678901234567890', // CPF muito longo para violar max:14
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'cpf']);
    }

    /**
     * @testdox Deve permitir que um usuário autenticado visualize seu próprio perfil.
     * Verifica se um usuário pode acessar seus próprios dados de perfil com status 200.
     * @return void
     */
    public function test_it_should_allow_an_authenticated_user_to_view_their_own_profile(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->getJson("/api/users/{$user->id}", ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $user->id, 'email' => $user->email]]);
    }

    /**
     * Test if a user cannot view another user's profile.
     */
    /**
     * @testdox Não deve permitir que um usuário comum visualize o perfil de outro usuário
     * @return void
     */
    public function test_it_should_not_allow_a_user_to_view_another_user_profile(): void
    {
        $user1 = User::factory()->create(['role' => 'user']); // Garante que user1 não é admin
        $user2 = User::factory()->create();
        $token1 = $user1->createToken('test_token')->plainTextToken;

        // Removido $this->actingAs($user1, 'sanctum'); pois o token já é enviado no header

        $response = $this->getJson("/api/users/{$user2->id}", ['Authorization' => 'Bearer ' . $token1]);

        $response->assertStatus(403);
    }

    /**
     * @testdox Deve permitir que um administrador visualize o perfil de qualquer usuário.
     * Verifica se um usuário com role 'admin' pode acessar o perfil de qualquer outro usuário com status 200.
     * @return void
     */
    public function test_it_should_allow_an_admin_to_view_any_user_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $adminToken = $admin->createToken('admin_token')->plainTextToken;

        $response = $this->getJson("/api/users/{$user->id}", ['Authorization' => 'Bearer ' . $adminToken]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $user->id, 'email' => $user->email]]);
    }

    /**
     * @testdox Deve permitir que um usuário atualize seu próprio perfil.
     * Verifica se um usuário pode atualizar seus próprios dados de perfil com status 200.
     * @return void
     */
    public function test_it_should_allow_a_user_to_update_their_own_profile(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $updatedData = [
            'name' => 'Updated Name',
            'phone' => '9988776655',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updatedData, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['name' => 'Updated Name', 'phone' => '9988776655']]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Updated Name']);
    }

    /**
     * @testdox Não deve permitir que um usuário atualize o perfil de outro usuário.
     * Verifica se um usuário não-admin não pode atualizar o perfil de outro usuário, resultando em 403.
     * @return void
     */
    public function test_it_should_not_allow_a_user_to_update_another_user_profile(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $token1 = $user1->createToken('test_token')->plainTextToken;

        $this->actingAs($user1, 'sanctum');

        $updatedData = [
            'name' => 'Attempted Update',
        ];

        $response = $this->putJson("/api/users/{$user2->id}", $updatedData, ['Authorization' => 'Bearer ' . $token1]);

        $response->assertStatus(403);
    }

    /**
     * Test if an admin can update any user's profile.
     */
    public function test_it_should_allow_an_admin_to_update_any_user_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $adminToken = $admin->createToken('admin_token')->plainTextToken;

        $updatedData = [
            'name' => 'Admin Updated Name',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updatedData, ['Authorization' => 'Bearer ' . $adminToken]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['name' => 'Admin Updated Name']]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Admin Updated Name']);
    }

    /**
     * Test if a user can delete their own profile.
     */
    public function test_it_should_allow_a_user_to_delete_their_own_profile(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->deleteJson("/api/users/{$user->id}", [], ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(204);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    /**
     * @testdox Não deve permitir que um usuário exclua o perfil de outro usuário.
     * Verifica se um usuário não-admin não pode excluir o perfil de outro usuário, resultando em 403.
     * @return void
     */
    public function test_it_should_not_allow_a_user_to_delete_another_user_profile(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $token1 = $user1->createToken('test_token')->plainTextToken;

        $this->actingAs($user1, 'sanctum');

        $response = $this->deleteJson(route('users.destroy', $user2), [], ['Authorization' => 'Bearer ' . $token1]);

        $response->assertStatus(403);
    }

    /**
     * @testdox Deve permitir que um administrador exclua o perfil de qualquer usuário.
     * Verifica se um usuário com role 'admin' pode excluir o perfil de qualquer outro usuário com status 204.
     * @return void
     */
    public function test_it_should_allow_an_admin_to_delete_any_user_profile(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $adminToken = $admin->createToken('admin_token')->plainTextToken;

        $response = $this->deleteJson("/api/users/{$user->id}", [], ['Authorization' => 'Bearer ' . $adminToken]);

        $response->assertStatus(204);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}
