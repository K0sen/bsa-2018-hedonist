<?php

namespace Hedonist\Entities\Place;

use Illuminate\Database\Eloquent\Model;

class PlaceWorkTime extends Model
{
    const MO = 'mo';
    const TU = 'tu';
    const WE = 'we';
    const TH = 'th';
    const FR = 'fr';
    const SA = 'sa';
    const SU = 'su';

    public $timestamps = false;

    protected $table = 'place_worktime';

    protected $fillable = [
        'place_id',
        'day_code',
        'start_time',
        'end_time'
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
