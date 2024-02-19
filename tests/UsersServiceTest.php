<?php

use PHPUnit\Framework\TestCase;
use services\UsersService;
use models\User;

/**
 * Users Service Test
 */
class UsersServiceTest extends TestCase
{
    /**
     * Test Authentication with valid credentials
     */
    public function testAuthenticateValidCredentials()
    {
        $pdoMock = $this->createMock(PDO::class);

        $userMock = new User(
            1,
            'test_user',
            password_hash('test_password', PASSWORD_DEFAULT),
            'Test',
            'User',
            'test@example.com',
            '2024-02-17 12:00:00',
            '2024-02-17 12:00:00',
            false,
            ['ROLE_USER']
        );

        $usersService = $this->getMockBuilder(UsersService::class)
            ->setConstructorArgs([$pdoMock])
            ->onlyMethods(['findUserByUsername'])
            ->getMock();

        $usersService->expects($this->once())
            ->method('findUserByUsername')
            ->with('test_user')
            ->willReturn($userMock);

        $authenticatedUser = $usersService->authenticate('test_user', 'test_password');
        $this->assertInstanceOf(User::class, $authenticatedUser);
        $this->assertEquals('Test', $authenticatedUser->name);
    }

    /**
     * Test Authentication with invalid credentials
     */
    public function testAuthenticateInvalidCredentials()
    {
        $pdoMock = $this->createMock(PDO::class);

        $usersService = $this->getMockBuilder(UsersService::class)
            ->setConstructorArgs([$pdoMock])
            ->onlyMethods(['findUserByUsername'])
            ->getMock();

        $usersService->expects($this->once())
            ->method('findUserByUsername')
            ->with('non_existing_user')
            ->willReturn(null);

        $this->expectException(Exception::class);
        $usersService->authenticate('non_existing_user', 'invalid_password');
    }

    /**
     * Test Create User
     */
    public function testCreateUser()
    {
        $pdoMock = $this->createMock(PDO::class);

        $userMock = new User(
            1,
            'test_user',
            password_hash('test_password', PASSWORD_DEFAULT),
            'Test',
            'User',
            'test@example.com',
            '2024-02-17 12:00:00',
            '2024-02-17 12:00:00',
            false,
            ['ROLE_USER']
        );

        $usersService = $this->getMockBuilder(UsersService::class)
            ->setConstructorArgs([$pdoMock])
            ->onlyMethods(['createUser'])
            ->getMock();

        $usersService->expects($this->once())
            ->method('createUser')
            ->with('test_user', 'test_password', 'test_password', 'Test', 'User', 'test@example.com')
            ->willReturn($userMock);

        $createdUser = $usersService->createUser('test_user', 'test_password', 'test_password', 'Test', 'User', 'test@example.com');
        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertEquals('Test', $createdUser->name);
    }

    /**
     * Test FindUserByUsername
     */
    public function testFindUserByUsername()
    {
        $pdoMock = $this->createMock(PDO::class);

        $userMock = new User(
            1,
            'test_user',
            password_hash('test_password', PASSWORD_DEFAULT),
            'Test',
            'User',
            'test@example.com',
            '2024-02-17 12:00:00',
            '2024-02-17 12:00:00',
            false,
            ['ROLE_USER']
        );

        $usersService = $this->getMockBuilder(UsersService::class)
            ->setConstructorArgs([$pdoMock])
            ->onlyMethods(['findUserByUsername'])
            ->getMock();

        $usersService->expects($this->once())
            ->method('findUserByUsername')
            ->with('test_user')
            ->willReturn($userMock);

        $foundUser = $usersService->findUserByUsername('test_user');
        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals('Test', $foundUser->name);
    }

}
