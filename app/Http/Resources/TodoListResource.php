<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TodoListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
        	'id' => $this->id,
			'user_id' => $this->user_id,
			'text' => $this->text,
			'image' => $this->getImage(),
			'mini_image' => $this->getMiniImage(),
			'tags' => $this->tags()->get()->pluck('name', 'id'),
		];
    }
}
