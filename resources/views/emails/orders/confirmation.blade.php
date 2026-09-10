<x-mail::message>
# ご注文ありがとうございます

{{ $order->shipping_name }} 様

以下の内容でご注文を承りました。

**注文ID:** {{ $order->id }}
**注文日時:** {{ $order->created_at->format('Y/m/d H:i') }}

<x-mail::table>
| 商品名 | 個数 |
| :- | -: |
@foreach ($order->details as $detail)
| {{ $detail->product->name }} | {{ $detail->quantity }}個 |
@endforeach
</x-mail::table>

**合計金額:** {{ number_format($order->total_price) }}円

## お届け先

{{ $order->shipping_name }} 様
〒{{ $order->shipping_postal_code }}
{{ $order->shipping_address }}
{{ $order->shipping_phone }}

<x-mail::button :url="url('/orders/'.$order->id)">
注文詳細を見る
</x-mail::button>

引き続きよろしくお願いいたします。<br>
{{ config('app.name') }}
</x-mail::message>
