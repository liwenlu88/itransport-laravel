<?php

namespace App\Http\Resources\Admin\Role;

use App\Http\Resources\Admin\Permission\ShowPermissionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'permissions' => ShowPermissionResource::collection($this->resource->permissions),
        ];
    }
}
