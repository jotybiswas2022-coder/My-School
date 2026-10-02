<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $notices = Notice::published()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->string('q') . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('title', 'like', $term)->orWhere('description', 'like', $term);
                });
            })
            ->when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->paginate(9)
            ->withQueryString();

        return view('frontend.notices', [
            'notices' => $notices,
            'categories' => Notice::categories(),
            'latest' => Notice::published()->take(5)->get(),
        ]);
    }

    public function show(Notice $notice)
    {
        abort_unless($notice->is_published, 404);

        return view('frontend.notice-show', [
            'notice' => $notice,
            'related' => Notice::published()->where('id', '!=', $notice->id)->take(4)->get(),
        ]);
    }
}
