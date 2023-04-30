<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'todo_list_id',
		'user_id',
		'action',
	];

	public function user(): HasOne
	{
		return $this->hasOne(User::class);
	}

	/**
	 * Проверить есть ли доступ к делу
	 *
	 * @param Request $request
	 * @return bool
	 */
	public function check(Request $request): bool
	{
		return self::where([
			'todo_list_id' => $request->input('todo_list_id'),
			'user_id' => $request->input('user_id'),
		])->exists();
	}
}
