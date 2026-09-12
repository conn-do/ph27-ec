<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\PointHistory;
use Illuminate\Support\Facades\DB;

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

        $user = auth()->user();

        // 1. 同一商品への重複レビュー投稿チェック
        $alreadyReviewed = Review::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'この商品には既にレビューを投稿済みです。');
        }

        // 2. 過去に一度でもレビューを投稿したことがあるかチェック（初回判定）
        $hasReviewedBefore = Review::where('user_id', $user->id)->exists();

        $message = 'レビューを投稿しました！';

        // 3. データベースのトランザクション処理
        DB::transaction(function () use ($request, $user, $hasReviewedBefore, &$message) {
            // レビューの保存
            Review::create([
                'user_id'    => $user->id,
                'product_id' => $request->product_id,
                'rating'     => $request->rating,
                'comment'    => $request->comment,
            ]);

            // 🎁 初回レビュー投稿のみ100ptプレゼント
            if (!$hasReviewedBefore) {
                $bonusPoint = 100;

                // ユーザーの所持ポイントを加算
                $user->increment('point', $bonusPoint);

                // ポイント履歴テーブルに登録
                PointHistory::create([
                    'user_id'     => $user->id,
                    'points'      => $bonusPoint,
                    'description' => '初回レビュー投稿特典ポイント付与',
                ]);

                $message = 'レビューを投稿しました！初回特典として100ptを獲得しました🎁';
            }
        });

        return back()->with('message', $message);
    }

    // 削除処理（destroy）
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