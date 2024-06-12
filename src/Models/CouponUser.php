<?php

namespace Fieroo\Events\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Fieroo\Bootstrapper\Models\User;
use Fieroo\Events\Models\Coupon;

class CouponUser extends Model
{
    use HasFactory;

    public $table = 'user_coupons';

    protected $fillable = [
        'user_id',
        'coupon_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'coupon_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
