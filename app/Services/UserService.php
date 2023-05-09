<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\Service;
use Illuminate\Support\Collection;

class UserService implements Service
{
	/** @var User  */
	private User $item;

	/**
	 * UserService constructor.
	 *
	 * @param User $user
	 */
	public function __construct(User $user)
	{
		$this->item = $user;
	}

	/**
	 * Получить пользователей, кроме id
	 *
	 * @param int $id
	 * @return Collection
	 */
	public function getUsersExceptId(int $id): Collection
	{
		return $this->item::getExceptId($id);
	}
}
