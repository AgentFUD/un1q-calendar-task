<?php

namespace App\Domains\Event\Http\Controllers;

use App\Domains\Event\Http\Requests\CreateEventRequest;
use App\Domains\Event\Http\Requests\UpdateEventRequest;
use App\Http\Controllers\Controller;
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

    public function show(Event $event): Event
    {
        return $event;
    }

    public function list(Request $request)
    {
        $eventList = Event::where([
            ['start', '<=', Carbon::parse($request->from)],
            ['end', '>=', Carbon::parse($request->to)],
        ])->paginate(3);

        return response()->json($eventList, 200);
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