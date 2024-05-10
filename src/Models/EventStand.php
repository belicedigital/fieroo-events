<?php

namespace Fieroo\Events\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Fieroo\Stands\Models\StandsType;
use Event;

class EventStand extends Model
{
    use HasFactory;

    public $table = 'events_stands';

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'stand_type_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function stand()
    {
        return $this->belongsTo(StandsType::class);
    }
}
