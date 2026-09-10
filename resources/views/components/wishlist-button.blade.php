@props(['product'])
@php($saved = in_array($product->id, session('wishlist', [])))
<form action="{{ route($saved ? 'wishlist.destroy' : 'wishlist.store', $product) }}" method="POST" class="mt-4">
    @csrf
    @if ($saved) @method('DELETE') @endif
    <button type="submit" aria-label="{{ $product->name }}：{{ $saved ? 'お気に入りから削除' : 'お気に入りに追加' }}" class="rounded-full border border-amber-300 px-5 py-3 text-sm font-bold text-amber-800 hover:bg-amber-50">{{ $saved ? '♥ お気に入りから削除' : '♡ お気に入りに追加' }}</button>
</form>
