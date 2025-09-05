<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @testdox Deve listar todos os endereços de um usuário específico
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

    /**
     * @testdox Deve criar um novo endereço para um usuário
     * @return void
     */
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

    /**
     * @testdox Não deve criar um endereço com dados inválidos
     * @return void
     */
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

    /**
     * @testdox Deve exibir um endereço específico de um usuário
     * @return void
     */
    public function it_should_show_a_specific_address_for_a_user(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;
        $address = Address::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/users/{$user->id}/addresses/{$address->id}", ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(200)
            ->assertJson(['data' => ['id' => $address->id, 'street' => $address->street]]);
    }

    /**
     * @testdox Não deve exibir um endereço que não pertence ao usuário
     * @return void
     */
    public function it_should_not_show_an_address_that_does_not_belong_to_the_user(): void
    {
        $user1 = User::factory()->create();
        $token1 = $user1->createToken('test_token')->plainTextToken;
        $user2 = User::factory()->create();
        $address2 = Address::factory()->create(['user_id' => $user2->id]);

        $response = $this->getJson("/api/users/{$user1->id}/addresses/{$address2->id}", ['Authorization' => 'Bearer ' . $token1]);

        $response->assertStatus(404); // Ou 403, dependendo da política exata
    }

    /**
     * @testdox Deve atualizar um endereço existente
     * @return void
     */
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

    /**
     * @testdox Não deve atualizar um endereço que não pertence ao usuário
     * @return void
     */
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

    /**
     * @testdox Deve excluir um endereço existente
     * @return void
     */
    public function it_should_delete_an_existing_address(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;
        $address = Address::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/users/{$user->id}/addresses/{$address->id}", [], ['Authorization' => 'Bearer ' . $token]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    /**
     * @testdox Não deve excluir um endereço que não pertence ao usuário
     * @return void
     */
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
