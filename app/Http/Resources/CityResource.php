<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                          => $this->id,
            'ibge_id'                     => $this->ibge_id,
            'cidade'                      => $this->name,
            'uf_ibge_id'                  => $this->uf_ibge_id,
            'sigla_uf'                    => $this->uf,
            'decricao_uf'                 => $this->uf_full_name,
            'total_enderecos_cadastrados' => $this->addresses()->count()
        ];
    }
}
