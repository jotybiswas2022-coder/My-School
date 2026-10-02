<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    use UploadsFiles;

    public function index(Request $request)
    {
        $notices = Notice::with('author')
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->string('q') . '%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('backend.notices.index', [
            'notices' => $notices,
            'categories' => Notice::categories(),
        ]);
    }

    public function create()
    {
        return view('backend.notices.form', [
            'notice' => new Notice(['is_published' => true, 'published_at' => now(), 'category' => 'General']),
            'categories' => Notice::categories(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['user_id'] = Auth::id();
        $data['attachment'] = $this->uploadImage($request->file('attachment'), 'notices');

        Notice::create($data);

        return redirect()->route('admin.notices.index')->with('success', 'Notice created successfully.');
    }

    public function edit(Notice $notice)
    {
        return view('backend.notices.form', [
            'notice' => $notice,
            'categories' => Notice::categories(),
        ]);
    }

    public function update(Request $request, Notice $notice)
    {
        $data = $this->validated($request);

        if ($attachment = $this->uploadImage($request->file('attachment'), 'notices')) {
            $this->deleteImage($notice->attachment);
            $data['attachment'] = $attachment;
        }

        $notice->update($data);

        return redirect()->route('admin.notices.index')->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        $this->deleteImage($notice->attachment);
        $notice->delete();

        return redirect()->route('admin.notices.index')->with('success', 'Notice deleted successfully.');
    }

    public function togglePublish(Notice $notice)
    {
        $notice->update([
            'is_published' => ! $notice->is_published,
            'published_at' => $notice->published_at ?? now(),
        ]);

        return back()->with('success', $notice->is_published ? 'Notice published.' : 'Notice unpublished.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'title_bn' => ['nullable', 'string', 'max:180'],
            'category' => ['required', 'string', 'max:60'],
            'description' => ['required', 'string', 'max:5000'],
            'description_bn' => ['nullable', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx', 'max:5120'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]) + [
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->date('published_at') ?: now(),
        ];
    }
}
