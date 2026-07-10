<html>

<body>
    <h1>ご注文ありがとうございます</h1>
    <p>{{ $user->name }} 様</p>
    <p>このたびはご注文いただき、誠にありがとうございます。以下の内容で承りました。</p>
    <p>注文番号: {{ $order->id }}</p>
    <p>注文日: {{ $order->created_at->format('Y/m/d H:i:s') }}</p>
    <p>合計金額: {{ number_format($order->total_price) }}円</p>
    <table>
        <tr>
            <th>商品名</th>
            <th>数量</th>
        </tr>
        @foreach ($order->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->quantity }}</td>
            </tr>
        @endforeach
    </table>
    <p>商品の発送準備が整い次第、改めてご連絡いたします。</p>
</body>

</html>
