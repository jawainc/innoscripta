<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyInvoicesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'purpose' => $this->purpose,
            'billNumber' => $this->billNumber,
            'amount' => $this->amount,
            'date' => $this->date->format('Y-m-d'),
            'dateShow' => false,
            'dateDisplay' => $this->date->format('m-d-Y'),
            'share' => 0
        ];
    }
}
