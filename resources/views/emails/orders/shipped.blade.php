<x-mail::message>
# 商品を発送しました

{{ $order->shipping_name }} 様

ご注文の商品を発送いたしましたのでお知らせいたします。

**注文ID:** {{ $order->id }}
**発送日時:** {{ $order->shipped_at?->format('Y/m/d H:i') }}

@if ($order->carrier)
**配送業者:** {{ $order->carrier }}
@endif
@if ($order->tracking_number)
**追跡番号:** {{ $order->tracking_number }}
@endif

<x-mail::table>
| 商品名 | 個数 |
| :- | -: |
@foreach ($order->details as $detail)
| {{ $detail->product->name }} | {{ $detail->quantity }}個 |
@endforeach
</x-mail::table>

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
