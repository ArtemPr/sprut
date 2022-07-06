<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserEntityTest extends TestCase
{
    public function testPhone(): void
    {
        $test_string = '123456';
        $user = new User();
        $user->setPhone($test_string);
        $this->assertStringContainsString($user->getPhone(), $test_string);
    }

    public function testEmail(): void
    {
        $test_string = 'qwerty@test.ru';
        $user = new User();
        $user->setEmail($test_string);
        $this->assertStringContainsString($user->getEmail(), $test_string);
    }

    public function testUsername(): void
    {
        $test_string = "user";
        $user = new User();
        $user->setUsername($test_string);
        $this->assertStringContainsString($user->getUsername(), $test_string);
    }
}
