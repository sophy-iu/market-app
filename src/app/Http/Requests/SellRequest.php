<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'item_name' => ['required', 'string'],
            'item_description' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'condition_id' => ['required', 'exists:conditions,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'brand_name' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'item_name.required' => '商品名を入力してください。',

            'item_description.required' => '商品説明を入力してください。',
            'item_description.max' => '商品説明は255文字以内で入力してください。',

            'image.required' => '商品画像を選択してください。',
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => '商品画像は.jpegまたは.png形式でアップロードしてください。',

            'categories.required' => '商品のカテゴリーを選択してください。',
            'categories.min' => '商品のカテゴリーを選択してください。',
            'categories.array' => '商品のカテゴリーを選択してください。',

            'condition_id.required' => '商品の状態を選択してください。',
            'condition_id.exists' => '選択された商品の状態が正しくありません。',

            'price.required' => '商品価格を入力してください。',
            'price.numeric' => '商品価格は数値で入力してください。',
            'price.min' => '商品価格は0円以上で入力してください。',
        ];
    }
}
