<?php

namespace Tests\Feature;

use App\Http\Controllers\EventController;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function retrieve_single_event(): void
    {
        $event = Event::factory([
            'title' => 'Show single event',
            'start' => now()->addHour()->format('c'),
            'end' => now()->addHours(2)->format('c'),
            'frequency' => 'daily',
            'repeat_until' => now()->addYear()->format('c'),
        ])->create();

        $eventToShow = Event::first();

        $response = $this->get('/api/events/show/'.$eventToShow->id)
            ->assertStatus(200);
        $this->assertJsonStringEqualsJsonString($eventToShow->toJson(), $response->content());
    }

    /**
     * test
     */
    public function list_events_with_pagination(): void
    {
        $event = Event::factory([
            'title' => 'Show single event',
            'start' => now()->addHour()->format('c'),
            'end' => now()->addHours(2)->format('c'),
            'frequency' => 'daily',
            'repeat_until' => now()->addYear()->format('c'),
        ])->create();

        $from = now()->format('c');
        $to = now()->addMonths(3)->format('c');

        $url = action([EventController::class, 'list'], ['from' => now()->format('Y-m-d'), 'to' => now()->addMonths(2)->format('Y-m-d')]);

        $response = $this->get($url)
            ->assertStatus(200);
        print_r($response->content());
    }
}
