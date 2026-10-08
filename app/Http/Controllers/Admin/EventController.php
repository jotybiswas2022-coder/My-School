<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use UploadsFiles;

    public function index(Request $request)
    {
        $events = Event::when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->string('q') . '%'))
            ->orderByDesc('event_date')
            ->paginate(15)
            ->withQueryString();

        return view('backend.events.index', compact('events'));
    }

    public function create()
    {
        return view('backend.events.form', [
            'event' => new Event(['is_published' => true, 'event_date' => now()]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->uploadImage($request->file('image'), 'events');

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('backend.events.form', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $this->validated($request);

        if ($image = $this->uploadImage($request->file('image'), 'events')) {
            $this->deleteImage($event->image);
            $data['image'] = $image;
        } elseif ($request->boolean('remove_image') && $event->image) {
            $this->deleteImage($event->image);
            $data['image'] = null;
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->deleteImage($event->image);
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    public function togglePublish(Event $event)
    {
        $event->update(['is_published' => ! $event->is_published]);

        return back()->with('success', $event->is_published ? 'Event published.' : 'Event unpublished.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'title_bn' => ['nullable', 'string', 'max:180'],
            'description' => ['required', 'string', 'max:5000'],
            'description_bn' => ['nullable', 'string', 'max:5000'],
            'image' => $this->imageRules(),
            'event_date' => ['required', 'date'],
            'event_time' => ['nullable', 'date_format:H:i'],
            'location' => ['nullable', 'string', 'max:180'],
            'location_bn' => ['nullable', 'string', 'max:180'],
            'is_published' => ['nullable', 'boolean'],
        ]) + ['is_published' => $request->boolean('is_published')];
    }
}
