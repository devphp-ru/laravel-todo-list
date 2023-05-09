<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TodoList;
use App\Services\TodoListService;
use Illuminate\Http\Request;

class FilterController extends Controller
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
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
		$userId = $request->input('current_user_id');
		$items = $this->todoService->getAllWithPaginate($request, TodoList::PER_PAGE, $userId);

		return view('todolist.blocks._todo_lists', [
			'paginator' => $items,
			'userId' => $userId,
		])->render();
    }
}
