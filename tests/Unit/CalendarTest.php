<?php

namespace Tests\Unit;

use \App;
use App\Calendar\Calendar;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function aCalendarCanBeCreatedWithoutData()
    {
        $calendar = App::make(Calendar::class);

        $this->assertEquals($calendar->title, null);
        $this->assertEquals($calendar->description, null);
        $this->assertEquals($calendar->published, null);
        $this->assertCount(0, $calendar->events);
    }

    /**
     * @test
     * @return void
     */
    public function aCalendarCanBeCreatedWithMetadataButNoEvents()
    {
        $rawData =  "BEGIN:VCALENDAR\n".
                    "VERSION:2.0\n".
                    "METHOD:PUBLISH\n".
                    "X-WR-CALNAME:TimeEdit-SWU 1st year-20180901\n".
                    "X-WR-CALDESC:Date limit 2018-08-20 - 2023-09-10\n".
                    "X-PUBLISHED-TTL:PT20M\n".
                    "CALSCALE:GREGORIAN\n".
                    "PRODID:-//TimeEdit\\\, //TimeEdit//EN\n".
                    "END:VCALENDAR";

        $calendar = App::make(Calendar::class);
        $calendar->create($rawData);

        $this->assertEquals($calendar->title, 'TimeEdit-SWU 1st year-20180901');
        $this->assertEquals($calendar->description, 'Date limit 2018-08-20 - 2023-09-10');
        $this->assertEquals($calendar->published, 'PT20M');
        $this->assertCount(0, $calendar->events);
    }

    /**
     * @test
     * @return void
     */
    public function aCalendarCanBeCreatedWithEventData()
    {
        $rawData =  "BEGIN:VCALENDAR\n".
                    "X-WR-CALNAME:Calendar Name\n".
                    "X-WR-CALDESC:A calendar description\n".
                    "BEGIN:VEVENT\n".
                    "UID:1234\n".
                    "SUMMARY:An amazing event\n".
                    "DESCRIPTION:This is a descrption of an amazing event\n".
                    "END:VEVENT".
                    "END:VCALENDAR";

        $calendar = App::make(Calendar::class);
        $calendar->create($rawData);

        $this->assertEquals($calendar->title, 'Calendar Name');
        $this->assertEquals($calendar->description, 'A calendar description');
        $this->assertCount(1, $calendar->events);

        //Also test that the event is parsed correctly together with the calendar data
        $event = @$calendar->events[0];

        $this->assertEquals($event->uid, '1234');
        $this->assertEquals($event->summary, 'An amazing event');
        $this->assertEquals($event->description, 'This is a descrption of an amazing event');
    }
}
