<?php

namespace App\Http\Requests\Dashboard\TopicRejectType;

use Illuminate\Foundation\Http\FormRequest;

class TopicRejectTypeUpdateRequest extends FormRequest
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
            'title' => 'string|max:255',
            'description' => 'string',
        ];
    }
}
