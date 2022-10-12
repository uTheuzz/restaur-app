<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginationResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $lengthAwarePaginator = $this->resource[0];
        $items = $this->resource[1];
        return [
            'current_page' => $lengthAwarePaginator->currentPage(),
            'data' => $items,
            'first_page_url' => $lengthAwarePaginator->url(1),
            'from' => $lengthAwarePaginator->firstItem(),
            'last_page' => $lengthAwarePaginator->lastPage(),
            'last_page_url' => $lengthAwarePaginator->url($lengthAwarePaginator->lastPage()),
            'links' => $lengthAwarePaginator->linkCollection()->toArray(),
            'next_page_url' => $lengthAwarePaginator->nextPageUrl(),
            'path' => $lengthAwarePaginator->path(),
            'per_page' => $lengthAwarePaginator->perPage(),
            'prev_page_url' => $lengthAwarePaginator->previousPageUrl(),
            'to' => $lengthAwarePaginator->lastItem(),
            'total' => $lengthAwarePaginator->total(),
        ];

    }
}
