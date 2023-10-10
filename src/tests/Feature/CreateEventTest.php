<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function we_can_create_a_single_event(): void
    {
        $event = [
            'title' => 'My Event Title',
            'start' => now()->addDay()->format('c'),
            'end' => now()->addDay()->addMinutes(90)->format('c'),
        ];

        $this->postJson('/api/events/create', $event)
            ->assertStatus(201);
        $this->assertDatabaseHas('events', $event);
    }

    /**
     * @test
     */
    public function on_create_title_is_required(): void
    {
        $event = Event::factory([
            'title' => null,
            'start' => now()->addDay()->format('c'),
            'end' => now()->addDay()->addHour()->format('c'),
        ])->make();

        $this->postJson('/api/events/create', $event->toArray())
            ->assertStatus(422);
        $this->assertDatabaseMissing('events', $event->toArray());
    }

    /**
     * @test
     */
    public function on_create_description_is_optional(): void
    {
        $event = [
            'title' => 'My title yeah',
            'start' => now()->addDay()->format('c'),
            'end' => now()->addDay()->addHour()->format('c'),
            'frequency' => 'monthly',
            'repeat_until' => now()->addYear()->format('c'),
        ];

        $this->postJson('/api/events/create', $event)
            ->assertStatus(201);
    }

    /**
     * @test
     *
     * @dataProvider invalidDateProvider
     */
    public function only_valid_iso_8601_dates_are_accepted($event): void
    {
        $response = $this->postJson('/api/events/create', $event)
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function we_can_create_recurring_events(): void
    {
        $eventCount = Event::count();

        $event = [
            'title' => 'Recurring event 30 times',
            'start' => now()->addMinute()->format('c'),
            'end' => now()->addMinutes(60)->format('c'),
            'frequency' => 'daily',
            'repeat_until' => now()->addDays(29)->format('c'),
        ];

        $response = $this->postJson('/api/events/create', $event)
            ->assertStatus(201);

        $this->assertEquals(Event::count() - $eventCount, 30);
    }

    /**
     * @test
     */
    public function on_create_start_is_required(): void
    {
        $event = [
            'title' => 'Recurring event 30 times',
            'end' => now()->addMinutes(60)->format('c'),
            'frequency' => 'daily',
            'repeat_until' => now()->addDays(29)->format('c'),
        ];

        $response = $this->postJson('/api/events/create', $event)
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function on_create_end_is_required(): void
    {
        $event = [
            'title' => 'Recurring event 30 times',
            'start' => now()->addMinutes(60)->format('c'),
            'frequency' => 'daily',
            'repeat_until' => now()->addDays(29)->format('c'),
        ];

        $response = $this->postJson('/api/events/create', $event)
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function on_create_wrong_frequency_is_not_accepted(): void
    {
        $event = [
            'title' => 'Recurring event 30 times',
            'start' => now()->addMinute()->format('c'),
            'end' => now()->addMinutes(60)->format('c'),
            'frequency' => 'forthight',
            'repeat_until' => now()->addDays(29)->format('c'),
        ];

        $response = $this->postJson('/api/events/create', $event)
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function on_create_repeat_until_has_to_be_iso_8601(): void
    {
        $event = [
            'title' => 'Recurring event 30 times',
            'start' => now()->addMinute()->format('c'),
            'end' => now()->addMinutes(60)->format('c'),
            'frequency' => 'daily',
            'repeat_until' => now()->addDays(29),
        ];

        $response = $this->postJson('/api/events/create', $event)
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function overlapping_start_will_fail(): void
    {
        $event = [
            'title' => 'Recurring event 10 times',
            'start' => now()->addMinute()->format('c'),
            'end' => now()->addMinutes(61)->format('c'),
            'frequency' => 'daily',
            'repeat_until' => now()->addDays(9)->format('c'),
        ];

        $response = $this->postJson('/api/events/create', $event)
            ->assertStatus(201);

        $overlapping_event_start = [
            'title' => 'Overlapping event',
            'start' => now()->addMinutes(2)->format('c'),
            'end' => now()->addMinutes(62)->format('c'),
        ];

        $response = $this->postJson('/api/events/create', $overlapping_event_start)
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function overlapping_end_will_fail(): void
    {
        $event = [
            'title' => 'Recurring event 10 times',
            'start' => now()->addMinute()->format('c'),
            'end' => now()->addMinutes(61)->format('c'),
            'frequency' => 'daily',
            'repeat_until' => now()->addDays(9)->format('c'),
        ];

        $response = $this->postJson('/api/events/create', $event)
            ->assertStatus(201);

        $overlapping_event_end = [
            'title' => 'Overlapping event',
            'start' => now()->format('c'),
            'end' => now()->addHour()->format('c'),
        ];

        $response = $this->postJson('/api/events/create', $overlapping_event_end)
            ->assertStatus(422);
    }

    public static function invalidDateProvider()
    {
        return [
            [
                'wrong1' => [
                    'title' => 'ASDF',
                    'start' => now()->addDay()->format('Y-m-d H:i:s'),
                    'end' => now()->addDay()->addMinutes(20)->format('Y-m-d H:i:s'),
                ],
            ],
            [
                'wrong2' => [
                    'title' => 'ASDF',
                    'start' => now()->addDay()->format('Y-m-d H:i:s'),
                    'end' => now()->addDay()->addMinutes(20)->format('Y-m-d'),
                ],
            ],
            [
                'wrong3' => [
                    'title' => 'ASDF',
                    'start' => now()->addDay()->format('Ymd His'),
                    'end' => now()->addDay()->addMinutes(20)->format('Ymd'),
                ],
            ],
        ];
    }
}
