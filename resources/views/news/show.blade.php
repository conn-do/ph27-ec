<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }} - すごい文房具ECサイト</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div style="padding: 20px; max-width: 800px; margin: 0 auto;">
        <!-- お知らせのタイトルを表示 -->
        <h1 style="border-bottom: 2px solid #ccc; padding-bottom: 10px;">{{ $news->title }}</h1>
        
        <!-- お知らせの公開日時-->
        <p style="color: #666; text-align: right;">公開日: {{ $news->created_at->format('Y/m/d') }}</p>

        <div style="margin-top: 20px; line-height: 1.6;">
            {!! $news->content !!}
        </div>

        <div style="margin-top: 40px;">
            <a href="/" style="color: #3490dc; text-decoration: none;">← 商品一覧に戻る</a>
        </div>
    </div>
</body>
</html>