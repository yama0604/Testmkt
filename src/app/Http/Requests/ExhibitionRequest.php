<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'listing_name'   => ['required', 'string', 'max:255'],
            'explanation'    => ['required', 'string', 'max:255'],
            'product_image'  => ['required', 'image', 'mimes:jpeg,png'],
            'categories'     => ['required', 'array', 'min:1'],
            'categories.*'   => ['exists:categories,id'],
            'status'         => ['required', 'in:0,1,2,3'],
            'price'          => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'listing_name.required' => '商品名は必須です。',
            'explanation.required'  => '商品の説明は必須です。',
            'product_image.required'=> '商品画像は必須です。',
            'product_image.image'   => '画像ファイルを選択してください。',
            'product_image.mimes'   => '画像はjpegまたはpng形式でアップロードしてください。',
            'categories.required'   => 'カテゴリーを一つ以上選択してください。',
            'status.required'       => '商品の状態を選択してください。',
            'price.required'        => '販売価格は必須です。',
            'price.numeric'         => '販売価格は数値で入力してください。',
            'price.min'             => '販売価格は0円以上にしてください。',
        ];
    }
}
