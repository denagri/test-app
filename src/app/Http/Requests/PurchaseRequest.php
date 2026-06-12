<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment_id' => 'required',
            'address_check' => 'required',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'address_check'=>auth()->user()->address ?'exists':null,
        ]);
    }
    public function messages()
    {
        return [
            'payment_id.required' => '支払方法を選択してください',
            'address_check.required' => '住所を登録してください',
        ];
    }
}
