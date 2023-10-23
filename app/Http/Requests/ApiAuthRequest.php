<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response as HttpResponse;

use Illuminate\Support\Facades\Response;

class ApiAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            "email" => "enter your email",
            "password" => "enter your password",
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "email"  => "required|min:5|email",
            "password" => "required|string",
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            Response::json([
                'message' => 'Invalid email and/or password',
                'errors' => $validator->errors(),
            ], HttpResponse::HTTP_PRECONDITION_FAILED)
        );
    }
}
