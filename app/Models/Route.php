<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{

    use \Sushi\Sushi;

    protected $rows = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->rows = loadJson('../data/routes.json')['routes'];
    }

    protected function sushiShouldCache()
    {
        return false;
    }

    public function routeData()
    {
        return $this->hasOne(RouteData::class, 'route_id', 'id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'route_id', 'id');
    }

    public function service()
    {
        return $this->hasMany(Service::class, 'external_route_id', 'external_id');
    }
}
