<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguagesRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'abbr' => 'required|string|max:10',
          //  'active' => 'required|in:0,1',
            'direction' => 'required|in:rtl,ltr',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم اللغة مطلوب.',
            'name.string' => 'اسم اللغة يجب أن يكون نص.',
            'name.max' => 'يجب أن لا يزيد اسم اللغة عن 100 حرف.',
            'abbr.required' => 'اختصار اللغة مطلوب.',
            'abbr.string' => 'اختصار اللغة يجب أن يكون نص.',
            'abbr.max' => 'يجب أن لا يزيد اختصار اللغة عن 10 حرف.',
            'active.required' => 'حالة اللغة مطلوبة.',
            'direction.required' => 'اتجاه اللغة مطلوب.',
            'in' => 'القيمة المدخلة غير صحيحة.',
        ];
    }
}
