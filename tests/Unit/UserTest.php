<?php

use App\DTOs\UserDTOIN;
use App\Models\User;
use App\Repositories\Eloquent\UserRepository;
use App\Services\UserService;
use Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;

class UserTest extends TestCase {

    use DatabaseMigrations;

    private User $user;
    private UserService $userService;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->user->name = "edumiol";
        $this->user->email = "emiranda.dev@gmail.com";
        $this->user->password = '123456';
        $this->user->save();

        $this->userRepository = new UserRepository($this->user);
        $this->userService = new UserService($this->userRepository);

    }
    public function test_should_find_user()
    {
        $user = $this->userService->getUser(new UserDTOIN("edumiol", "emiranda.dev@gmail.com", "123456"));

        $this->assertInstanceOf(User::class, $user[0]);
        $this->assertNotEmpty($user);
        $this->assertEquals($user[0]->name, 'edumiol');
        $this->assertEquals($user[0]->email, 'emiranda.dev@gmail.com');
    }

    public function test_should_failed_find_user_invalid()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('User invalid.');

        $user = $this->userService->getUser(new UserDTOIN("beltrano", "beltrano@gmail.com", "testa123"));

        $this->assertInstanceOf(User::class, $user[0]);
        $this->assertNotEmpty($user);
        $this->assertEquals($user[0]->name, 'beltrano');
        $this->assertEquals($user[0]->email, 'beltrano@gmail.com');

    }
}
