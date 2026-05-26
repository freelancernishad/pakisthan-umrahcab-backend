<?php

namespace Tests\Feature;

use App\Models\Notice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoticeApiTest extends TestCase
{
    // We cannot use RefreshDatabase because of migration issues in the project.
    // We will manually clean up created notices.

    public function test_can_create_global_notice()
    {
        $response = $this->postJson('/api/notices', [
            'title' => 'Test Global Notice',
            'content' => 'This is a test global notice content.',
            'type' => 'global',
            'is_active' => true,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'title' => 'Test Global Notice',
                    'type' => 'global',
                ]
            ]);

        $this->assertDatabaseHas('notices', [
            'title' => 'Test Global Notice',
            'type' => 'global',
        ]);

        // Cleanup
        Notice::where('title', 'Test Global Notice')->delete();
    }

    public function test_can_create_union_notice()
    {
        $response = $this->postJson('/api/notices', [
            'title' => 'Test Union Notice',
            'content' => 'This is a test union notice content.',
            'type' => 'union',
            'union_id' => 999, // Arbitrary ID
            'is_active' => true,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'title' => 'Test Union Notice',
                    'type' => 'union',
                    'union_id' => 999
                ]
            ]);

        // Cleanup
        Notice::where('title', 'Test Union Notice')->delete();
    }

    public function test_can_list_notices()
    {
         // Create a notice first
         $notice = Notice::create([
             'title' => 'List Test Notice',
             'content' => 'Content for list test',
             'type' => 'admin',
         ]);

        $response = $this->getJson('/api/notices');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'data' => [
                        '*' => ['id', 'title', 'content', 'type']
                    ]
                ]
            ]);
        
        // Cleanup
        $notice->delete();
    }
}
