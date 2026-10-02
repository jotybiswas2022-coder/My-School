<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    use UploadsFiles;

    public function index(Request $request)
    {
        $albums = GalleryAlbum::withCount('images')
            ->when($request->filled('q'), fn ($q) => $q->where('title', 'like', '%' . $request->string('q') . '%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('backend.gallery.index', [
            'albums' => $albums,
            'album' => new GalleryAlbum(['category' => 'General']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'title_bn' => ['nullable', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:500'],
            'description_bn' => ['nullable', 'string', 'max:500'],
            'cover_image' => $this->imageRules(),
        ]);

        $data['cover_image'] = $this->uploadImage($request->file('cover_image'), 'gallery');

        GalleryAlbum::create($data);

        return back()->with('success', 'Album created successfully.');
    }

    public function show(GalleryAlbum $album)
    {
        return view('backend.gallery.show', [
            'album' => $album->load('images'),
        ]);
    }

    public function update(Request $request, GalleryAlbum $album)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'title_bn' => ['nullable', 'string', 'max:150'],
            'category' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:500'],
            'description_bn' => ['nullable', 'string', 'max:500'],
            'cover_image' => $this->imageRules(),
        ]);

        if ($cover = $this->uploadImage($request->file('cover_image'), 'gallery')) {
            $this->deleteImage($album->cover_image);
            $data['cover_image'] = $cover;
        }

        $album->update($data);

        return back()->with('success', 'Album updated successfully.');
    }

    public function destroy(GalleryAlbum $album)
    {
        $this->deleteImage($album->cover_image);
        foreach ($album->images as $image) {
            $this->deleteImage($image->image);
        }
        $album->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Album deleted successfully.');
    }

    public function uploadImages(Request $request, GalleryAlbum $album)
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => $this->imageRules(true),
            'caption' => ['nullable', 'string', 'max:180'],
        ]);

        foreach ($request->file('images', []) as $file) {
            $album->images()->create([
                'image' => $this->uploadImage($file, 'gallery'),
                'caption' => $request->input('caption'),
            ]);
        }

        return back()->with('success', 'Images uploaded successfully.');
    }

    public function destroyImage(GalleryImage $image)
    {
        $this->deleteImage($image->image);
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}
