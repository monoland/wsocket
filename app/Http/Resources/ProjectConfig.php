<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectConfig extends JsonResource
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
            'path' => null,
            'host' => env('WSC_URL'),
            'port' => env('WSC_PORT'),
            'capacity' => null,
            'enable_client_messages' => $this->messages,
            'enable_statistics' => $this->statistics,
        ];
    }
}
