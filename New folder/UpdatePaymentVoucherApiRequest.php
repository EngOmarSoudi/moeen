<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentVoucherApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'voucher_date' => ['sometimes','date'],
            'party_mode'   => ['sometimes','in:search,other'],
            'party_search' => ['required_if:party_mode,search','nullable','string','max:50'],
            'party_other'  => ['required_if:party_mode,other','nullable','string','max:120'],
            'amount'       => ['sometimes','numeric','min:0.01','max:999999999'],
            'currency'     => ['sometimes','string','max:6'],
            'notes'        => ['nullable','string','max:500'],

            'attachments'   => ['sometimes','array','max:10'],
            'attachments.*' => ['file','mimes:jpg,jpeg,png,webp,pdf','max:8192'],
        ];
    }

    public function messages(): array
    {
        return [
            'attachments.*.mimes'   => 'أنواع الملفات المسموح بها: jpg,png,webp,pdf.',
            'attachments.*.max'     => 'حجم الملف الأقصى 8MB.',
        ];
    }
}
