<?php

namespace App\Models\Media;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    protected $fillable = ['name', 'original_url','uploaded_by_user_id',
        'uploaded_by_admin_id',        'uploader_id',
        'uploader_type',];

    public function versions()
    {
        return $this->hasMany(MediaFileVersion::class);
    }

        public function uploadedByUser()
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function uploadedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by_admin_id');
    }

    // Polymorphic relation for uploader (Admin/User/Hotel)
    public function uploader()
    {
        return $this->morphTo();
    }

}
