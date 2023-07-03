<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use \Sushi\Sushi;

    protected $rows = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->rows = loadJson('../data/users.json')['users'];
    }

    protected function sushiShouldCache()
    {
        return false;
    }

    public function userPlan()
    {
        return $this->hasOne(UserPlan::class, 'user_id', 'id');
    }

}
