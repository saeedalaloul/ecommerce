<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
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
            'logo' => 'required_without:id|mimes:jpg,jpeg,png',
            'name' => 'required|string|max:20',
            'mobile' => 'required|max:11|unique:vendors,mobile,' . $this->id,
            'email' => 'required|email|unique:vendors,email,' . $this->id,
            'category_id' => 'required|exists:main_categories,id',
            'address' => 'required|string|max:500',
            'password' => 'required_without:id'
        ];
    }

    public function messages()
    {
        return [
            'logo.required_without' => 'حقل الصورة مطلوب',
            'logo.mimes' => 'يجب أن تكون صيغة الصورة jpg أو jpeg أو png .',
            'name.required' => 'حقل اسم المتجر مطلوب .',
            'name.string' => 'يجب أن يتكون حقل اسم المتجر من حروف .',
            'name.max' => 'يجب أن لا يزيد حجم الإسم عن 20 حرف .',
            'mobile.required' => 'حقل الهاتف مطلوب .',
            'mobile.unique' => 'رقم الهاتف المدخل موجود بالفعل .',
            'mobile.max' => 'يجب أن لا يزيد طول رقم الهاتف عن 11 رقم .',
            'email.required' => 'حقل البريد الإلكتروني مطلوب .',
            'email.email' => 'يجب أن يكون حقل البريد الإلكتروني صحيح .',
            'email.unique' => 'البريد الإلكتروني المدخل موجود بالفعل .',
            'category_id.required' => 'يجب اختيار قسم للمتجر .',
            'category_id.exists' => 'القسم الذي قمت بإختياره غير موجود في قاعدة البيانات .',
            'address.required' => 'العنوان مطلوب .',
            'address.string' => 'العنوان يجب أن يكون نص .',
            'address.max' => 'العنوان يجب أن لا يزيد عن 500 حرف .',
            'password.required_without' => 'كلمة المرور مطلوبة .',
            'password.string' => 'كلمة المرور يجب أن تتكون من نص وأرقام .',
            'password.min' => 'كلمة المرور يجب أن لا تقل عن 6 أحرف .',
        ];
    }
}
