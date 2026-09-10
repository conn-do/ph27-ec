<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrowseProductsRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(BrowseProductsRequest $request): Response|RedirectResponse
    {
        $filters = $request->filters();
        $products = Product::query()
            ->with('category:id,name,slug')
            ->where('is_active', true)
            ->when($filters['q'] !== '', function (Builder $query) use ($filters) {
                $query->where(function (Builder $search) use ($filters) {
                    $search->whereLike('name', '%'.$filters['q'].'%')
                        ->orWhereLike('description', '%'.$filters['q'].'%');
                });
            })
            ->when($filters['category'] !== '', function (Builder $query) use ($filters) {
                $query->whereHas('category', function (Builder $category) use ($filters) {
                    $category->where('slug', $filters['category']);
                });
            });

        match ($filters['sort']) {
            'price_asc' => $products->orderBy('price'),
            'price_desc' => $products->orderByDesc('price'),
            default => $products,
        };

        $products = $products->latest()->orderByDesc('id')->paginate(12)->withQueryString();

        if ($products->currentPage() > $products->lastPage()) {
            return to_route('shop', $filters);
        }

        return Inertia::render('shop/index', [
            'products' => $products,
            'categories' => Category::orderBy('id')->get(['id', 'name', 'slug']),
            'filters' => $filters,
        ]);
    }

    public function show(Product $product): Response
    {
        abort_unless($product->is_active, 404);

        return Inertia::render('products/show', [
            'product' => $product->load('category:id,name,slug'),
        ]);
    }
}
