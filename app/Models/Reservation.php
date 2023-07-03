<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{

    use \Sushi\Sushi;

    protected $rows = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->rows = loadJson('../data/reservations.json')['reservations'];
    }

    protected function sushiShouldCache()
    {
        return false;
    }

    public function userPlan()
    {
        return $this->belongsTo(UserPlan::class, 'user_plan_id', 'id');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }


}
