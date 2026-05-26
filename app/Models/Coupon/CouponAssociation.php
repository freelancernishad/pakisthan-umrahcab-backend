<?php

namespace App\Models\Coupon;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CouponAssociation extends Model
{
    use HasFactory;

    protected $fillable = ['coupon_id', 'item_id', 'item_type'];

    // Define inverse relationships for each type
    public function user()
    {
        return $this->belongsTo(User::class, 'item_id');
    }

    // public function package()
    // {
    //     return $this->belongsTo(Package::class, 'item_id');
    // }

    // public function service()
    // {
    //     return $this->belongsTo(Service::class, 'item_id');
    // }
}
