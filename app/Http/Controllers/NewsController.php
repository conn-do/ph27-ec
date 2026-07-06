<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Contracts\View\View;

class NewsController extends Controller
{
    public function show(News $news): View
    {
        return view('news.show', [
            'news' => $news,
        ]);
    }
}
