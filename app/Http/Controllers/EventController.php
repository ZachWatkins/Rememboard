<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function countdowns(Request $request): Response
    {
        $events = Event::where('show_on_countdown', $show_on_countdown)->orderBy('start_date')->get();

        return view('event.index', compact('events'));
    }

    public function trips(Request $request): Response
    {
        $events = Event::where('is_trip', $is_trip)->orderBy('start_date')->get();

        return $trips;
    }
}
