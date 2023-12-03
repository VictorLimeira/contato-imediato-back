<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_should_register_new_users(): void
    {
        $data = ['email' => 'my_email@email.com'];
        $response = $this->postJson('api/register', $data);

        $response->assertStatus(201);
        $response->assertJsonStructure(['personal_code']);
        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'ulid_token' => $response->decodeResponseJson()['personal_code']
        ]);
    }
}
