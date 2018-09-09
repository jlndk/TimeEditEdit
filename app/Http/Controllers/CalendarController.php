<?php

namespace App\Http\Controllers;

use App\Calendar\Calendar;
use App\Calendar\EventTransformer;
use \App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    public function show($calid, Request $request)
    {
        $cachedCalendar = Cache::get($calid);

        /**
         * If we have a cached version of the calendar and we're not in
         * development mode we return that instead of fetching it from TimeEdit.
         *
         */
        if ($cachedCalendar && !App::environment('local')) {
            return $cachedCalendar;
        }

        $calendar = new Calendar("https://cloud.timeedit.net/itu/web/public/$calid.ics");

        foreach ($calendar->events as $i => $event) {
            $et = new EventTransformer($event);
            $calendar->events[$i] = $et->transform();
        }

        Cache::put($calid, $calendar, 5);

        return $calendar;
    }
}
