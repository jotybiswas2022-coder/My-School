<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::published()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', $term)->orWhere('description', 'like', $term);
                });
            })
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->paginate(9)
            ->withQueryString();

        return view('frontend.news', [
            'news' => $news,
            'categories' => News::categories(),
            'featured' => News::published()->first(),
        ]);
    }

    public function show(News $news)
    {
        abort_unless($news->is_published, 404);

        return view('frontend.news-show', [
            'article' => $news,
            'related' => News::published()->where('id', '!=', $news->id)->take(3)->get(),
        ]);
    }
}
