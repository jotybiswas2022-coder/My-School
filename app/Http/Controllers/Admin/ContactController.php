<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $messages = Contact::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('subject', 'like', $term);
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('is_read', $request->string('status') === 'read'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('backend.messages.index', [
            'messages' => $messages,
            'unread' => Contact::where('is_read', false)->count(),
        ]);
    }

    public function show(Contact $message)
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('backend.messages.show', compact('message'));
    }

    public function toggleRead(Contact $message)
    {
        $message->update(['is_read' => ! $message->is_read]);

        return back()->with('success', $message->is_read ? 'Message marked as read.' : 'Message marked as unread.');
    }

    public function destroy(Contact $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('success', 'Message deleted successfully.');
    }
}
