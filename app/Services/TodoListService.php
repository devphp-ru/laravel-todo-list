<?php

namespace App\Services;

use App\Helper\Uploader;
use App\Http\Requests\UpdateTodoListRequest;
use App\Models\Access;
use App\Models\Tag;
use App\Models\TodoList;
use App\Models\User;
use App\Services\Contracts\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TodoListService implements Service
{
	/**
	 * Получить дела с разбивкой на страницы
	 *
	 * @param Request $request
	 * @param int $perPage
	 * @param int $userId
	 * @return LengthAwarePaginator
	 */
	public function getAllWithPaginate(Request $request, int $perPage, int $userId): LengthAwarePaginator
	{
		$builder = TodoList::with('tags', 'user', 'access');
		$builder = $this->search($request, $builder);
		$builder->where('user_id', $userId);

		$todoIds = $this->getAvailableCases($userId);
		if ($todoIds->isNotEmpty()) {
			$builder->orWhere(function ($query) use ($todoIds) {
				$query->whereIn('id', $todoIds);
			});
		}

		return $builder
			->orderByDesc('id')
			->paginate($perPage)
			->withQueryString();
	}

	/**
	 * Поиск дел
	 *
	 * @param Request $request
	 * @param Builder $builder
	 * @return Builder
	 */
	private function search(Request $request, Builder $builder): Builder
	{
		if ($request->filled('query')) {
			$query = trim($request->input('query'));
			$search = preg_replace('#[^0-9a-zA-ZА-Яа-яёЁ]#u', ' ', $query);
			$search = preg_replace('#\s+#u', ' ', $search);
			$search = mb_strtolower(trim($search));
			$q = "%{$search}%";
			$builder->where('text', 'LIKE', $q);
		}

		return $builder;
	}

	/**
	 * Получить чужие дела
	 *
	 * @param int $userId
	 * @return Collection
	 */
	private function getAvailableCases(int $userId): Collection
	{
		return Access::where([
			['user_id', $userId,],
			['action', '!=', '0',]
		])->get()->pluck('todo_list_id');
	}

	/**
	 * Создать модель в хранишище
	 *
	 * @param FormRequest $request
	 * @return TodoList|null
	 */
	public function create(FormRequest $request): ?TodoList
	{
		$request->offsetSet('image', Uploader::upload($request));

		$item = new TodoList();
		$result = $item->create($request->only($item->getFillable()));

		$this->makeTags($request, $result);

		return $result;
	}

	/**
	 * Обновить модель в хранилище
	 *
	 * @param UpdateTodoListRequest $request
	 * @param TodoList $todoList
	 * @return TodoList
	 */
	public function update(UpdateTodoListRequest $request, TodoList $todoList): TodoList
	{
		if ($request->has('file')) {
			Uploader::remove($todoList);
			$request->offsetSet('image', Uploader::upload($request));
		}

		$todoList->update($request->only($todoList->getFillable()));

		$this->makeTags($request, $todoList);

		return $todoList;
	}

	/**
	 * Удалить модель из хранилища
	 *
	 * @param TodoList $item
	 * @return bool
	 */
	public function destroy(TodoList $item): bool
	{
		$result = $item->delete();

		if ($result) {
			DB::table('tag_todo_list')->where('todo_list_id', $item->id)->delete();
			Access::where('todo_list_id', $item->id)->delete();
			Uploader::remove($item);
		}

		return $result;
	}

	/**
	 * Сохранить теги и привязать к делу
	 *
	 * @param FormRequest $request
	 * @param TodoList $item
	 */
	private function makeTags(FormRequest $request, TodoList $item): void
	{
		$result = [];

		if ($request->tags) {
			foreach ($request->tags as $value) {
				if (is_numeric($value)) {
					$result[] = Tag::where('id', $value)->value('id');
				} else {
					$result[] = Tag::create(['name' => $value])->id;
				}
			}
		}

		$item->tags()->sync($result);
	}

	/**
	 * Удалить изображение и путь из хранилища
	 *
	 * @param TodoList $item
	 * @return bool
	 */
	public function removeImage(TodoList $item): bool
	{
		Uploader::remove($item);

		return $item->update(['image' => null]);
	}

	/**
	 * Получить пользователей, кроме id
	 *
	 * @param int $id
	 * @return Collection
	 */
	public function getUsersExceptId(int $id): Collection
	{
		return User::getExceptId($id);
	}
}
