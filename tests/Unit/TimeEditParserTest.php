<?php

namespace Tests\Unit;

use App\Calendar\Event;
use App\Calendar\TimeEditParser;
use Tests\TestCase;

class TimeEditParserTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function itCanParseANormalSummary()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            "Study Activity\,  : Grundlæggende programmering. BSGRPRO1KU\, " .
            "Name: Claus Brabrand\, " .
            "Name: Dan Witzner Hansen\, " .
            "Name: Signe Kyster\, " .
            "Programme: SWU 1st year\, " .
            "Course type: Mandatory\,  " .
            "Activity: Lecture\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals('Grundlæggende programmering', $parser->studyActivities());
        $this->assertEquals(__('calendar.activity.lecture'), $parser->activity());
        $this->assertEquals('Claus Brabrand, Dan Witzner Hansen & Signe Kyster', $parser->lectors());
        $this->assertEquals(trans_choice('calendar.lectors', 3), $parser->lectorPrefix());
        $this->assertEquals('Mandatory', $parser->courseType());
        $this->assertEquals('SWU 1st year', $parser->programme());
    }

    /**
     * @test
     * @return void
     */
    public function itCanParseAStudyAssitanceSummary()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            "Study Activity\,  : Study Assistance\, " .
            "Name: Dan Witzner Hansen\, " .
            "Programme: SWU 1st year\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals('Study Assistance', $parser->studyActivities());
        $this->assertEquals('Dan Witzner Hansen', $parser->lectors());
        $this->assertEquals(trans_choice('calendar.lectors', 1), $parser->lectorPrefix());
        $this->assertEquals('SWU 1st year', $parser->programme());
    }

    /**
     * @test
     * @return void
     */
    public function itCanParseAnReexamSummary()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:Study Activity\,  : " .
            "Førsteårsprojekt: Danmarkskort. Visualisering\, Navigation\, Søgning og Rute. 1413001U\, " .
            "Name: Troels Bjerre Lund\, Programme: SWU 1st year\,  Activity: Reexam" .
            "LOCATION:Room: 2A20\n" .
            "DESCRIPTION:ID 54626\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $expectedStudtActivities = 'Førsteårsprojekt: Danmarkskort. Visualisering, Navigation, Søgning og Rute';

        $this->assertEquals($expectedStudtActivities, $parser->studyActivities());
        $this->assertEquals('Troels Bjerre Lund', $parser->lectors());
        $this->assertEquals(trans_choice('calendar.lectors', 1), $parser->lectorPrefix());
        $this->assertEquals('SWU 1st year', $parser->programme());
    }

    /**
     * @test
     * @return void
     */
    public function itCanHandleOneLector()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            "Study Activity\,  : Grundlæggende programmering. BSGRPRO1KU\, " .
            "Name: Claus Brabrand\, " .
            "Programme: SWU 1st year\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($parser->lectors(), 'Claus Brabrand');
        $this->assertEquals($parser->lectorPrefix(), trans_choice('calendar.lectors', 1));
    }

    /**
     * @test
     * @return void
     */
    public function itCanHandleMultipleLectors()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            "Study Activity\,  : Grundlæggende programmering. BSGRPRO1KU\, " .
            "Name: Claus Brabrand\, " .
            "Name: Dan Witzner Hansen\, " .
            "Name: Signe Kyster\, " .
            "Programme: SWU 1st year\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($parser->lectors(), 'Claus Brabrand, Dan Witzner Hansen & Signe Kyster');
        $this->assertEquals($parser->lectorPrefix(), trans_choice('calendar.lectors', 2));
    }

    /**
     * @test
     * @return void
     */
    public function itCanHandleNoStudyActivities()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            "Name: Jonas Lindenskov Nielsen\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertNull($parser->studyActivities());
    }

    /**
     * @test
     * @return void
     */
    public function itCanHandleMultipleStudyActivities()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            "Study Activity\,  : Projektarbejde og kommunikation. 1407003U-1\, " .
            "Study Activity\,  : Grundlæggende programmering. BSGRPRO1KU\, " .
            "Name: Henriette Moos\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $expectedActivities = 'Projektarbejde og kommunikation & Grundlæggende programmering';

        $this->assertEquals($expectedActivities, $parser->studyActivities());
    }

    /**
     * @test
     * @dataProvider removeUnwantedSuffixesProvider
     * @return void
     */
    public function itWillRemoveUnwantedSuffixes($studyActivity, $suffix)
    {
        $rawData = "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            $studyActivity .
            "Name: Helge Pfeiffer\, Name: Mircea Lungu\, Name: Paolo Tell\, " .
            "Programme: CS - 1st year\, Programme: SWU 2nd year\, " .
            "Programme: SWU 3rd year\, Course type: Elective\,  Activity: Lecture\n" .
            "DESCRIPTION:ID 39280\n" .
            "LOCATION:Room: Aud 1 (0A11)\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($suffix, $parser->studyActivities());
    }

    public function removeUnwantedSuffixesProvider()
    {
        yield [
            "Study Activity\,  : DevOps\, Software Evolution and Software Maintenance\, BSc. BSDSESM1KU\, ",
            "DevOps, Software Evolution and Software Maintenance"
        ];
        yield [
            "Study Activity\,  : DevOps\, Software Evolution and Software Maintenance\, MSc. KSDSESM1KU\, ",
            "DevOps, Software Evolution and Software Maintenance"
        ];
    }

    /**
     * @test
     * @return void
     */
    public function itWillConsolidateActivitesWithSameName()
    {
        $rawData = "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            "Study Activity\,  : DevOps\, Software Evolution and Software Maintenance\, BSc. BSDSESM1KU\, " .
            "Study Activity\,  : DevOps\, Software Evolution and Software Maintenance\, MSc. KSDSESM1KU\, " .
            "Name: Helge Pfeiffer\, Name: Mircea Lungu\, Name: Paolo Tell\, " .
            "Programme: CS - 1st year\, Programme: SWU 2nd year\, " .
            "Programme: SWU 3rd year\, Course type: Elective\,  Activity: Lecture\n" .
            "DESCRIPTION:ID 39280\n" .
            "LOCATION:Room: Aud 1 (0A11)\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals("DevOps, Software Evolution and Software Maintenance", $parser->studyActivities());
    }

    /**
     * @test
     * @return void
     */
    public function itCanHandleDublicateStudyActivities()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            "Study Activity\,  : Projektarbejde og kommunikation. 1407003U-1\, " .
            "Study Activity\,  : Projektarbejde og kommunikation. 1407003U-2\, " .
            "Name: Henriette Moos\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $expectedActivities = 'Projektarbejde og kommunikation';

        $this->assertEquals($parser->studyActivities(), $expectedActivities);
    }

    /**
     * @test
     * @return void
     */
    public function itCanHandleMultipleActivityTypes()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            "Study Activity\,  : Projektarbejde og kommunikation. 1407003U-1\, " .
            "Name: Henriette Moos\, " .
            "Activity: Exercises\,  " .
            "Activity: Lecture\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $expectedActivities =  __('calendar.activity.exercises') . ' & ' . __('calendar.activity.lecture');

        $this->assertEquals($parser->activity(), $expectedActivities);
    }

    /**
     * @test
     * @return void
     */
    public function itCanHandleDublicateActivityTypes()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            "Study Activity\,  : Projektarbejde og kommunikation. 1407003U-1\, " .
            "Name: Henriette Moos\, " .
            "Activity: Exercises\,  " .
            "Activity: Exercises\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($parser->activity(), __('calendar.activity.exercises'));
    }

    /**
     * @test
     * @return void
     */
    public function whenNoActivtyIsGivenItShouldBeReturnedEmpty()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            "Study Activity\,  : Grundlæggende programmering. BSGRPRO1KU\, " .
            "Name: Claus Brabrand\, " .
            "Programme: SWU 1st year\, " .
            "Course type: Mandatory\,  " .
            "END:VEVENT";

        $event = new Event($rawData);
        $parser = new TimeEditParser($event);

        $this->assertEquals("", $parser->activity());
    }

    /**
     * @test
     * @return void
     */
    public function itCanParseOtherFields()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:Activity: Lecture\n" .
            "LOCATION:Room: Aud 1 (0A11)\n" .
            "DESCRIPTION:ID 39280\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($parser->id(), '39280');
        $this->assertEquals($parser->roomPrefix(), trans_choice('calendar.rooms', 1));
        $this->assertEquals($parser->rooms(), 'Aud 1 (0A11)');
    }

    /**
     * @test
     * @return void
     */
    public function itCanHandleNoRooms()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:Activity: Lecture\n" .
            "END:VEVENT";

        $event = new Event($rawData);

        $parser = new TimeEditParser($event);

        $this->assertEquals($parser->roomPrefix(), null);
        $this->assertEquals($parser->rooms(), null);
    }

    /**
     * @test
     * @return void
     */
    public function itCanParseMultipleRooms()
    {
        $rawData =  "BEGIN:VEVENT\n" .
            "SUMMARY:Activity: Lecture\n" .
            "LOCATION:Room: 2A52\, Room: 2A54\, Room: 3A18\, Room: 3A52\n" .
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
            "SUMMARY:" .
            "Programme: SWU 1st year\," .
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
            "SUMMARY:" .
            "Programme: DS 1st year\, " .
            "Programme: SDT - Software Design SD\, " .
            "Programme: SDT - Software Development\," .
            "Programme: SWU 1st year\,\n" .
            "END:VEVENT";

        $event = new Event($rawData);


        $parser = new TimeEditParser($event);

        $res = 'DS 1st year, SDT - Software Design SD, SDT - Software Development & SWU 1st year';

        $this->assertEquals($res, $parser->programme());
    }
}
