<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this -> id,
            'pin' => $this -> pin,
            'room_owner' => $this -> room_owner,
            'status_game' => $this -> status_game,
            'question_current' => $this -> question_current,
            'test_id' => $this -> test_id
        ];
    }
}
