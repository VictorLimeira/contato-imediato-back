<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Medium;
use App\Models\User;
use Tests\TestCase;

class MediumTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_it_should_list_empty_media_from_contacts(): void
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);

        $response = $this
            ->withHeaders(['Authorization' => "Bearer {$this->user->ulid_token}"])
            ->getJson("api/contacts/{$contact->id}/media");

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_it_should_list_media(): void
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        $media = Medium::factory()->create(['contact_id' => $contact->id]);

        $response = $this
            ->withHeaders(['Authorization' => "Bearer {$this->user->ulid_token}"])
            ->getJson("api/contacts/{$contact->id}/media");

        $response->assertStatus(200);
        $response->assertJsonStructure([[
            "id",
            "category",
            "value",
            "contact_id",
            "created_at",
            "updated_at",
            "deleted_at",
        ]]);
        $response->assertJsonFragment([
            'id' => $media->id,
            'value' => $media->value,
            'contact_id' => $media->contact_id
        ]);
    }

    public function test_it_should_show_media(): void
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        $media = Medium::factory()->create(['contact_id' => $contact->id]);

        $response = $this
            ->withHeaders(['Authorization' => "Bearer {$this->user->ulid_token}"])
            ->getJson("api/contacts/{$contact->id}/media/{$media->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "id",
            "category",
            "value",
            "contact_id",
            "created_at",
            "updated_at",
            "deleted_at",
            "contact" => [
                "id",
                "name",
                "description",
                "user_id",
                "created_at",
                "updated_at",
                "deleted_at"
            ]
        ]);
        $response->assertJsonFragment([
            'id' => $media->id,
            'value' => $media->value,
            'contact_id' => $media->contact_id
        ]);
        $response->assertJsonFragment([
            'name' => $contact->name,
            'description' => $contact->description,
            'user_id' => $this->user->id
        ]);
    }

    public function test_it_should_create_media(): void
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);

        $data = [
            'category' => Medium::CATEGORIES[0],
            'value' => '123456789'
        ];
        $response = $this
            ->withHeaders(['Authorization' => "Bearer {$this->user->ulid_token}"])
            ->postJson("api/contacts/{$contact->id}/media", $data);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'category' => $data['category'],
            'value' => $data['value'],
            'contact_id' => $contact->id
        ]);
        $this->assertDatabaseHas('media', [
            'category' => $data['category'],
            'value' => $data['value'],
            'contact_id' => $contact->id
        ]);
    }

    public function test_it_should_edit_media(): void
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        $media = Medium::factory()->create(['contact_id' => $contact->id]);

        $dataModified = [
            'category' => Medium::CATEGORIES[0],
            'value' => '@amanda #45678561234235456',
        ];
        $response = $this
            ->withHeaders(['Authorization' => "Bearer {$this->user->ulid_token}"])
            ->putJson("api/contacts/{$contact->id}/media/{$media->id}", $dataModified);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'category' => $dataModified['category'],
            'value' => $dataModified['value'],
            'contact_id' => $contact->id
        ]);
        $this->assertDatabaseHas('media', [
            'category' => $dataModified['category'],
            'value' => $dataModified['value'],
            'contact_id' => $contact->id
        ]);
        $this->assertDatabaseMissing('media', [
            'category' => $media->category,
            'value' => $media->value,
            'contact_id' => $contact->id
        ]);
    }

    public function test_it_should_delete_contact(): void
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        $media = Medium::factory()->create(['contact_id' => $contact->id]);

        $response = $this
            ->withHeaders(['Authorization' => "Bearer {$this->user->ulid_token}"])
            ->deleteJson("api/contacts/{$contact->id}/media/{$media->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted($media);
    }
}
