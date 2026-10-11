<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserPasswordHashingTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_plain_password_is_hashed_before_it_is_stored(): void
    {
        $user = User::create([
            'name' => 'Ada',
            'email' => 'ada@example.com',
            'password' => 'correct horse battery staple',
        ]);

        $stored = User::query()->whereKey($user->id)->value('password');

        $this->assertNotSame('correct horse battery staple', $stored);
        $this->assertTrue(Hash::check('correct horse battery staple', $stored));
    }

    public function test_updating_the_password_hashes_it_too(): void
    {
        $user = User::factory()->create();

        $user->update(['password' => 'a new secret']);

        $stored = User::query()->whereKey($user->id)->value('password');

        $this->assertNotSame('a new secret', $stored);
        $this->assertTrue(Hash::check('a new secret', $stored));
    }

    public function test_factory_users_log_in_with_the_documented_password(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(Hash::check('password', $user->password));
    }
}
