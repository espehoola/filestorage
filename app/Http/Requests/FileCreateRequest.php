<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class FileCreateRequest
 */
class FileCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'mimes:' . config('app.settings.allowed_extensions'),
                'max:' . config('app.settings.max_file_size'),
            ],
        ];
    }
}
