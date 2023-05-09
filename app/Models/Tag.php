<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * @package App\Models
 * @mixin Builder
 */
class Tag extends Model
{
    use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'user_id',
		'name',
	];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Проверить существует ли тег
	 *
	 * @param int $userId
	 * @param string $value
	 * @return bool
	 */
	public static function isExists(int $userId, string $value): bool
	{
		return self::where('user_id', $userId)->where(function ($query) use ($value) {
			$query->orWhere('id', $value)->orWhere('name', $value);
		})->exists();
	}

	/**
	 * Сохарнить новую модель в хранилище
	 *
	 * @param int $userId
	 * @param string $value
	 * @return int
	 */
	public static function newSave(int $userId, string $value): int
	{
		return Tag::create([
			'user_id' => $userId,
			'name' => $value,
		])->id;
	}
}
