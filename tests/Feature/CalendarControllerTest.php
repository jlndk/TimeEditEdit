<?php

namespace Tests\Feature;

use Tests\TestCase;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

class CalendarControllerTest extends TestCase
{
    private function mockResponse(string $body, int $status = 200, array $headers = []): void
    {
        $mock = new MockHandler([
            new Response($status, $headers, $body),
        ]);

        $client = new Client([
            'handler' => HandlerStack::create($mock)
        ]);

        $this->app->instance(Client::class, $client);
    }

    /**
     * A basic test example.
     *
     * @test
     * @return void
     */
    public function fetchingAnInvalidCalendarShouldResultInA404StatusCode()
    {
        $this->mockResponse('', 200, ['content-type' => 'text/html; charset=UTF-8',]);
        $this->get('/abc123')->assertStatus(404);
    }

    /**
     * @test
     * @return void
     */
    public function fetchingAValidCalendarReturnsTheFormattedCalendar()
    {
        $rawData =  "BEGIN:VCALENDAR\n" .
            "X-WR-CALNAME:Calendar Name\n" .
            "X-WR-CALDESC:A calendar description\n" .
            "BEGIN:VEVENT\n" .
            "SUMMARY:" .
            "Study Activity\,  : Some course. BSCODE142\, " .
            "Name: Some Dude\, " .
            "Programme: SWU 3rd year\, " .
            "Course type: Mandatory\,  " .
            "Activity: Lecture\n" .
            "DESCRIPTION:ID 39280\n" .
            "END:VEVENT\n" .
            "END:VCALENDAR";

        $expected = "BEGIN:VCALENDAR\r\n" .
            "VERSION:2.0\r\n" .
            "METHOD:PUBLISH\r\n" .
            "CALSCALE:GREGORIAN\r\n" .
            "PRODID://TimeEditEdit@jlndk//\r\n" .
            "X-WR-CALNAME:Calendar Name\r\n" .
            "X-WR-CALDESC:A calendar description\r\n" .
            "X-PUBLISHED-TTL:\r\n" .
            "BEGIN:VEVENT\r\n" .
            "UID:\r\n" .
            "SUMMARY:Lecture: Some course\r\n" .
            "DESCRIPTION:Lector: Some Dude\\nProgramme: SWU 3rd year\\nTimeEdit ID: 39280\r\n" .
            "LOCATION:: \r\n" .
            "DTSTART:\r\n" .
            "DTEND:\r\n" .
            "LAST-MODIFIED:END:VEVENT\r\n" .
            "END:VCALENDAR";

        $this->mockResponse($rawData);
        $response = $this->get('/foobar')->assertStatus(200);
        $actual = $response->getContent();

        $this->assertEquals($expected, $actual);
    }
}
