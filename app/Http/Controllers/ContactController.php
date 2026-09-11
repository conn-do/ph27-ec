<?php

namespace App\Http\Controllers;

use App\Mail\ContactAdminNotificationMail;
use App\Mail\ContactAutoReplyMail;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $contactMessage = ContactMessage::create($validated);

        Mail::to($contactMessage->email)->send(new ContactAutoReplyMail($contactMessage));

        // 管理者ごとに個別送信する。まとめて1通で送ると、いずれか1人でも
        // 無効なアドレスの場合に他の管理者にも届かなくなってしまうため。
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            Mail::to($admin)->send(new ContactAdminNotificationMail($contactMessage));
        }

        return redirect('/contact')->with('message', 'お問い合わせを受け付けました。内容を確認のうえご連絡いたします。');
    }
}
