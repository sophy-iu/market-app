<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'image' => ['nullable', 'image', 'mimes:jpeg,png'],
            'name' => ['required', 'string', 'max:20'],
            'postal_code' => ['required', 'string', 'size:8'],
            'address' => ['required', 'string'],
            'building' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => 'プロフィール画像は.jpegまたは.png形式でアップロードしてください。',

            'name.required' => 'ユーザー名を入力してください。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',

            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.size' => '郵便番号は8文字で入力してください。',

            'address.required' => '住所を入力してください。',
        ];
    }
}
