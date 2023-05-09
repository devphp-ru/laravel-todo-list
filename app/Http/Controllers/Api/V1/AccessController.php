<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccessRequest;
use App\Http\Resources\AccessResource;
use App\Services\AccessService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AccessController extends Controller
{
	/** @var AccessService  */
	private AccessService $accessService;

	/**
	 * AccessController constructor.
	 *
	 * @param AccessService $accessService
	 */
	public function __construct(AccessService $accessService)
	{
		$this->accessService = $accessService;
	}

	/**
	 * Получить пользователей которые имеют доступ к делу
	 *
	 * @param int $id
	 * @return JsonResponse
	 */
	public function index(int $id): JsonResponse
	{
		$access = $this->accessService->find($id);

		return response()->json([
			'status' => true,
			'items' => AccessResource::collection($access),
		])->setStatusCode(Response::HTTP_OK);
	}

	/**
	 * Сохранить модель в хранилище
	 *
	 * @param StoreAccessRequest $request
	 * @return JsonResponse
	 */
    public function save(StoreAccessRequest $request): JsonResponse
	{
		if ($this->accessService->check($request)) {
			$this->accessService->delete($request);
		}

		$result = $this->accessService->create($request);

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
