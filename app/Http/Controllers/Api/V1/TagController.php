<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TagController extends Controller
{
	/** @var TagService  */
	private TagService $service;

	/**
	 * TagController constructor.
	 *
	 * @param TagService $service
	 */
	public function __construct(TagService $service)
	{
		$this->service = $service;
	}

	/**
	 * Получить теги пользователя для селекта
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function forSelect(Request $request): JsonResponse
	{
		return response()->json([
			'status' => true,
			'tags' => $this->service->getForSelect($request->input('user_id')),
		])->setStatusCode(Response::HTTP_OK);
	}

	/**
	 * Получить теги пользователя для фильтра
	 *
	 * @return JsonResponse
	 */
	public function forFilter(): JsonResponse
	{
		return response()->json([
			'status' => true,
			'tags' => $this->service->getForFilter(),
		])->setStatusCode(Response::HTTP_OK);
	}
}
