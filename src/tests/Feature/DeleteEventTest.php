<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Event;

class DeleteEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function delete_single_event(): void
    {
        $event = [
            'title' => 'Deletable Event Title',
            'start' => now()->addDay()->format('c'),
            'end' => now()->addDay()->addMinutes(90)->format('c'),
        ];

        $response = $this->postJson('/api/events/create', $event)
            ->assertStatus(201);

        $event_id = $response['id'];
        $response = $this->postJson('/api/events/delete/'.$event_id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('events', $event);
    }

    /**
     * @test
     */
    public function delete_single_event_from_recurrence_series(): void
    {
        $event = Event::factory([
            'title' => 'Delete from event series',
            'start' => now()->addYears(2)->format('c'),
            'end' => now()->addYears(2)->addDay()->addHour()->format('c'),
            'frequency' => 'monthly',
            'repeat_until' => now()->addYears(3)->format('c')
        ])->make();

        $response = $this->postJson('/api/events/create', $event->toArray())
            ->assertStatus(201);

        $eventToDelete = Event::whereNotNull('event_id')->first();

        $this->postJson('/api/events/delete/' . $eventToDelete->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('events', ['id' => $eventToDelete->id]);
    }
}
