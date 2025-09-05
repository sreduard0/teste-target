<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Repositories\Contracts\AddressRepositoryInterface;
use App\Services\AddressService;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class AddressServiceTest extends TestCase
{
    protected $addressRepositoryMock;
    protected AddressService $addressService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->addressRepositoryMock = $this->createMock(AddressRepositoryInterface::class);
        $this->addressService = new AddressService($this->addressRepositoryMock);
    }

    /**
     * @testdox Deve retornar todos os endereços para um usuário específico
     * @return void
     */
    public function it_should_return_all_addresses_for_a_specific_user(): void
    {
        $userId = 1;
        $expectedAddresses = new Collection([new Address(), new Address()]);
        $this->addressRepositoryMock->expects($this->once())
            ->method('getByUser')
            ->with($userId)
            ->willReturn($expectedAddresses);

        $addresses = $this->addressService->getAddressesByUser($userId);

        $this->assertEquals($expectedAddresses, $addresses);
    }

    /**
     * @testdox Deve encontrar um endereço pelo ID
     * @return void
     */
    public function it_should_find_an_address_by_id(): void
    {
        $addressId = 1;
        $expectedAddress = new Address();
        $this->addressRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($addressId)
            ->willReturn($expectedAddress);

        $address = $this->addressService->findAddressById($addressId);

        $this->assertEquals($expectedAddress, $address);
    }

    /**
     * @testdox Deve criar um novo endereço
     * @return void
     */
    public function it_should_create_a_new_address(): void
    {
        $addressData = ['user_id' => 1, 'street' => 'Test Street'];
        $expectedAddress = new Address($addressData);
        $this->addressRepositoryMock->expects($this->once())
            ->method('create')
            ->with($addressData)
            ->willReturn($expectedAddress);

        $address = $this->addressService->createAddress($addressData);

        $this->assertEquals($expectedAddress, $address);
    }

    /**
     * @testdox Deve atualizar um endereço existente
     * @return void
     */
    public function it_should_update_an_existing_address(): void
    {
        $addressId = 1;
        $updatedData = ['street' => 'Updated Street'];
        $expectedAddress = new Address(['id' => $addressId, 'street' => 'Updated Street']);
        $this->addressRepositoryMock->expects($this->once())
            ->method('update')
            ->with($addressId, $updatedData)
            ->willReturn($expectedAddress);

        $address = $this->addressService->updateAddress($addressId, $updatedData);

        $this->assertEquals($expectedAddress, $address);
    }

    /**
     * @testdox Deve excluir um endereço
     * @return void
     */
    public function it_should_delete_an_address(): void
    {
        $addressId = 1;
        $this->addressRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($addressId)
            ->willReturn(true);

        $result = $this->addressService->deleteAddress($addressId);

        $this->assertTrue($result);
    }
}
