<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $albums = GalleryAlbum::withCount('images')
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('frontend.gallery', [
            'albums' => $albums,
            'categories' => GalleryAlbum::distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    public function show(GalleryAlbum $album)
    {
        return view('frontend.gallery-show', [
            'album' => $album->load('images'),
            'others' => GalleryAlbum::where('id', '!=', $album->id)->withCount('images')->latest()->take(3)->get(),
        ]);
    }
}
