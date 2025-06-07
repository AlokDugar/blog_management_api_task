<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'author_name' => $this->author->name,
            'category_name' => $this->category->name,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
