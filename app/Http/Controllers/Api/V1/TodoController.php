<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoListRequest;
use App\Http\Requests\UpdateTodoListRequest;
use App\Http\Resources\TodoListResource;
use App\Models\TodoList;
use App\Services\TodoListService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoController extends Controller
{
	/** @var TodoListService  */
	private TodoListService $todoService;

	/**
	 * TodoController constructor.
	 *
	 * @param TodoListService $todoService
	 */
	public function __construct(TodoListService $todoService)
	{
		$this->todoService = $todoService;
	}

	/**
	 * Получить модель для редактирования
	 *
	 * @param TodoList $todoList
	 * @return JsonResponse
	 */
	public function show(TodoList $todoList): JsonResponse
	{
		return response()->json([
			'status' => true,
			'item' => new TodoListResource($todoList),
		])->setStatusCode(Response::HTTP_OK);
	}

	/**
	 * Пагинация без перезагружки страницы
	 *
	 * @param Request $request
	 * @return string|null
	 */
	public function paginateAjax(Request $request): ?string
	{
		if ($request->ajax()) {
			$userId = $request->input('current_user_id');
			$items = $this->todoService->getAllWithPaginate($request, TodoList::PER_PAGE, $userId);

			return view('todolist.blocks._todo_lists', [
				'paginator' => $items,
				'userId' => $userId,
			])->render();
		}

		return null;
	}

	/**
	 * Создать модель в хранишище
	 *
	 * @param StoreTodoListRequest $request
	 * @return string
	 */
	public function store(StoreTodoListRequest $request): string
	{
		$this->todoService->create($request);

		$userId = $request->input('current_user_id');
		$items = $this->todoService->getAllWithPaginate($request, TodoList::PER_PAGE, $userId);

		return view('todolist.blocks._todo_lists', [
			'paginator' => $items,
			'userId' => $userId,
		])->render();
	}

	/**
	 * Обновить модель в хранилище
	 *
	 * @param UpdateTodoListRequest $request
	 * @param TodoList $todoList
	 * @return string
	 */
	public function edit(UpdateTodoListRequest $request, TodoList $todoList): string
	{
		$this->todoService->update($request, $todoList);

		$userId = $request->input('current_user_id');
		$items = $this->todoService->getAllWithPaginate($request, TodoList::PER_PAGE, $userId);

		return view('todolist.blocks._todo_lists', [
			'paginator' => $items,
			'userId' => $userId,
		])->render();
	}

	/**
	 * Удалить модель из хранилища
	 *
	 * @param Request $request
	 * @param TodoList $todoList
	 * @return JsonResponse|string
	 */
	public function destroy(Request $request, TodoList $todoList): JsonResponse|string
	{
		$result = $this->todoService->destroy($todoList);

		if (!$result) {
			return response()->json([
				'status' => false,
			])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		$userId = $request->input('current_user_id');
		$items = $this->todoService->getAllWithPaginate($request, TodoList::PER_PAGE, $userId);

		return view('todolist.blocks._todo_lists', [
			'paginator' => $items,
			'userId' => $userId,
		])->render();
	}

	/**
	 * Удалить изображение и путь из хранилища
	 *
	 * @param TodoList $todoList
	 * @return JsonResponse
	 */
	public function removeImage(TodoList $todoList): JsonResponse
	{
		return response()->json([
			'status' => $this->todoService->removeImage($todoList),
		])->setStatusCode(Response::HTTP_OK);
	}
}
