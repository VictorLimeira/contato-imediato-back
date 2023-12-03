<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Tests\TestCase;

class ContactTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_it_should_list_empty_contacts(): void
    {
        $response = $this
            ->withHeaders(['Authorization' => "Bearer {$this->user->ulid_token}"])
            ->getJson('api/contacts');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_it_should_list_contacts(): void
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        Contact::factory(5)->create(['user_id' => $this->user->id]);

        $response = $this
            ->withHeaders(['Authorization' => "Bearer {$this->user->ulid_token}"])
            ->getJson('api/contacts');

        $response->assertStatus(200);
        $this->assertCount(6, $response->decodeResponseJson());
        $response->assertJsonStructure([
            ['id', 'name', 'description', 'user_id', 'created_at', 'updated_at', 'deleted_at']
        ]);
        $response->assertJsonFragment([
            'id' => $contact->id,
            'name' => $contact->name,
            'description' => $contact->description,
            'user_id' => $this->user->id
        ]);
    }

    public function test_it_should_show_contacts(): void
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        Contact::factory(5)->create(['user_id' => $this->user->id]);

        $response = $this
            ->withHeaders(['Authorization' => "Bearer {$this->user->ulid_token}"])
            ->getJson("api/contacts/{$contact->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'description',
            'user_id',
            'created_at',
            'updated_at',
            'deleted_at'
        ]);
        $response->assertJsonFragment([
            'id' => $contact->id,
            'name' => $contact->name,
            'description' => $contact->description,
            'user_id' => $this->user->id
        ]);
    }

    public function test_it_should_create_contacts(): void
    {
        $data = ['name' => 'Amanda Dias', 'description' => 'Colega do trabalho do João.'];

        $response = $this
            ->withHeaders(['Authorization' => "Bearer {$this->user->ulid_token}"])
            ->postJson('api/contacts', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => $data['name'],
            'description' => $data['description'],
            'user_id' => $this->user->id
        ]);
        $this->assertDatabaseHas('contacts', [
            'name' => $data['name'],
            'description' => $data['description'],
            'user_id' => $this->user->id
        ]);
    }

    public function test_it_should_edit_contact(): void
    {
        $contact = Contact::factory()->create([
            'name' => 'Amanda Dias',
            'description' => 'Colega do trabalho do João.',
            'user_id' => $this->user->id
        ]);

        $dataModified = [
            'name' => 'Amanda Dias Lopes',
            'description' => 'Colega do trabalho do João e Maria.',
        ];
        $response = $this
            ->withHeaders(['Authorization' => "Bearer {$this->user->ulid_token}"])
            ->putJson("api/contacts/$contact->id", $dataModified);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $dataModified['name'],
            'description' => $dataModified['description'],
            'user_id' => $this->user->id
        ]);
        $this->assertDatabaseHas('contacts', [
            'name' => $dataModified['name'],
            'description' => $dataModified['description'],
            'user_id' => $this->user->id
        ]);
        $this->assertDatabaseMissing('contacts', [
            'name' => $contact->name,
            'description' => $contact->description,
            'user_id' => $this->user->id
        ]);
    }

    public function test_it_should_delete_contact(): void
    {
        $contact = Contact::factory()->create([
            'name' => 'Amanda Dias',
            'description' => 'Colega do trabalho do João.',
            'user_id' => $this->user->id
        ]);

        $response = $this
            ->withHeaders(['Authorization' => "Bearer {$this->user->ulid_token}"])
            ->deleteJson("api/contacts/$contact->id");

        $response->assertStatus(200);
        $this->assertSoftDeleted($contact);
    }
}
