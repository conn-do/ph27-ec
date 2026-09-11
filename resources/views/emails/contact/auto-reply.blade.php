<x-mail::message>
# お問い合わせありがとうございます

{{ $contactMessage->name }} 様

以下の内容でお問い合わせを受け付けました。内容を確認のうえ、担当者よりご連絡いたします。

**お問い合わせ内容**

{{ $contactMessage->message }}

引き続きよろしくお願いいたします。<br>
{{ config('app.name') }}
</x-mail::message>
