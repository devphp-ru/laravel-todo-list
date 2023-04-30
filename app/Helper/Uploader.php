<?php

namespace App\Helper;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Uploader
{
	/**
	 * Сохранить изображение в хранилище (папку)
	 * @param $request
	 * @return string|null
	 */
	public static function upload($request): ?string
	{
		if ($request->has('file')) {
			$path = 'public/images/';
			$ext = $request->file('file')->getClientOriginalExtension();
			$filename = Str::random(50) . '.' . $ext;
			$request->file('file')->move(Storage::path($path) . 'origin/', $filename);
			$thumbnail = Image::make(Storage::path($path) . 'origin/' . $filename);

			self::makeDirectory('/public/images/thumbnail');

			$thumbnail->fit(150, 150);
			$thumbnail->save(Storage::path($path) . 'thumbnail/' . $filename);
		}

		return $filename ?? null;
	}

	/**
	 * Удалить изображение из хранилища (папки)
	 *
	 * @param $item
	 * @return bool
	 */
	public static function remove($item): bool
	{
		if(Storage::exists("public/images/origin/{$item->image}")) {
			Storage::delete([
				"public/images/origin/{$item->image}",
				"public/images/thumbnail/{$item->image}",
			]);

			return true;
		}

		return false;
	}

	/**
	 * Создать директорию
	 *
	 * @param string $path
	 */
	private static function makeDirectory(string $path): void
	{
		if(!Storage::exists(Storage::url('images/thumbnail'))) {
			Storage::makeDirectory($path, 0775, true);
		}
	}
}
