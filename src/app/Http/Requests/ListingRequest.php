<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListingRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'=>'required',
            'explanation'=>'required|max:255',
            'image'=>'required|mimes:jpeg,png',
            'category_ids'=>'required',
            'condition_id'=>'required',
            'price'=>'required|integer|min:0',
        ];
    }
    public function messages()
    {
        return [
            'name.required'=>'商品名を入力してください',
            'explanation.required'=>'商品の説明を入力してください',
            'explanation.max'=>'説明は255文字以内で入力してください',
            'image.required'=>'画像をアップロードしてください',
            'image.mimes'=>'画像は.jpegか.pngで入力してください',
            'category_ids.required'=>'カテゴリーを選択してください',
            'condition_id.required'=>'商品の状態を選択してください',
            'price.required'=>'販売価格を入力してください',
            'price.integer'=>'販売価格は数字で入力してください',
            'price.min'=>'販売価格は0以上の数字を入力してください',
        ];
    }
}
