<?php

namespace App\Http\Resources\Administration;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Administration\UserStatusResource;
use App\Http\Resources\Administration\RoleResource;
use App\Http\Resources\Administration\AccountResource;

class BasicUserResource extends JsonResource
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
            'name'=> $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'category' => $this->category,
            'account_approved' => !empty($this->approved_by) ? true : false,
            'status' => new UserStatusResource($this->whenLoaded('userStatus')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'accounts' => AccountResource::collection($this->whenLoaded('accounts')),
        ];
    }
}
