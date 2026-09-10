<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrowseProductsRequest extends FormRequest
{
    protected $redirectRoute = 'shop';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:255', Rule::exists(Category::class, 'slug')],
            'sort' => ['nullable', 'string', Rule::in(['newest', 'price_asc', 'price_desc'])],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'q.string' => '検索キーワードは文字列で入力してください。',
            'q.max' => '検索キーワードは100文字以内で入力してください。',
            'category.string' => 'カテゴリを正しく選択してください。',
            'category.max' => 'カテゴリを正しく選択してください。',
            'category.exists' => '選択されたカテゴリは存在しません。',
            'sort.string' => '並び順を正しく選択してください。',
            'sort.in' => '並び順を正しく選択してください。',
            'page.integer' => 'ページ番号は整数で指定してください。',
            'page.min' => 'ページ番号は1以上で指定してください。',
        ];
    }

    /** @return array{q: string, category: string, sort: string} */
    public function filters(): array
    {
        return [
            'q' => $this->validated('q') ?? '',
            'category' => $this->validated('category') ?? '',
            'sort' => $this->validated('sort') ?? 'newest',
        ];
    }
}
