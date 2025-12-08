<?php

namespace App\Http\Resources\Administration;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Administration\UserDetailResource;
use App\Http\Resources\Administration\AccountResource;
use App\Http\Resources\Administration\UserStatusResource;
use App\Http\Resources\Administration\RoleResource;


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
            'user_status_id' => $this->user_status_id,
            'account_approved' => !empty($this->approved_by) ? true : false,
            'status' => new UserStatusResource($this->whenLoaded('userStatus')),
            'details' => new UserDetailResource($this->whenLoaded('details')),
            'permissions' => $this->getAllPermissions()->map(function($permission){
                return [
                    'permission' => $permission['name']
                ];
            }),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'accounts' => AccountResource::collection($this->whenLoaded('accounts')),
        ];
    }
}
