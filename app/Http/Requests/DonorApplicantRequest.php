<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class DonorApplicantRequest extends FormRequest
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
            'name' => 'required|string',
            'nik' => 'required|string|max:16',
            'dob' => 'required|date|date_format:Y-m-d',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'body_mass' => 'required|numeric',
            'hemoglobin_level' => 'required|string',
            'blood_type' => 'required|string|max:2',
            'blood_pressure' => 'required|string',
            'medical_conditions' => 'string',
            'schedule_uuid' => 'required|string',
        ];
    }

    public function failedValidation(Validator $validator) 
    {
        throw new HttpResponseException(
            response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Validation Errors',
                'errors' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST)
        );
    }
}
