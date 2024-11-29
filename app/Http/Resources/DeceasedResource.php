<?php

namespace App\Http\Resources;

use App\Models\Deceased;
use Illuminate\Http\Resources\Json\JsonResource;

class DeceasedResource extends JsonResource
{
    public static $wrap = 'deceased';

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
            'site_id' => $this->site_id,
            'obitus_site_id' => $this->obitus_site_id,
            'name' => $this->name,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'date_of_birth' => $this->date_of_birth,
            'date_of_death' => $this->date_of_death,
            'date_of_service' => $this->date_of_service,
            'code' => $this->code,
            'landing_code' => $this->landing_code,
            'link_emailed' => $this->link_emailed,
            'link_printed' => $this->link_printed,
            'memorialisation_link' => Deceased::shopRoute($this->code, $this->firstname, $this->lastname),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'date_of_cremation' => $this->date_of_cremation,
            'cremation_number' => $this->cremation_number,
            'funeral_director' => $this->funeral_director,
            'site' => $this->site,
            'contact_firstname' => $this->contact_firstname,
            'contact_lastname' => $this->contact_lastname,
            'contact_email' => $this->contact_email,
        ];
    }
}
