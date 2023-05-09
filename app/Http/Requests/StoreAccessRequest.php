<?php

namespace App\Http\Requests;

use App\Services\Requests\ApiRequest;

class StoreAccessRequest extends ApiRequest
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
     * @return array
     */
    public function rules()
    {
		return [
			'todo_list_id' => 'required',
			'user_id' => 'required',
		];
    }
}
