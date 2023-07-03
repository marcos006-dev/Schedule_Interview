<?php

namespace App\Enums;

enum StatusDateEnum:string {
    case DAY_DISABLED_FOR_CALENDAR = 'warning';
    case DAY_OFF_FRECUENCY = 'danger';
    case DAY_RESERVED = 'green';
    case DAY_WITH_SERVICE = 'white';
    case FULL_ROUTE_CAPACITY = 'gray';
}