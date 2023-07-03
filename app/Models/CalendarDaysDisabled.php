<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarDaysDisabled extends Model
{
    use \Sushi\Sushi;

    protected $rows = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // dd(loadJson('../data/calendar_days_disabled.json')['calendar_days_disabled ']);
        $this->rows = loadJson('../data/calendar_days_disabled.json')['calendar_days_disabled '];
    }

    protected function sushiShouldCache()
    {
        return false;
    }

    public function calendar()
    {
        return $this->belongsTo(Calendar::class, 'calendar_id', 'id');
    }
}
