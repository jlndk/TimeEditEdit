<?php

namespace Tests\Unit;

use App\Calendar\Event;
use App\Calendar\EventTransformer;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTransfomerTest extends TestCase
{
    /**
     * Test if the transformer returns the expected text format of each field
     * for the default TimeEdit format
     *
     * @test
     * @return void
     */
    public function itCanTransformANormalEvent()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:Study Activity\,  : ".
                    "Grundlæggende programmering. BSGRPRO1KU\, ".
                    "Name: Claus Brabrand\, ".
                    "Programme: SWU 1st year\, ".
                    "Course type: Mandatory\,  ".
                    "Activity: Lecture\n".
                    "LOCATION:Room: Aud 1 (0A11)\n".
                    "DESCRIPTION:ID 39280\n".
                    "END:VEVENT";

        $orgEvent = new Event($rawData);
        $transformer = tap(new EventTransformer($orgEvent))->transform();

        $expectedDescription = 'Lektor: Claus Brabrand\nProgramme: SWU 1st year\nTimeEdit ID: 39280';

        $this->assertEquals($transformer->summary(), 'Forelæsning: Grundlæggende programmering');
        $this->assertEquals($transformer->description(), $expectedDescription);
        $this->assertEquals($transformer->location(), 'Room: Aud 1 (0A11)');
    }

    /**
    * Test if the transformer returns the expected text format of each field
    * for the Study Assistance format
     *
     * @test
     * @return void
     */
    public function itCanTransformAStudyAssistanceEvent()
    {
        $rawData =  "BEGIN:VEVENT\n" .
                    "SUMMARY:Study Activity\,  : Study Assistance\, ".
                    "Name: Dan Witzner Hansen\, ".
                    "Programme: SWU 1st year\n".
                    "LOCATION:Room: 3A50\n".
                    "DESCRIPTION:ID 43107\n".
                    "END:VEVENT";

        $orgEvent = new Event($rawData);
        $transformer = tap(new EventTransformer($orgEvent))->transform();

        $expectedDescription = 'Lektor: Dan Witzner Hansen\nProgramme: SWU 1st year\nTimeEdit ID: 43107';

        $this->assertEquals($transformer->summary(), 'Study Assistance');
        $this->assertEquals($transformer->description(), $expectedDescription);
        $this->assertEquals($transformer->location(), 'Room: 3A50');
    }
}
