<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * おこづかい帳。いつ いくら つかったかを ならべて見せる。
 */
class WalletController extends Controller
{
    public function index(Request $request): View
    {
        return view('wallet.index', [
            'user' => $request->user(),
            'transactions' => $request->user()
                ->allowanceTransactions()
                ->with('order')
                ->orderByDesc('created_at')
                ->paginate(15),
        ]);
    }
}
