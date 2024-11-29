<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Lab404\Impersonate\Services\ImpersonateManager;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'roles' => $this->roles,
            'canImpersonate' => $this->canImpersonate(),
            'canBeImpersonated' => $this->canBeImpersonated(),
            'isImpersonating' => app(ImpersonateManager::class)->isImpersonating(),
        ];
    }
}
