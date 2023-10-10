<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class UpdateEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function we_can_update_a_single_event_title(): void
    {
        $event = Event::factory()->create(['title' => 'Can be updated']);

        $update = ['title' => 'It is updated'];

        $response = $this->putJson('/api/events/update/'.$event->id, $update)
            ->assertStatus(201);
        $this->assertDatabaseHas('events', $update);
    }

    /**
     * @test
     */
    public function we_can_update_a_single_event_description(): void
    {
        $event = Event::factory()->create(['title' => 'Will not be updated']);
        $update = ['description' => 'It is an updated description'];

        $response = $this->putJson('/api/events/update/'.$event->id, $update)
            ->assertStatus(201);
        $this->assertDatabaseHas('events', $update);
    }
}
