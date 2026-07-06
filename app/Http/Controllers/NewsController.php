<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index() {
        $news = News::orderBy('id', 'desc')->limit(3)->get();
        return view('index', compact('news'));
    }

    public function show(News $news) {
        return view('news.show', compact('news'));
    }
}
