<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionPointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'latitude'        => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude'       => $this->longitude !== null ? (float) $this->longitude : null,
            'address'         => $this->address,
            'operating_hours' => $this->operating_hours,
            'phone'           => $this->phone,
            'verified'        => (bool) ($this->verified ?? false),
            'distance'        => $this->when(isset($this->distance), function () {
                // se vindo da query Haversine, normalmente em metros
                return (float) $this->distance;
            }),
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),
            'created_at' => optional($this->created_at)?->toDateTimeString(),
            'updated_at' => optional($this->updated_at)?->toDateTimeString(),
        ];
    }
}
