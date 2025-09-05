<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @testdox Deve listar todos os endereços de um usuário específico.
     * Verifica se a API retorna corretamente todos os endereços associados a um usuário.
     * Garante que apenas os endereços do usuário alvo são listados.
     * @return void
     */
    public function it_should_list_all_addresses_for_a_specific_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;
        Address::factory()->count(3)->create(['user_id' => $user->id]);
        Address::factory()->count(2)->create(); // Endereços de outros usuários

        $response = $this->getJson("/api/users/{$user->id}/addresses", ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    #[TestDox('Deve criar um novo endereço para um usuário.')]
    // Verifica se a API permite a criação de um novo endereço para um usuário existente.
    // Garante que o endereço é persistido no banco de dados e a resposta é 201 Created.
    public function it_should_create_a_new_address_for_a_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $addressData = [
            'user_id' => $user->id,
            'street' => 'Rua Teste',
            'number' => '123',
            'neighborhood' => 'Bairro Teste',
            'complement' => 'Apto 101',
            'zip_code' => '12345-678',
        ];

        $response = $this->postJson("/api/users/{$user->id}/addresses", $addressData, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(201)
            ->assertJson(['data' => ['street' => 'Rua Teste', 'user_id' => $user->id]]);

        $this->assertDatabaseHas('addresses', ['street' => 'Rua Teste', 'user_id' => $user->id]);
    }

    #[TestDox('Não deve criar um endereço com dados inválidos.')]
    // Verifica se a API rejeita a criação de endereços com dados incompletos ou inválidos,
    // retornando status 422 Unprocessable Entity e erros de validação.
    public function it_should_not_create_an_address_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $addressData = [
            'user_id' => 9999, // Usuário inexistente
            'street' => '',
            'zip_code' => 'invalid-cep',
        ];

        $response = $this->postJson("/api/users/{$user->id}/addresses", $addressData, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id', 'street', 'zip_code']);
    }

    #[TestDox('Deve exibir um endereço específico de um usuário.')]
    // Verifica se a API retorna os detalhes de um endereço específico pertencente ao usuário.
    public function it_should_show_a_specific_address_for_a_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;
        $address = Address::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/users/{$user->id}/addresses/{$address->id}", ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $address->id, 'street' => $address->street]]);
    }

    #[TestDox('Não deve exibir um endereço que não pertence ao usuário.')]
    // Verifica se um usuário não pode visualizar um endereço que não está associado a ele,
    // mesmo que o endereço exista, resultando em 404 Not Found (ou 403 Forbidden, dependendo da política).
    public function it_should_not_show_an_address_that_does_not_belong_to_the_user(): void
    {
        $user1 = User::factory()->create();
        $token1 = $user1->createToken('test_token')->plainTextToken;
        $user2 = User::factory()->create();
        $address2 = Address::factory()->create(['user_id' => $user2->id]);

        $response = $this->getJson("/api/users/{$user1->id}/addresses/{$address2->id}", ['Authorization' => 'Bearer ' . $token1]);

        $response->assertStatus(404); // Ou 403, dependendo da política exata
    }

    #[TestDox('Deve atualizar um endereço existente.')]
    // Verifica se a API permite a atualização de um endereço existente de um usuário.
    // Garante que os dados são atualizados no banco de dados e a resposta é 200 OK.
    public function it_should_update_an_existing_address(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;
        $address = Address::factory()->create(['user_id' => $user->id]);

        $updatedData = [
            'street' => 'Rua Atualizada',
            'number' => '456',
        ];

        $response = $this->putJson("/api/users/{$user->id}/addresses/{$address->id}", $updatedData, ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['street' => 'Rua Atualizada', 'number' => '456']]);

        $this->assertDatabaseHas('addresses', ['id' => $address->id, 'street' => 'Rua Atualizada']);
    }

    #[TestDox('Não deve atualizar um endereço que não pertence ao usuário.')]
    // Verifica se um usuário não pode atualizar um endereço que não está associado a ele,
    // resultando em 404 Not Found (ou 403 Forbidden, dependendo da política).
    public function it_should_not_update_an_address_that_does_not_belong_to_the_user(): void
    {
        $user1 = User::factory()->create();
        $token1 = $user1->createToken('test_token')->plainTextToken;
        $user2 = User::factory()->create();
        $address2 = Address::factory()->create(['user_id' => $user2->id]);

        $updatedData = [
            'street' => 'Rua Tentativa',
        ];

        $response = $this->putJson("/api/users/{$user1->id}/addresses/{$address2->id}", $updatedData, ['Authorization' => 'Bearer ' . $token1]);

        $response->assertStatus(404); // Ou 403, dependendo da política exata
    }

    #[TestDox('Deve excluir um endereço existente.')]
    // Verifica se a API permite a exclusão de um endereço existente de um usuário.
    // Garante que o endereço é removido do banco de dados e a resposta é 204 No Content.
    public function it_should_delete_an_existing_address(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;
        $address = Address::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/users/{$user->id}/addresses/{$address->id}", [], ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    #[TestDox('Não deve excluir um endereço que não pertence ao usuário.')]
    // Verifica se um usuário não pode excluir um endereço que não está associado a ele,
    // resultando em 404 Not Found (ou 403 Forbidden, dependendo da política).
    public function it_should_not_delete_an_address_that_does_not_belong_to_the_user(): void
    {
        $user1 = User::factory()->create();
        $token1 = $user1->createToken('test_token')->plainTextToken;
        $user2 = User::factory()->create();
        $address2 = Address::factory()->create(['user_id' => $user2->id]);

        $response = $this->deleteJson("/api/users/{$user1->id}/addresses/{$address2->id}", [], ['Authorization' => 'Bearer ' . $token1]);

        $response->assertStatus(404); // Ou 403, dependendo da política exata
    }
}
