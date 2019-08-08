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

        $expectedDescription =  trans_choice('calendar.lectors', 1) . ': Claus Brabrand\n'.
                                __('calendar.programme') . ': SWU 1st year\nTimeEdit ID: 39280';

        $this->assertEquals(__('calendar.activity.lecture') . ': Grundlæggende programmering', $transformer->summary());
        $this->assertEquals($expectedDescription, $transformer->description());
        $this->assertEquals(trans_choice('calendar.rooms', 1) . ': Aud 1 (0A11)', $transformer->location());
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

        $expectedDescription =  trans_choice('calendar.lectors', 1) . ': Dan Witzner Hansen\n'.
                                __('calendar.programme') . ': SWU 1st year\n'.
                                __('calendar.timeedit_id') . ': 43107';

        $this->assertEquals('Study Assistance', $transformer->summary());
        $this->assertEquals($expectedDescription, $transformer->description());
        $this->assertEquals(trans_choice('calendar.rooms', 1) . ': 3A50', $transformer->location());
    }

    /**
     * Test if the transformer returns the expected text format of each field
     * for the default TimeEdit format
     *
     * @test
     * @return void
     */
    public function itCanTransformAReexamEvent()
    {
        $rawData =  "BEGIN:VEVENT\n".
                    "SUMMARY:Study Activity\,  : " .
                    "Førsteårsprojekt: Danmarkskort. Visualisering\, Navigation\, Søgning og Rute. 1413001U\, ".
                    "Name: Troels Bjerre Lund\, Programme: SWU 1st year\,  Activity: Reexam\n".
                    "LOCATION:Room: 2A20\n".
                    "DESCRIPTION:ID 54626\n".
                    "END:VEVENT";

        $orgEvent = new Event($rawData);

        $transformer = tap(new EventTransformer($orgEvent))->transform();

        $expectedSummary = __('calendar.activity.reexam') .
                           ': Førsteårsprojekt: Danmarkskort. Visualisering, Navigation, Søgning og Rute';
        $expectedDescription =  trans_choice('calendar.lectors', 1) . ': Troels Bjerre Lund\n' .
            __('calendar.programme') . ': SWU 1st year\nTimeEdit ID: 54626';

        $this->assertEquals($expectedSummary, $transformer->summary());
        $this->assertEquals($expectedDescription, $transformer->description());
        $this->assertEquals(trans_choice('calendar.rooms', 1) . ': 2A20', $transformer->location());
    }
}
