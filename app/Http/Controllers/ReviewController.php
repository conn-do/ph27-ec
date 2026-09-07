<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // 入力チェック（バリデーション）
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|max:1000',
        ]);

        // レビューの保存
        Review::create([
            'user_id'    => auth()->id(),
            'product_id' => $request->product_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return back()->with('message', 'レビューを投稿しました！');
    }

    // ▼ ここから削除処理（destroy）を追加
    public function destroy(Review $review)
    {
        // 本人のレビューかどうかチェック（他人のレビューは削除不可）
        if ($review->user_id !== auth()->id()) {
            return back()->with('error', '他のユーザーのレビューは削除できません。');
        }

        // レビューを削除
        $review->delete();

        return back()->with('message', 'レビューを削除しました。');
    }
}