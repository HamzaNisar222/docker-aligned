<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorServiceRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'service_id' => 'required|exists:services,id',
            'document_path' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png',
        ];
    }
}
