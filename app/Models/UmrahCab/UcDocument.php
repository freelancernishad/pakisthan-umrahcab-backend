<?php

namespace App\Models\UmrahCab;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UcDocument extends Model
{
    use HasFactory;

    protected $table = 'uc_documents';

    protected $fillable = [
        'customer_id',
        'title',
        'file_path',
        'file_type',
        'uploaded_by'
    ];

    public function customer()
    {
        return $this->belongsTo(UcCustomer::class, 'customer_id');
    }
}
