<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function create(CreateEventRequest $request): JsonResponse
    {
        $event = Event::create($request->all());

        return response()->json($event, 201);
    }

    public function show(Event $event)
    {
        return new EventResource($event);
    }

    public function list(Request $request)
    {
        $eventList = Event::where([
            ['start', '<=', Carbon::createFromFormat('Y-m-d', $request->from)],
            ['end', '>=', Carbon::createFromFormat('Y-m-d', $request->to)],
        ])->paginate(3);

        return EventResource::collection($eventList);
    }

    public function update(UpdateEventRequest $request, Event $event): JsonResponse
    {
        $event->update($request->only(['title', 'description', 'start', 'end']));

        return response()->json($event->refresh(), 201);
    }

    public function delete(Event $event): Response
    {
        $event->delete();

        return response(null, Response::HTTP_OK);
    }
}
