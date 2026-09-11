<?php

use App\Mail\ContactAdminNotificationMail;
use App\Mail\ContactAutoReplyMail;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('お問い合わせフォームを表示できる', function () {
    $this->get('/contact')->assertOk();
});

test('お問い合わせを送信できる', function () {
    $response = $this->post('/contact', [
        'name' => '山田太郎',
        'email' => 'yamada@example.com',
        'message' => '在庫の再入荷予定を教えてください。',
    ]);

    $response->assertRedirect('/contact');
    $this->assertDatabaseHas('contact_messages', [
        'name' => '山田太郎',
        'email' => 'yamada@example.com',
        'message' => '在庫の再入荷予定を教えてください。',
        'is_read' => false,
    ]);
});

test('送信すると本人へ自動返信メールが送られる', function () {
    Mail::fake();

    $this->post('/contact', [
        'name' => '山田太郎',
        'email' => 'yamada@example.com',
        'message' => '在庫の再入荷予定を教えてください。',
    ]);

    Mail::assertQueued(ContactAutoReplyMail::class, function ($mail) {
        return $mail->hasTo('yamada@example.com')
            && $mail->contactMessage->name === '山田太郎';
    });
});

test('送信すると管理者へ通知メールが送られる', function () {
    Mail::fake();
    $admin = User::factory()->create(['is_admin' => true, 'email' => 'admin@example.com']);
    User::factory()->create(['is_admin' => false, 'email' => 'not-admin@example.com']);

    $this->post('/contact', [
        'name' => '山田太郎',
        'email' => 'yamada@example.com',
        'message' => '在庫の再入荷予定を教えてください。',
    ]);

    Mail::assertQueued(ContactAdminNotificationMail::class, function ($mail) use ($admin) {
        return $mail->hasTo($admin->email);
    });
});

test('必須項目が空だとお問い合わせを送信できない', function () {
    $response = $this->post('/contact', []);

    $response->assertSessionHasErrors(['name', 'email', 'message']);
    expect(ContactMessage::count())->toBe(0);
});

test('メールアドレスの形式が不正だと送信できない', function () {
    $response = $this->post('/contact', [
        'name' => '山田太郎',
        'email' => 'not-an-email',
        'message' => 'テスト',
    ]);

    $response->assertSessionHasErrors(['email']);
});
