<x-mail::message>
# 新しいお問い合わせが届きました

**お名前:** {{ $contactMessage->name }}
**メールアドレス:** {{ $contactMessage->email }}
**受信日時:** {{ $contactMessage->created_at->format('Y/m/d H:i') }}

**お問い合わせ内容**

{{ $contactMessage->message }}

<x-mail::button :url="url('/admin/contact-messages')">
管理画面で確認する
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
