<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    use UploadsFiles;

    public function index(Request $request)
    {
        $news = News::when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->string('q') . '%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('backend.news.index', [
            'news' => $news,
            'categories' => News::categories(),
        ]);
    }

    public function create()
    {
        return view('backend.news.form', [
            'article' => new News(['is_published' => true, 'published_at' => now(), 'category' => 'General']),
            'categories' => News::categories(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['featured_image'] = $this->uploadImage($request->file('featured_image'), 'news');

        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'News article created successfully.');
    }

    public function edit(News $news)
    {
        return view('backend.news.form', [
            'article' => $news,
            'categories' => News::categories(),
        ]);
    }

    public function update(Request $request, News $news)
    {
        $data = $this->validated($request);

        if ($image = $this->uploadImage($request->file('featured_image'), 'news')) {
            $this->deleteImage($news->featured_image);
            $data['featured_image'] = $image;
        } elseif ($request->boolean('remove_featured_image') && $news->featured_image) {
            $this->deleteImage($news->featured_image);
            $data['featured_image'] = null;
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'News article updated successfully.');
    }

    public function destroy(News $news)
    {
        $this->deleteImage($news->featured_image);
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News article deleted successfully.');
    }

    public function togglePublish(News $news)
    {
        $news->update([
            'is_published' => ! $news->is_published,
            'published_at' => $news->published_at ?? now(),
        ]);

        return back()->with('success', $news->is_published ? 'News published.' : 'News unpublished.');
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
            'description' => ['required', 'string', 'max:8000'],
            'description_bn' => ['nullable', 'string', 'max:8000'],
            'featured_image' => $this->imageRules(),
            'published_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
        ]) + [
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->date('published_at') ?: now(),
        ];
    }
}
