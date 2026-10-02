<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab') === 'past' ? 'past' : 'upcoming';

        $events = Event::published()
            ->when($tab === 'past', fn ($q) => $q->past(), fn ($q) => $q->upcoming())
            ->paginate(9)
            ->withQueryString();

        return view('frontend.events', compact('events', 'tab'));
    }

    public function show(Event $event)
    {
        abort_unless($event->is_published, 404);

        return view('frontend.event-show', [
            'event' => $event,
            'others' => Event::published()->upcoming()->where('id', '!=', $event->id)->take(3)->get(),
        ]);
    }
}
