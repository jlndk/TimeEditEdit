<?php

namespace App\Http\Controllers;

use App\Calendar\Calendar;
use App\Calendar\EventTransformer;

class CalendarController
{
    public function show($calid)
    {
        $calendar = Calendar::get($calid);

        foreach ($calendar->events as $i => $event) {
            $et = new EventTransformer($event);
            $calendar->events[$i] = $et->transform();
        }

        return $calendar;
    }
}
