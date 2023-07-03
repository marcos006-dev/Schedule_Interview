<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteData extends Model
{
    use \Sushi\Sushi;

    protected $rows = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->rows = loadJson('../data/route_data.json')['routes_data'];
    }

    protected function sushiShouldCache()
    {
        return false;
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    public function calendar()
    {
        return $this->belongsTo(Calendar::class, 'calendar_id', 'id');
    }
}
