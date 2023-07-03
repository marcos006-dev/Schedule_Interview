<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    use \Sushi\Sushi;

    protected $rows = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->rows = loadJson('../data/user_plans.json')['user_plans'];
    }

    protected function sushiShouldCache()
    {
        return false;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
