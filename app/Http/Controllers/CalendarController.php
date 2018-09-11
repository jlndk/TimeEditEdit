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
    protected $calendar;

    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }

    public function show($calid, Request $request)
    {
        //Try to fetch the calendar with this id from the cache
        $calendar = Cache::get($calid);

        /**
         * If don't have a cached version of the calendar or we're in
         * development mode we fetch the calendar from TimeEdit
         */
        if (!$calendar || App::environment('local')) {
            $calendar = $this->calendar->create("https://cloud.timeedit.net/itu/web/public/$calid.ics");
            //After we fetch the calendar we put it into the cache for future requests
            Cache::put($calid, $calendar, 5);
        }

        foreach ($calendar->events as $i => $event) {
            $et = new EventTransformer($event);
            $calendar->events[$i] = $et->transform();
        }

        return $calendar;
    }
}
