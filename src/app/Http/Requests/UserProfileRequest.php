<?php

namespace App\Http\Requests;

use App\Models\UserProfile;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Auth;

class UserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:191',
            'postcode' => 'required|digits:7|regex:/^[0-9]+$/',
            'address' => 'required|string|max:191',
            'building' => 'nullable|string|max:191',
            'image' => 'nullable|string|max:512',
        ];
    }
}
