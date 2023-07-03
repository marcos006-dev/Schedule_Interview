<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ReservationRepositoryInterface
{
    public function getByDay(String $date);
    public function getByRange(String $dateStart, String $dateEnd);
    public function getByRoute(Request $request);
}