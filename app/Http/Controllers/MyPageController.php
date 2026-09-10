<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MyPageController extends Controller
{
    public function index(Request $request): View
    {
        $favorites = $request->user()->favorites()->with('product.category')->latest()->get();

        return view('mypage', ['favorites' => $favorites]);
    }
}
