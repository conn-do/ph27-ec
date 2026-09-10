<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('orders/index', [
            'orders' => $request->user()
                ->orders()
                ->withCount('items')
                ->latest()
                ->get(['id', 'total_price', 'status', 'created_at']),
        ]);
    }
}
