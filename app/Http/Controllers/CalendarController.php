<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Calendar\Calendar;
use App\Calendar\EventTransformer;

class CalendarController extends Controller
{
    function show($calid, Request $request) {
        $calendar = new Calendar("https://cloud.timeedit.net/itu/web/public/$calid.ics");

        foreach($calendar->events as $i => $event) {
            $et = new EventTransformer($event);
            $calendar->events[$i] = $et->transform();
        }

        return $calendar;
    }
}
