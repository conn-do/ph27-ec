@if ($product->stock <= 0)
    <p class="stock stock--empty">売り切れ</p>
@elseif ($product->stock <= 5)
    <p class="stock stock--low">残りわずか（{{ $product->stock }}個）</p>
@else
    <p class="stock">在庫あり（{{ $product->stock }}個）</p>
@endif
