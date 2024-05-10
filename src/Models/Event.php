<?php

namespace Fieroo\Events\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Fieroo\Payment\Models\Payment;
use Fieroo\Bootstrapper\Models\User;
use EventStand;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start',
        'end',
        'subscription_date_open_until',
        'is_published'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Payment::class);
    }

    public function stands()
    {
        return $this->hasMany(EventStand::class);
    }
}
