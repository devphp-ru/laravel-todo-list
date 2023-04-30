<?php

namespace App\Services\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

/**
 * Class ApiRequest
 * @package App\Services\Requests
 */
class ApiRequest extends FormRequest
{
	/**
	 * Handle a failed validation attempt.
	 *
	 * @param \Illuminate\Contracts\Validation\Validator $validator
	 * @return void
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	protected function failedValidation(Validator $validator): void
	{
		$errors = (new ValidationException($validator))->errors();

		throw new HttpResponseException(response()->json([
			'status' => false,
			'errors' => $errors,
		])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY));
	}
}
