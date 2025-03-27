<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use function Ramsey\Uuid\v1;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description,
            'language' => $this->language,
            'dimensions' => $this->dimensions,
        ];

        if ($request->user()) {
            $data['borrowed'] = $this->borrowed;
        }

        return $data;
    }
}
