@extends('layouts.base')
@section('title', '人気ランキング')
@section('content')
    <div class="page-heading ranking-heading">
        <p class="eyebrow">BEST SELLERS</p>
        <h1>人気の文房具ランキング</h1>
        <p>お客さまに選ばれた数をもとに、今人気の道具をご紹介します。</p>
    </div>
    <section class="collection ranking-collection" aria-labelledby="ranking-title">
        <div class="section-heading">
            <div>
                <p class="eyebrow">CUSTOMER FAVORITES</p>
                <h2 id="ranking-title">暮らしに選ばれているもの</h2>
            </div>
            <span>累計販売数順</span>
        </div>
        <div class="product-grid">
            @foreach ($products as $product)
                <x-product-card :product="$product" :rank="$loop->iteration" />
            @endforeach
        </div>
    </section>
@endsection
