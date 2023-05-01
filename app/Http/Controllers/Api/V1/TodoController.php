<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoListRequest;
use App\Http\Requests\UpdateTodoListRequest;
use App\Http\Resources\TodoListResource;
use App\Models\Tag;
use App\Models\TodoList;
use App\Services\TodoListService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoController extends Controller
{
	/** @var TodoListService  */
	private TodoListService $service;

	/**
	 * TodoController constructor.
	 *
	 * @param TodoListService $service
	 */
	public function __construct(TodoListService $service)
	{
		$this->service = $service;
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
			$items = $this->service->getAllWithPaginate($request, TodoList::PER_PAGE, $request->input('current_user_id'));

			return view('todolist.blocks._todo_lists', [
				'paginator' => $items,
				'userId' => $request->input('current_user_id'),
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
		$this->service->create($request);

		$items = $this->service->getAllWithPaginate($request, TodoList::PER_PAGE, $request->input('current_user_id'));

		return view('todolist.blocks._todo_lists', [
			'paginator' => $items,
			'userId' => $request->input('current_user_id'),
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
		$this->service->update($request, $todoList);

		$items = $this->service->getAllWithPaginate($request, TodoList::PER_PAGE, $request->input('current_user_id'));

		return view('todolist.blocks._todo_lists', [
			'paginator' => $items,
			'userId' => $request->input('current_user_id'),
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
		$result = $this->service->destroy($todoList);

		if (!$result) {
			return response()->json([
				'status' => false,
			])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		$items = $this->service->getAllWithPaginate($request, TodoList::PER_PAGE, $request->input('current_user_id'));

		return view('todolist.blocks._todo_lists', [
			'paginator' => $items,
			'userId' => $request->input('current_user_id'),
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
		$result = $this->service->removeImage($todoList);

		if (!$result) {
			return response()->json([
				'status' => false,
			])->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		return response()->json([
			'status' => true,
		])->setSTatusCode(Response::HTTP_NO_CONTENT);
	}

	/**
	 * Получить все теги из хранилища
	 *
	 * @return JsonResponse
	 */
	public function tags(): JsonResponse
	{
		return response()->json([
			'status' => true,
			'tags' => Tag::getAll(),
		])->setStatusCode(Response::HTTP_OK);
	}
}
