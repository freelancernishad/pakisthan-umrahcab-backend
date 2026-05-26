<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaFileCollection extends ResourceCollection
{
    public $collects = MediaFileResource::class;

    public function toArray($request)
    {
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->collection,
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'last_page_url' => $this->url($this->lastPage()),
            'links' => $this->linkCollection(),
            'next_page_url' => $this->nextPageUrl(),
            'path' => $this->path(),
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }

    private function linkCollection()
    {
        return collect(range(1, $this->lastPage()))->map(function ($page) {
            return [
                'url' => $this->url($page),
                'label' => (string) $page,
                'active' => $page == $this->currentPage(),
            ];
        });
    }
}
