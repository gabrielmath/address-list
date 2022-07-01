<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'address';


    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'cidade_id'         => $this->city_id,
            'endereco_completo' => $this->full_address,
            'logradouro'        => $this->street,
            'numero'            => $this->number,
            'bairro'            => $this->neighborhood,
            'cidade'            => "{$this->city->name}/{$this->city->uf}",
            //            'cidade'            => new CityResource($this->city),
        ];
    }
}
