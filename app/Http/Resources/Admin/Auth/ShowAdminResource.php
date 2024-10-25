<?php

namespace App\Http\Resources\Admin\Auth;

use App\Http\Resources\Admin\PositionStatus\ShowPositionStatusResource;
use App\Http\Resources\Admin\Role\ShowRoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'roles' => new ShowRoleResource($this->roles),
            'position_status' => new ShowPositionStatusResource($this->positionStatus)
        ];
    }
}
