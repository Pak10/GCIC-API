<?php

namespace App\Http\Resources\Administration;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Administration\PermissionResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return 
        [

            'id' => $this->id,
            'role' => $this->name,
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
        ];
    }
}
