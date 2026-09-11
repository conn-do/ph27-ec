<?php

namespace App\Http\Controllers;

use App\Actions\Shop\ChangeCalculator;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->withSum('details', 'quantity')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
        ]);
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        return view('orders.show', [
            'order' => $order->load('details.product'),
        ]);
    }

    /**
     * おかいけいが おわったあとの「レシート」画面。
     */
    public function complete(Order $order, ChangeCalculator $changeCalculator): View
    {
        $this->authorize('view', $order);

        return view('orders.complete', [
            'order' => $order->load('details'),
            'changeBreakdown' => $changeCalculator->breakdown($order->change_amount),
        ]);
    }
}
