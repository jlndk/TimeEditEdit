<?php

namespace App\Calendar;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class Calendar implements Responsable
{
    /**
     * The title of the entire calendar
     */
    public ?string $title = null;

    /**
     * The description for the entire calendar
     */
    public string $description = "";

    /**
     * PUBLISHED-TTL
     */
    public ?string $published = null;

    /**
     * The list of all events in the calendar
     */
    public array $events = [];

    protected RemoteCalendar $remoteCalendar;

    /**
     * Create a new Calendar. Optionally fill calendar with existing ICS data.
     *
     * @param string $content An existing ICS calendar. Can either be the actual content or a path/url to an ics file.
     */
    public function __construct(?string $content = null, RemoteCalendar $remoteCalendar)
    {
        $this->remoteCalendar = $remoteCalendar;

        if ($content) {
            $this->create($content);
        }
    }

    /**
     * Create an ICS compatable string from this existing calendar.
     * @return string The ICS formatted calendar.
     */
    public function render(): string
    {
        $output = "BEGIN:VCALENDAR" . "\r\n";
        $output .= 'VERSION:2.0' . "\r\n";
        $output .= "METHOD:PUBLISH\r\n";
        $output .= "CALSCALE:GREGORIAN" . "\r\n";
        $output .= "PRODID://TimeEditEdit@jlndk//\r\n";
        $output .= "X-WR-CALNAME:" . $this->title . "\r\n";
        $output .= "X-WR-CALDESC:" . $this->description . "\r\n";
        $output .= "X-PUBLISHED-TTL:" . $this->published . "\r\n";

        foreach ($this->events as $event) {
            $output .= $event->render();
        }

        $output .= "END:VCALENDAR";

        return $output;
    }

    /**
     * Alias for render
     */
    public function export(): string
    {
        return $this->render();
    }

    /**
     * Extract all information from an ICS formatted string
     *
     * @param  string  $content The actual content of an ICS file
     * @return $this
     */
    protected function parse(string $content): Calendar
    {
        $content = str_replace("\r\n ", '', $content);

        // Title
        preg_match('`^X-WR-CALNAME:(.*)$`m', $content, $m);
        $this->title = $m ? trim($m[1]) : null;

        // Description
        preg_match('`^X-WR-CALDESC:(.*)$`m', $content, $m);
        $this->description = $m ? trim($m[1]) : null;

        // Description
        preg_match('`^X-PUBLISHED-TTL:(.*)$`m', $content, $m);
        $this->published = $m ? trim($m[1]) : null;

        // Events
        preg_match_all('`BEGIN:VEVENT(.+)END:VEVENT`Us', $content, $m);
        foreach ($m[0] as $c) {
            $this->events[] = new Event($c);
        }

        return $this;
    }

    /**
     * Fill the calendar with existing ICS data.
     * @param  string $content An existing ICS calendar. Can either be the actual content or a path/url to an ics file.
     * @return $this
     */
    public function create(string $content): Calendar
    {
        $isUrl  = strpos($content, 'http') === 0 && filter_var($content, FILTER_VALIDATE_URL);
        $isFile = strpos($content, "\n") === false && file_exists($content);

        if ($isFile) {
            $content = file_get_contents($content);
        } else if ($isUrl) {
            $content = $this->remoteCalendar->setUrl($content)->fetch();
        }

        $this->parse($content);

        return $this;
    }

    /**
     * Magic method for automatic string conversion
     */
    public function __toString(): string
    {
        return $this->render();
    }

    public function toResponse($request): Response
    {
        $response = response($this->render())
            ->header('Content-Type', 'text/calendar; charset=UTF-8');

        if ($request->has('plain')) {
            $response->header('Content-Type', 'text/plain; charset=UTF-8');
        }

        return $response;
    }

    public static function get(string $calid): Calendar
    {
        //Try to fetch the calendar with this id from the cache
        $calendar = Cache::get($calid);

        /**
         * If don't have a cached version of the calendar or we're in
         * development mode we fetch the calendar from TimeEdit
         */
        if (!$calendar || App::environment('local')) {
            $calendar = app(Calendar::class)->create("https://cloud.timeedit.net/itu/web/public/$calid.ics");
            //After we fetch the calendar we put it into the cache for future requests
            Cache::put($calid, $calendar, 5);
        }

        return $calendar;
    }
}
