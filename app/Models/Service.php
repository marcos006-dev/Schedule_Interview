<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    use \Sushi\Sushi;

    protected $rows = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->rows = loadJson('../data/services.json')['services'];
    }

    protected function sushiShouldCache()
    {
        return false;
    }

    public function reservations()
    {
        return $this->hasMany(Route::class, 'external_route_id', 'external_id');
    }

}
