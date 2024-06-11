<?php

namespace Fieroo\Events\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Fieroo\Bootstrapper\Models\User;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'percentage',
        'user_id',
        'is_active'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
