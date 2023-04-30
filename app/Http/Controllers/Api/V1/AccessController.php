<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Access;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccessController extends Controller
{
	/**
	 * Добавить доступ пользователю к делу
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
    public function save(Request $request): JsonResponse
	{
		$access = new Access();
		if ($access->check($request)) {
			$access->where([
				'todo_list_id' => $request->input('todo_list_id'),
				'user_id' => $request->input('user_id'),
			])->delete();
		}

		$result = $access->create($request->only($access->getFillable()));

		if (!$result) {
			return response()->json([
				'status' => false,
			])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		return response()->json([
			'status' => true,
		])->setSTatusCode(Response::HTTP_NO_CONTENT);
	}
}
