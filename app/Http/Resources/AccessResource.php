<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccessResource extends JsonResource
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
			'todo_list_id' => $this->todo_list_id,
			'user_id' => $this->user_id,
			'action' => $this->action,
			'user' => $this->user,
		];
    }
}
