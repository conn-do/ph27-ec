<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductSearchRequest;
use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(ProductSearchRequest $request): View
    {
        $filters = $request->validated();
        $query = Product::query();

        if (filled($filters['keyword'] ?? null)) {
            $keyword = $filters['keyword'];
            $query->where(function (Builder $query) use ($keyword): void {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if (filled($filters['category'] ?? null)) {
            $query->where('category_id', $filters['category']);
        }

        foreach (['min_price' => '>=', 'max_price' => '<='] as $filter => $operator) {
            if (filled($filters[$filter] ?? null)) {
                $query->where('price', $operator, $filters[$filter]);
            }
        }

        [$column, $direction] = match ($filters['sort'] ?? 'newest') {
            'price_asc' => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
            'name' => ['name', 'asc'],
            default => ['id', 'desc'],
        };

        return view('index', [
            'news' => News::query()->latest('id')->limit(3)->get(),
            'categories' => Category::query()->orderBy('name')->get(),
            'filters' => $filters,
            'products' => $query->orderBy($column, $direction)->orderBy('id')
                ->paginate(12)->appends($request->safe()->except('page'))->fragment('products'),
        ]);
    }

    public function show(Product $product): View
    {
        return view('products.show', [
            'product' => $product,
        ]);
    }

    public function search(ProductSearchRequest $request): View
    {
        return $this->index($request);
    }
}
