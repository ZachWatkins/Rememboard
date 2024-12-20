<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Event;
use Inertia\Response;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\EventStoreRequest;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\EventUpdateRequest;

class EventController extends Controller
{
    public function index(Request $request): Response
    {
        $events = Event::all();

        // If a user is logged in, convert the start_date and end_date to the user's timezone.
        $events->each(function ($event) use ($request) {
            $event->start_date = \dateToSessionTime($event->start_date, $request->user());
            $event->end_date = \dateToSessionTime($event->end_date, $request->user());
        });

        return Inertia::render('Event/Index', [
            'events' => $events,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Event/Create');
    }

    public function store(EventStoreRequest $request): RedirectResponse
    {
        $event = Event::create($request->validated());

        $request->session()->flash('event.id', $event->id);

        return Redirect::route('events.edit', $event);
    }

    public function show(Request $request, Event $event): View
    {
        $event->start_date = \dateToSessionTime($event->start_date, $request->user());
        $event->end_date = \dateToSessionTime($event->end_date, $request->user());
        return view('event.show', compact('event'));
    }

    public function edit(Request $request, Event $event): View
    {
        return view('event.edit', compact('event'));
    }

    public function update(EventUpdateRequest $request, Event $event): RedirectResponse
    {
        $event->update($request->validated());

        $request->session()->flash('event.id', $event->id);

        return redirect()->route('events.index');
    }

    public function destroy(Request $request, Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('events.index');
    }

    public function countdowns(): View
    {
        $events = Event::where('show_on_countdown', true)->orderBy('start_date')->get();

        return view('event.index', compact('events'));
    }

    public function trips(): JsonResponse
    {
        $trips = Event::where('is_trip', true)->get();

        return response()->json($trips);
    }
}
