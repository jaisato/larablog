<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_plain_password_is_hashed_when_assigned(): void
    {
        $user = User::create([
            'name' => 'Ada',
            'email' => 'ada@example.com',
            'password' => 'correct horse battery staple',
        ]);

        $stored = $user->fresh()->getAttributes()['password'];

        $this->assertNotSame('correct horse battery staple', $stored);
        $this->assertTrue(Hash::check('correct horse battery staple', $stored));
    }

    public function test_an_existing_hash_is_not_hashed_again(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
