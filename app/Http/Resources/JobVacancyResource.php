<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobVacancyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'author_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}
