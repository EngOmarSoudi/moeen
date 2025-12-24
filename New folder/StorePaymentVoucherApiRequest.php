<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentVoucherApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // استبدلها بسياساتك إن رغبت
    }

    public function rules(): array
    {
        return [
            'voucher_date' => ['required','date'],
            'party_mode'   => ['required','in:search,other'],
            'party_search' => ['required_if:party_mode,search','nullable','string','max:50'],
            'party_other'  => ['required_if:party_mode,other','nullable','string','max:120'],
            'amount'       => ['required','numeric','min:0.01','max:999999999'],
            'currency'     => ['sometimes','string','max:6'],
            'notes'        => ['nullable','string','max:500'],

            // مرفقات
            'attachments'   => ['sometimes','array','max:10'],
            'attachments.*' => ['file','mimes:jpg,jpeg,png,webp,pdf','max:8192'],
        ];
    }

    public function messages(): array
    {
        return [
            'voucher_date.required' => 'تاريخ السند مطلوب.',
            'party_mode.required'   => 'طريقة تحديد الجهة مطلوبة.',
            'party_search.required_if' => 'حدد الجهة من البحث.',
            'party_other.required_if'  => 'أدخل اسم الجهة الأخرى.',
            'amount.required'       => 'المبلغ مطلوب.',
            'amount.min'            => 'المبلغ يجب أن يكون أكبر من صفر.',
            'attachments.*.mimes'   => 'أنواع الملفات المسموح بها: jpg,png,webp,pdf.',
            'attachments.*.max'     => 'حجم الملف الأقصى 8MB.',
        ];
    }
}
