<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

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
	 * @return BelongsToMany
	 */
	public function tags(): BelongsToMany
	{
		return $this->belongsToMany(Tag::class);
	}

	/**
	 * @return BelongsTo
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
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

	/**
	 * @return BelongsTo
	 */
	public function access(): BelongsTo
	{
		return $this->belongsTo(Access::class, 'id', 'todo_list_id');
	}
}
