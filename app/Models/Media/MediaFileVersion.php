<?php

namespace App\Models\Media;

use Illuminate\Database\Eloquent\Model;

class MediaFileVersion extends Model
{
    protected $fillable = ['media_file_id', 'label', 'url', 'size', 'size_type', 'type', 'dimensions'];

    public function mediaFile()
    {
        return $this->belongsTo(MediaFile::class);
    }
}
