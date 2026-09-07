<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductSearchRequest;
use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(ProductSearchRequest $request, ?Category $category = null): View
    {
        $keyword = $request->string('keyword')->trim()->toString();
        $sort = $request->input('sort') ?: 'newest';
        $query = $this->productQuery($request->user())
            ->when($category, fn ($query) => $query->where('category_id', $category->id))
            ->when($keyword !== '', fn ($query) => $query->where('name', 'like', '%'.$keyword.'%'));
        match ($sort) {
            'price_asc' => $query->orderBy('price')->orderBy('id'),
            'price_desc' => $query->orderByDesc('price')->orderBy('id'),
            default => $query->orderByDesc('id'),
        };

        return view('index', [
            'products' => $query->paginate(9)->withQueryString(),
            'categories' => Category::withCount('products')->get(),
            'category' => $category,
            'keyword' => $keyword,
            'sort' => $sort,
            'news' => News::latest('id')->limit(3)->get(),
            'ranking' => $this->rankingQuery($request->user())->limit(5)->get(),
        ]);
    }

    public function show(Request $request, Product $product): View
    {
        $product->load('category');
        $this->loadFavoriteStatus($product, $request->user());

        return view('products.show', [
            'product' => $product,
            'related' => $this->productQuery($request->user())->where('category_id', $product->category_id)->whereKeyNot($product->id)->limit(3)->get(),
        ]);
    }

    public function search(ProductSearchRequest $request): View
    {
        return $this->index($request);
    }

    public function category(ProductSearchRequest $request, Category $category): View
    {
        return $this->index($request, $category);
    }

    public function ranking(Request $request): View
    {
        return view('products.ranking', [
            'products' => $this->rankingQuery($request->user())->limit(5)->get(),
        ]);
    }

    private function productQuery(?User $user): Builder
    {
        $query = Product::query()->with('category');

        if ($user) {
            $query->withExists([
                'favoritedBy as is_favorited' => fn (Builder $query) => $query->where('users.id', $user->id),
            ]);
        }

        return $query;
    }

    private function rankingQuery(?User $user): Builder
    {
        return $this->productQuery($user)
            ->withSum('orderDetails as sold_quantity', 'quantity')
            ->orderByDesc('sold_quantity')
            ->orderByDesc('id');
    }

    private function loadFavoriteStatus(Product $product, ?User $user): void
    {
        if (! $user) {
            $product->setAttribute('is_favorited', false);

            return;
        }

        $product->loadExists([
            'favoritedBy as is_favorited' => fn (Builder $query) => $query->where('users.id', $user->id),
        ]);
    }
}
