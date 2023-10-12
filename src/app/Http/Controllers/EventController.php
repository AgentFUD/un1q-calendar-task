<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function create(CreateEventRequest $request): EventResource
    {
        $event = Event::create($request->all());

        return EventResource::make($event);
    }

    public function show(Event $event)
    {
        return EventResource::make($event);
    }

    public function list(Request $request)
    {
        $eventList = Event::where([
            ['start', '<=', Carbon::createFromFormat('Y-m-d', $request->from)],
            ['end', '>=', Carbon::createFromFormat('Y-m-d', $request->to)],
        ])->paginate(3);

        return EventResource::collection($eventList);
    }

    public function update(UpdateEventRequest $request, Event $event): EventResource
    {
        $event->update($request->only(['title', 'description', 'start', 'end']));

        return EventResource::make($event);
    }

    public function delete(Event $event): Response
    {
        $event->delete();

        return response()->noContent();
    }
}
