<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Access
 * @package App\Models
 * @mixin Builder
 */
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

	/**
	 * Получить доступ, связанный с пользователем
	 *
	 * @return BelongsTo
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Получить пользователей у которых есть доступы к делу
	 *
	 * @return BelongsTo
	 */
	public function users(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Проверить есть ли доступ к делу у пользователя
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
