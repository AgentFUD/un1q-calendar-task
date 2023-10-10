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

    /**
     * @test
     */
    public function frequency_can_not_be_changed(): void
    {
        $event = Event::factory()->create(['title' => 'Frequency will not be updated', 'frequency' => 'daily']);
        $update = ['frequency' => 'monthly'];

        $response = $this->putJson('/api/events/update/'.$event->id, $update)
            ->assertStatus(201);
        $this->assertDatabaseMissing('events', $update);
    }

    /**
     * @test
     */
    public function repeat_until_can_not_be_changed(): void
    {
        $now = now()->addMonths(2)->format('c');
        $updatedNow = now()->addMonths(3)->format('c');
        $event = Event::factory()->create(['title' => 'Frequency will not be updated', 'repeat_until' => $now]);
        $update = ['repeat_until' => $updatedNow];

        $response = $this->putJson('/api/events/update/'.$event->id, $update)
            ->assertStatus(201);
        $this->assertDatabaseMissing('events', $update);
    }

    /**
     * @test
     */
    public function start_can_be_changed(): void
    {
        $start = now()->addMonths(20)->format('c');
        $end = now()->addMonths(20)->addHour()->format('c');
        $repeatUntil = now()->addMonths(30)->format('c');

        $updatedStart = now()->addMonths(20)->addMinutes(10)->format('c');
        
        $event = Event::factory()->create([
            'title' => 'Start will be updated',
            'start' => $start,
            'end' => $end,
            'repeat_until' => $repeatUntil,
            'frequency' => 'weekly',
        ]);

        $update = ['start' => $updatedStart];

        $response = $this->putJson('/api/events/update/'.$event->id, $update)
            ->assertStatus(201);
        $this->assertDatabaseHas('events', $update);
    }

    /**
     * @test
     */
    public function end_can_be_changed(): void
    {
        $start = now()->addMonths(20)->format('c');
        $end = now()->addMonths(20)->addHour()->format('c');
        $repeatUntil = now()->addMonths(30)->format('c');

        $updatedEnd = now()->addMonths(20)->addHour()->subMinute()->format('c');
        
        $event = Event::factory()->create([
            'title' => 'Start will be updated',
            'start' => $start,
            'end' => $end,
            'repeat_until' => $repeatUntil,
            'frequency' => 'weekly',
        ]);

        $update = ['end' => $updatedEnd];

        $response = $this->putJson('/api/events/update/'.$event->id, $update)
            ->assertStatus(201);
        $this->assertDatabaseHas('events', $update);
    }
}
