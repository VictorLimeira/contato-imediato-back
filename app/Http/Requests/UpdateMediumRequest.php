<?php

namespace App\Http\Requests;

use App\Models\Medium;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateMediumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "category" => [
                "required",
                "string",
                "max:255",
                Rule::in(Medium::CATEGORIES)
            ],
            "value" => "required|string|max:255",
        ];
    }
}
