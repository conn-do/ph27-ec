<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function show(News $news)
    {
        return view('news.show', [
            'news' => $news,
        ]);
    }

    public function index()
{
    $newsList = News::latest()->get(); // または paginate(10)
    return view('news.index', compact('newsList'));
}
}