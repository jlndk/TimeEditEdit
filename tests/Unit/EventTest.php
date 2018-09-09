<?php

namespace Tests\Unit;

use App\Calendar\Event;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    /**
     * A description for this test
     *
     * @test
     * @return void
     */
    public function anEventCanBeCreatedWithoutData()
    {
        $event = new Event();

        $this->assertEquals($event->uid, null);
        $this->assertEquals($event->summary, null);
        $this->assertEquals($event->description, null);
        $this->assertEquals($event->dateStart, null);
        $this->assertEquals($event->dateEnd, null);
        $this->assertEquals($event->location, null);
        $this->assertEquals($event->status, null);
        $this->assertEquals($event->created, null);
        $this->assertEquals($event->updated, null);
        $this->assertEquals($event->timestamp, null);
    }

    /**
     * A description for this test
     *
     * @test
     * @return void
     */
    public function anEventCanBeCreatedWithData()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "DTSTART:20180904T080000Z\n" .
                    "DTEND:20180904T110000Z\n".
                    "UID:39280--425819232-0@timeedit.com\n".
                    "DTSTAMP:20180907T200950Z\n".
                    "LAST-MODIFIED:20180907T200950Z\n".
                    "SUMMARY:Study Activity: Grundlæggende Programmering, Name: Claus Brabrand\n".
                    "LOCATION:Room: Aud 1 (0A11)\n".
                    "STATUS:CONFIRMED\n".
                    "DESCRIPTION:ID 39280\n".
                    "END:VEVENT";

        $event = new Event($rawData);

        $this->assertEquals($event->uid, '39280--425819232-0@timeedit.com');
        $this->assertEquals($event->summary, 'Study Activity: Grundlæggende Programmering, Name: Claus Brabrand');
        $this->assertEquals($event->description, 'ID 39280');
        $this->assertEquals((string)$event->dateStart, "2018-09-04 08:00:00");
        $this->assertEquals((string)$event->dateEnd, "2018-09-04 11:00:00");
        $this->assertEquals($event->location, 'Room: Aud 1 (0A11)');
        $this->assertEquals($event->status, 'CONFIRMED');
        $this->assertEquals($event->updated, '20180907T200950Z');
        $this->assertEquals($event->timestamp, '20180907T200950Z');
    }
}
