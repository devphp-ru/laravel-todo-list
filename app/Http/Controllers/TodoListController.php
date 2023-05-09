<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Services\TagService;
use App\Services\TodoListService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TodoListController extends Controller
{
	/** @var TodoListService  */
	private TodoListService $todoService;

	/** @var TagService  */
	private TagService $tagService;

	/** @var UserService  */
	private UserService $userService;

	/**
	 * TodoListController constructor.
	 *
	 * @param TodoListService $todoService
	 * @param TagService $tagService
	 * @param UserService $userService
	 */
	public function __construct(
		TodoListService $todoService,
		TagService $tagService,
		UserService $userService
	) {
		$this->todoService = $todoService;
		$this->tagService = $tagService;
		$this->userService = $userService;
	}

	/**
	 * Показать страницу дел
	 *
	 * @param Request $request
	 * @return View
	 */
    public function index(Request $request): View
	{
		$userId = Auth::user()->id;

		$items = $this->todoService->getAllWithPaginate($request, TodoList::PER_PAGE, $userId);
		$users = $this->userService->getUsersExceptId($userId);
		$tags = $this->tagService->getForFilter();

		$title = 'Список дел';

		return view('todolist.index', [
			'title' => $title,
			'paginator' => $items,
			'users' => $users,
			'tags' => $tags,
			'userId' => $userId,
		]);
	}
}
