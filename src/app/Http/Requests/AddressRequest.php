<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'name' =>'required|max:20',
            'code' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required',
            'building' => 'nullable',
        ];
    }
    public function messages()
    {
        return[
            'name.required' =>'ユーザー名を入力してください',
            'name.max' =>'ユーザー名は20文字以内で入力してください',
            'code.required' => '郵便番号を入力してください',
            'code.regex' => '郵便番号はハイフン込みの8桁で入力してください',
            'address.required' => '住所を入力してください'
        ];
    }
}
