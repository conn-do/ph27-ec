<html>

<body>
    <h1>パスワード再設定</h1>
    <p>{{ $user->name }} 様</p>
    <p>パスワード再設定のリクエストを受け付けました。下のリンクをクリックして、新しいパスワードを設定してください。</p>
    <p><a href="{{ $resetUrl }}">パスワードを再設定する</a></p>
    <p>このリンクの有効期限は{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }}分です。</p>
    <p>心当たりがない場合は、このメールを無視してください。</p>
</body>

</html>
