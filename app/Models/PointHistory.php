<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'points', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mypage()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->latest()->get();
        $pointHistories = PointHistory::where('user_id', $user->id)->latest()->take(10)->get();

        return view('mypage', compact('user', 'orders', 'pointHistories'));
    }
}