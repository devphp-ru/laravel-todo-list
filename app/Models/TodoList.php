<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

/**
 * Class TodoList
 * @package App\Models
 * @mixin Builder
 */
class TodoList extends Model
{
    use HasFactory;

    public const PER_PAGE = 2;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'user_id',
		'text',
		'image',
	];

	/**
	 * Получить все теги
	 *
	 * @return BelongsToMany
	 */
	public function tags(): BelongsToMany
	{
		return $this->belongsToMany(Tag::class);
	}

	/**
	 * Получить пользователя
	 *
	 * @return BelongsTo
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * Получить доступ к делу
	 *
	 * @return BelongsTo
	 */
	public function access(): BelongsTo
	{
		return $this->belongsTo(Access::class, 'id', 'todo_list_id');
	}

	/**
	 * Получить оригинал изображения
	 *
	 * @return string|null
	 */
	public function getImage(): ?string
	{
		return ($this->image) ? Storage::url("images/origin/{$this->image}") : null;
	}

	/**
	 * Получить превью изображения
	 *
	 * @return string|null
	 */
	public function getMiniImage(): ?string
	{
		return ($this->image) ? Storage::url("images/thumbnail/{$this->image}") : null;
	}
}
