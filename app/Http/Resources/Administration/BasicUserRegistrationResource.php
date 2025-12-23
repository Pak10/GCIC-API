<?php

namespace App\Http\Resources\Administration;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BasicUserRegistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'registration_reference' => $this->registration_reference,
            'category' => $this->category,
            'self_registration' => $this->self_registration,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
