<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'key' => $this->key,
            'secret' => $this->secret,
            'messages' => $this->messages,
            'statistics' => $this->statistics,
            'pinned' => false,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}