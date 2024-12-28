<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'latitude',
        'longitude',
        'address',
        'city',
        'state',
        'folder_name',
        'show_on_countdown',
        'is_trip',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'show_on_countdown' => 'boolean',
        'is_trip' => 'boolean',
    ];

    protected $appends = ['countdown'];

    public function getCountdownAttribute()
    {
        if ((new \Carbon\Carbon($this->start_date))->isPast()) {
            return null;
        }
        return (new \Carbon\Carbon($this->start_date))->diffForHumans(now(), \Carbon\CarbonInterface::DIFF_ABSOLUTE, true, 6);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class);
    }
}
