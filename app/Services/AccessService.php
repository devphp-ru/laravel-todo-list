<?php

namespace App\Services;

use App\Models\Access;
use App\Services\Contracts\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AccessService implements Service
{
	/** @var Access  */
	private Access $item;

	/**
	 * AccessService constructor.
	 *
	 * @param Access $access
	 */
	public function __construct(Access $access)
	{
		$this->item = $access;
	}

	/**
	 * Получить пользователей которые имеют доступ к делу
	 *
	 * @param int $todoId
	 * @return Collection
	 */
	public function find(int $todoId): Collection
	{
		return $this->item->with('users')->where([
			['todo_list_id', $todoId],
			['action', '!=', 0],
		])->get();
	}

	/**
	 * Проверить есть ли доступ к делу у пользователя
	 *
	 * @param Request $request
	 * @return bool
	 */
	public function check(Request $request): bool
	{
		return $this->item->check($request);
	}

	/**
	 * Создать модель в хранилище
	 *
	 * @param FormRequest $request
	 * @return Access|null
	 */
	public function create(FormRequest $request): ?Access
	{
		return $this->item->create($request->only($this->item->getFillable()));
	}

	/**
	 * Удалить модель из хранилища
	 *
	 * @param Request $request
	 * @return bool
	 */
	public function delete(Request $request): bool
	{
		return $this->item->where([
			'todo_list_id' => $request->input('todo_list_id'),
			'user_id' => $request->input('user_id'),
		])->delete();
	}
}
