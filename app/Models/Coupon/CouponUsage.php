<?php

namespace App\Models\Coupon;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CouponUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id', 'user_id', 'used_at'
    ];

    /**
     * Get the coupon associated with this usage.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the user who used this coupon.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
