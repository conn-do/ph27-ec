<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Contracts\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        return view('news.index', [
            'news' => News::published()->paginate(10),
        ]);
    }

    public function show(News $news): View
    {
        abort_if($news->published_at === null || $news->published_at->isFuture(), 404);

        return view('news.show', [
            'topic' => $news,
        ]);
    }
}
