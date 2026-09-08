<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()->favorites;

        return view('mypage', compact('favorites'));
    }
}