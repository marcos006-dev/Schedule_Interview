<?php

namespace App\Http\Controllers;

use App\Interfaces\ReservationRepositoryInterface;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    private ReservationRepositoryInterface $reservationRepository;

    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function getByDay(Request $request)
    {
        $date = $request->input('date');

        return $this->reservationRepository->getByDay($date);

    }

    public function getByRange(Request $request)
    {

        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        return $this->reservationRepository->getByRange($dateStart, $dateEnd);
    }

    public function getByRoute(Request $request)
    {
        $routeId = $request->input('route_id');

        return $this->reservationRepository->getByRoute($routeId);
    }

}
