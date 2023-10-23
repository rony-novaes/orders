<?php

namespace App\Http\Requests;

use App\Exceptions\ApiAuthAuthorization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class CustomerStoreRequest extends FormRequest
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
            'name.required' => 'Name is required',
            'name.min' => 'The name must not have at least :min characters.',
            "email" => "Please provide a valid email",
            "email.unique" => "Email already registered", 
            "birth_date" => "Please provide a valid date of birth"
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
            "name"  => "required|min:5",
            "email" => "required|email|unique:customers",
            "password" => "string",
            "birth_date" => "date",
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            Response::json([
                'message' => 'Check all data for customer creation',
                'errors' => $validator->errors(),
            ], HttpResponse::HTTP_PRECONDITION_FAILED)
        );
    }

    protected function failedAuthorization()
    {
        return new HttpResponseException(Response::json(['message' => "action denied"], HttpResponse::HTTP_FORBIDDEN)) ;
    }

}
