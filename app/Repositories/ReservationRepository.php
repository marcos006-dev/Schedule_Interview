<?php

namespace App\Repositories;

use App\Enums\StatusDateEnum;
use App\Http\Responses\CustomResponse;
use App\Interfaces\ReservationRepositoryInterface;
use App\Models\CalendarDaysDisabled;
use App\Models\Reservation;
use App\Models\RouteData;
use App\Models\Service;
use Carbon\Carbon;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function getByDay($date)
    {
        try {
            $date = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
            $filteredReservations = $this->applyFilters($date);

            return new CustomResponse($filteredReservations);
        } catch (\Throwable $e) {
            return new CustomResponse("Failed", 400);
        }
    }

    public function getByRange($dateStart, $dateEnd)
    {
        try {

            $dateStartFormatted = Carbon::createFromFormat('d/m/Y', $dateStart)->format('Y-m-d');
            $dateEndFormatted = Carbon::createFromFormat('d/m/Y', $dateEnd)->format('Y-m-d');

            $dates = [];
            $dates[] = $dateStartFormatted;

            $dateStart = Carbon::createFromFormat('Y-m-d', $dateStartFormatted);
            $dateEnd = Carbon::createFromFormat('Y-m-d', $dateEndFormatted);

            while ($dateStart->lessThan($dateEnd)) {
                $dateStart->addDay();
                $dates[] = $dateStart->format('Y-m-d');
            }

            $datesArray = [];

            collect($dates)->each(function ($date) use (&$datesArray) {
                $filteredReservations = $this->applyFilters($date);

                $datesArray[] = [
                    'date' => $date,
                    'status_date' => $filteredReservations,
                ];
            });

            return new CustomResponse($datesArray);

        } catch (\Throwable $e) {
            // dd($e->getMessage());
            return new CustomResponse("Failed", 400);
        }
    }

    public function getByRoute($idRoute)
    {
        try {
            $daysByRoute = RouteData::where('route_id', $idRoute)->with('calendar.calendarDaysDisabled')->first();

            $daysDisabled = $daysByRoute->calendar->calendarDaysDisabled->pluck('day')->toArray();

            $datesArray = [];

            collect($daysDisabled)->each(function ($date) use (&$datesArray) {
                $filteredReservations = $this->applyFilters($date);

                $datesArray[] = [
                    'date' => $date,
                    'status_date' => $filteredReservations,
                ];
            });

            return new CustomResponse($datesArray);
        } catch (\Throwable $e) {
            return new CustomResponse("Failed", 400);
        }

    }

    private function applyFilters($date)
    {
        $resultDaysDisabled = $this->getDayDisabledForCalendar($date);
        if ($resultDaysDisabled) {
            return StatusDateEnum::DAY_DISABLED_FOR_CALENDAR->value;
        }

        $resultDaysOffFrecuency = $this->getDayOffFrecuency($date);
        if ($resultDaysOffFrecuency) {
            return StatusDateEnum::DAY_OFF_FRECUENCY->value;
        }

        $resultDaysReserved = $this->getDayReserved($date);
        if ($resultDaysReserved) {
            return StatusDateEnum::DAY_RESERVED->value;
        }

        $resultDaysWithService = $this->getDayWithService($date);
        if ($resultDaysWithService) {
            return StatusDateEnum::DAY_WITH_SERVICE->value;
        }

        $resultFullRouteCapacity = $this->getDayFullRouteCapacity($date);
        if ($resultFullRouteCapacity) {
            return StatusDateEnum::FULL_ROUTE_CAPACITY->value;
        }

        return "";
    }

    // if return true, day is disabled else day is enabled
    private function getDayDisabledForCalendar($date)
    {
        return CalendarDaysDisabled::whereDate('day', $date)->where('enabled', '0')->exists();
    }

    // if return true, day is off frecuency else day is on frecuency
    private function getDayOffFrecuency($date)
    {
        $calendarDaysDisabled = CalendarDaysDisabled::whereDate('day', $date)->with('calendar')->first();

        if (!$calendarDaysDisabled) {
            return "";
        }

        $calendarId = $calendarDaysDisabled->calendar->id;

        $routeData = RouteData::where('calendar_id', $calendarId)->first();

        if (!$routeData) {
            return "";
        }

        $nameDay = Carbon::createFromFormat('Y-m-d', $date)->format('l');

        return $routeData[strtolower(substr($nameDay, 0, 3))] === 1;
    }

    // if return true, day is reserved else day is not reserved
    private function getDayReserved($date)
    {
        return Reservation::whereDate('reservation_start', $date)->whereDate('reservation_end', $date)->exists();
    }

    private function getDayWithService($date)
    {
        return Service::whereDate('timestamp', $date)->exists();
    }

    // not implemented
    private function getDayFullRouteCapacity($date)
    {
        return false;
    }

}
