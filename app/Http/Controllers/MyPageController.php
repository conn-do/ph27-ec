<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $address = $request->user()->address;

        return view('mypage', [
            'address' => $address,
        ]);
    }
}
