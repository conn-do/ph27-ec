<?php

namespace App\Http\Controllers;
use App\Models\News;

use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function index()
    {
        $news = News::all();
        return view('mypage', compact('news'));
    }
}