<?php

namespace App\Services;

use App\Models\Tag;
use App\Services\Contracts\Service;
use Illuminate\Support\Collection;

class TagService implements Service
{
	/** @var Tag  */
	private Tag $item;

	/**
	 * TagService constructor.
	 *
	 * @param Tag $tag
	 */
	public function __construct(Tag $tag)
	{
		$this->item = $tag;
	}

	/**
	 * Получить теги пользователя для селекта
	 *
	 * @param int $userId
	 * @return Collection
	 */
	public function getForSelect(int $userId): Collection
	{
		return $this->item->where('user_id', $userId)->pluck('name', 'id');
	}

	/**
	 * Получить теги пользователя для фильтра
	 * (которые привязаны к делу)
	 *
	 * @return Collection
	 */
	public function getForFilter(): Collection
	{
		return $this->item::whereIn('id', function ($query) {
			$query->select('tag_id')->from('tag_todo_list')->distinct()->get();
		})->pluck('name', 'id');
	}
}
