<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use \Sushi\Sushi;

    protected $rows = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->rows = loadJson('../data/calendar.json')['calendar '];
    }

    protected function sushiShouldCache()
    {
        return false;
    }

    public function calendarDaysDisabled()
    {
        return $this->hasMany(CalendarDaysDisabled::class, 'calendar_id', 'id');
    }
}
