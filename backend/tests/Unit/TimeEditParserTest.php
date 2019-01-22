<?php

namespace Tests\Unit;

use App\Calendar\Event;
use App\Calendar\TimeEditParser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TimeEditParserTest extends TestCase
{
    /**
     * A description for this test
     *
     * @test
     * @return void
     */
    public function itCanParseANormalSummary()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:".
                    "Study Activity\,  : Grundlæggende programmering. BSGRPRO1KU\, ".
                    "Name: Claus Brabrand\, ".
                    "Name: Dan Witzner Hansen\, ".
                    "Name: Signe Kyster\, ".
                    "Programme: SWU 1st year\, ".
                    "Course type: Mandatory\,  ".
                    "Activity: Lecture\n".
                    "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($parser->studyActivities(), 'Grundlæggende programmering');
        $this->assertEquals($parser->activity(), __('calendar.activity.lecture'));
        $this->assertEquals($parser->lectors(), 'Claus Brabrand, Dan Witzner Hansen & Signe Kyster');
        $this->assertEquals($parser->lectorPrefix(), trans_choice('calendar.lectors', 3));
        $this->assertEquals($parser->courseType(), 'Mandatory');
        $this->assertEquals($parser->programme(), 'SWU 1st year');
    }

    /**
     * A description for this test
     *
     * @test
     * @return void
     */
    public function itCanParseAStudyAssitanceSummary()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:".
                    "Study Activity\,  : Study Assistance\, ".
                    "Name: Dan Witzner Hansen\, ".
                    "Programme: SWU 1st year\n".
                    "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($parser->studyActivities(), 'Study Assistance');
        $this->assertEquals($parser->lectors(), 'Dan Witzner Hansen');
        $this->assertEquals($parser->lectorPrefix(), trans_choice('calendar.lectors', 1));
        $this->assertEquals($parser->programme(), 'SWU 1st year');
    }

    /**
     * A description for this test
     *
     * @test
     * @return void
     */
    public function itCanHandleOneLector()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:".
                    "Study Activity\,  : Grundlæggende programmering. BSGRPRO1KU\, ".
                    "Name: Claus Brabrand\, ".
                    "Programme: SWU 1st year\n".
                    "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($parser->lectors(), 'Claus Brabrand');
        $this->assertEquals($parser->lectorPrefix(), trans_choice('calendar.lectors', 1));
    }

    /**
     * A description for this test
     *
     * @test
     * @return void
     */
    public function itCanHandleMultipleLectors()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:".
                    "Study Activity\,  : Grundlæggende programmering. BSGRPRO1KU\, ".
                    "Name: Claus Brabrand\, ".
                    "Name: Dan Witzner Hansen\, ".
                    "Name: Signe Kyster\, ".
                    "Programme: SWU 1st year\n".
                    "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($parser->lectors(), 'Claus Brabrand, Dan Witzner Hansen & Signe Kyster');
        $this->assertEquals($parser->lectorPrefix(), trans_choice('calendar.lectors', 2));
    }

    /**
     * A description for this test
     *
     * @test
     * @return void
     */
    public function itCanHandleMultipleStudyActivities()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:".
                    "Study Activity\,  : Projektarbejde og kommunikation. 1407003U-1\, ".
                    "Study Activity\,  : Grundlæggende programmering. BSGRPRO1KU\, ".
                    "Name: Henriette Moos\n".
                    "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $expectedActivities = 'Projektarbejde og kommunikation & Grundlæggende programmering';

        $this->assertEquals($parser->studyActivities(), $expectedActivities);
    }

    /**
     * A description for this test
     *
     * @test
     * @return void
     */
    public function itCanHandleDublicateStudyActivities()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:".
                    "Study Activity\,  : Projektarbejde og kommunikation. 1407003U-1\, ".
                    "Study Activity\,  : Projektarbejde og kommunikation. 1407003U-2\, ".
                    "Name: Henriette Moos\n".
                    "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $expectedActivities = 'Projektarbejde og kommunikation';

        $this->assertEquals($parser->studyActivities(), $expectedActivities);
    }

    /**
     * A description for this test
     *
     * @test
     * @return void
     */
    public function itCanHandleMultipleActivityTypes()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:".
                    "Study Activity\,  : Projektarbejde og kommunikation. 1407003U-1\, ".
                    "Name: Henriette Moos\, ".
                    "Activity: Exercises\,  ".
                    "Activity: Lecture\n".
                    "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $expectedActivities =  __('calendar.activity.exercises') . ' & ' . __('calendar.activity.lecture');

        $this->assertEquals($parser->activity(), $expectedActivities);
    }

    /**
     * A description for this test
     *
     * @test
     * @return void
     */
    public function itCanHandleDublicateActivityTypes()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:".
                    "Study Activity\,  : Projektarbejde og kommunikation. 1407003U-1\, ".
                    "Name: Henriette Moos\, ".
                    "Activity: Exercises\,  ".
                    "Activity: Exercises\n".
                    "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($parser->activity(), __('calendar.activity.exercises'));
    }

    /**
     * A description for this test
     *
     * @test
     * @return void
     */
    public function itCanParseOtherFields()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:Activity: Lecture\n".
                    "LOCATION:Room: Aud 1 (0A11)\n".
                    "DESCRIPTION:ID 39280\n".
                    "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($parser->id(), '39280');
        $this->assertEquals($parser->roomPrefix(), trans_choice('calendar.rooms', 1));
        $this->assertEquals($parser->rooms(), 'Aud 1 (0A11)');
    }

    /**
     * A description for this test
     *
     * @test
     * @return void
     */
    public function itCanParseMultipleRooms()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:Activity: Lecture\n".
                    "LOCATION:Room: 2A52\, Room: 2A54\, Room: 3A18\, Room: 3A52\n".
                    "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($parser->roomPrefix(), trans_choice('calendar.rooms', 2));
        $this->assertEquals($parser->rooms(), '2A52, 2A54, 3A18 & 3A52');
    }

    /**
     * @test
     * @return void
     */
    public function itCanParseASingleProgramme()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:".
                    "Programme: SWU 1st year\,".
                    "END:VEVENT";

        $event = new Event($rawData);


        $parser = new TimeEditParser($event);

        $this->assertEquals('SWU 1st year', $parser->programme());
    }

    /**
     * @test
     * @return void
     */
    public function itCanParseMultipleProgrammes()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:".
                    "Programme: DS 1st year\, ".
                    "Programme: SDT - Software Design SD\, ".
                    "Programme: SDT - Software Development\,".
                    "Programme: SWU 1st year\,\n".
                    "END:VEVENT";

        $event = new Event($rawData);


        $parser = new TimeEditParser($event);

        $this->assertEquals('DS 1st year, SDT - Software Design SD, SDT - Software Development & SWU 1st year', $parser->programme());
    }
}
