<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsController extends Controller
{
    $news = News::orderBy('id', 'desc')->limit(3)->get();
}
