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
        $this->assertEquals($parser->activity(), 'Forelæsning');
        $this->assertEquals($parser->lectors(), 'Claus Brabrand, Dan Witzner Hansen & Signe Kyster');
        $this->assertEquals($parser->lectorPrefix(), 'Lektorer: ');
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
        $this->assertEquals($parser->lectorPrefix(), 'Lektor: ');
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
        $this->assertEquals($parser->lectorPrefix(), 'Lektor: ');
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
        $this->assertEquals($parser->lectorPrefix(), 'Lektorer: ');
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

        $this->assertEquals($parser->activity(), 'Exercises & Forelæsning');
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

        $this->assertEquals($parser->activity(), 'Exercises');
    }
}
