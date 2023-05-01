<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Services\TodoListService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TodoListController extends Controller
{
	/** @var TodoListService  */
	private TodoListService $service;

	/**
	 * TodoListController constructor.
	 *
	 * @param TodoListService $service
	 */
	public function __construct(TodoListService $service)
	{
		$this->service = $service;
	}

	/**
	 * Показать страницу дел
	 *
	 * @param Request $request
	 * @return View
	 */
    public function index(Request $request): View
	{
		$items = $this->service->getAllWithPaginate($request, TodoList::PER_PAGE, Auth::user()->id);
		$users = $this->service->getUsersExceptId(Auth::user()->id);

		$title = 'Список дел';

		return view('todolist.index', [
			'title' => $title,
			'paginator' => $items,
			'users' => $users,
			'userId' => Auth::user()->id,
		]);
	}
}
