<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TorrentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'hash' => $this->hash,
        ];
    }
}