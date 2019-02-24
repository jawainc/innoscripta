<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name' => $this->name,
            'customerNumber' => $this->customerNumber,
            'cost' => $this->cost,
            'street' => $this->street,
            'invoices' => CompanyInvoicesResource::collection($this->invoices)
        ];
    }
}
