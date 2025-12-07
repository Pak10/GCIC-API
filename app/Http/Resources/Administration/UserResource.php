<?php

namespace App\Http\Resources\Administration;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Administration\UserDetailResource;
use App\Http\Resources\Administration\AccountResource;


class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'category' => $this->category,
        ];
    }
}
