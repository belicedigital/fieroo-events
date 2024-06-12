<?php

namespace Fieroo\Events\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Fieroo\Events\Models\CouponUser;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'percentage',
        'is_active',
    ];

    protected $casts = [
        'code' => 'string',
        'percentage' => 'integer',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->hasOne(CouponUser::class);
    }
}
