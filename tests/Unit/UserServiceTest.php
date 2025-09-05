<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    protected $userRepositoryMock;
    protected UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepositoryMock = $this->createMock(UserRepositoryInterface::class);
        $this->userService = new UserService($this->userRepositoryMock);
    }

    /**
     * @testdox Deve retornar todos os usuários
     * @return void
     */
    public function it_should_return_all_users(): void
    {
        $expectedUsers = new Collection([new User(), new User()]);
        $this->userRepositoryMock->expects($this->once())
            ->method('getAll')
            ->willReturn($expectedUsers);

        $users = $this->userService->getAllUsers();

        $this->assertEquals($expectedUsers, $users);
    }

    /**
     * @testdox Deve encontrar um usuário pelo ID
     * @return void
     */
    public function it_should_find_a_user_by_id(): void
    {
        $userId = 1;
        $expectedUser = new User();
        $this->userRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($expectedUser);

        $user = $this->userService->findUserById($userId);

        $this->assertEquals($expectedUser, $user);
    }

    /**
     * @testdox Deve criar um novo usuário
     * @return void
     */
    public function it_should_create_a_new_user(): void
    {
        $userData = ['name' => 'Test User', 'email' => 'test@example.com', 'password' => 'password'];
        $expectedUser = new User($userData);
        $this->userRepositoryMock->expects($this->once())
            ->method('create')
            ->with($userData)
            ->willReturn($expectedUser);

        $user = $this->userService->createUser($userData);

        $this->assertEquals($expectedUser, $user);
    }

    /**
     * @testdox Deve atualizar um usuário existente
     * @return void
     */
    public function it_should_update_an_existing_user(): void
    {
        $userId = 1;
        $updatedData = ['name' => 'Updated Name'];
        $expectedUser = new User(['id' => $userId, 'name' => 'Updated Name']);
        $this->userRepositoryMock->expects($this->once())
            ->method('update')
            ->with($userId, $updatedData)
            ->willReturn($expectedUser);

        $user = $this->userService->updateUser($userId, $updatedData);

        $this->assertEquals($expectedUser, $user);
    }

    /**
     * @testdox Deve excluir um usuário
     * @return void
     */
    public function it_should_delete_a_user(): void
    {
        $userId = 1;
        $this->userRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($userId)
            ->willReturn(true);

        $result = $this->userService->deleteUser($userId);

        $this->assertTrue($result);
    }
}
