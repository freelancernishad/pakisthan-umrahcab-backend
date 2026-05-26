<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaFileResource extends JsonResource
{
    public function toArray($request)
    {
        // Group versions by label
        $versions = [];
        $thumbnail = null;

        foreach ($this->versions as $version) {
            $data = [
                'url' => $version->url,
                'size' => $version->size,
                'size_type' => $version->size_type,
                'type' => $version->type,
                'dimensions' => $version->dimensions,
            ];

            $versions[$version->label] = $data;

            if ($version->label === 'thumbnail') {
                $thumbnail = $data;
            }
        }

        // Determine uploader
        if ($this->uploadedByUser) {
            $uploadedType = 'user';
            $uploader = [
                'id' => $this->uploadedByUser->id,
                'name' => $this->uploadedByUser->name,
                'email' => $this->uploadedByUser->email,
            ];
        } elseif ($this->uploadedByAdmin) {
            $uploadedType = 'admin';
            $uploader = [
                'id' => $this->uploadedByAdmin->id,
                'name' => $this->uploadedByAdmin->name,
                'email' => $this->uploadedByAdmin->email,
            ];
        } else {
            $uploadedType = 'others';
            $uploader = null;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'original_url' => $this->original_url,
            'thumbnail' => $thumbnail,
            'versions' => $versions,
            'uploaded' => [
                'type' => $uploadedType,
                'details' => $uploader,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
