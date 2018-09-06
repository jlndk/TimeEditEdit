<?php

namespace App\Calendar;

use Illuminate\Contracts\Support\Responsable;

class Calendar implements Responsable
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $published;

    /**
     * @var array
     */
    public $events = array();

    /**
     * @var array
     */
    protected $_eventsByDate;

    public function __construct($content = null)
    {
        if ($content) {
            $isUrl  = strpos($content, 'http') === 0 && filter_var($content, FILTER_VALIDATE_URL);
            $isFile = strpos($content, "\n") === false && file_exists($content);
            if ($isUrl || $isFile) {
                $content = file_get_contents($content);
            }
            $this->parse($content);
        }
    }

    public function title()
    {
        return $this->title;
    }

    public function description()
    {
        return $this->description;
    }

    public function published()
    {
        return $this->published;
    }

    public function events()
    {
        return $this->events;
    }

    public function eventsByDate()
    {
        if (! $this->_eventsByDate) {
            $this->_eventsByDate = array();
            foreach ($this->events() as $event) {
                foreach ($event->occurrences() as $occurrence) {
                    $date = $occurrence->format('Y-m-d');
                    $this->_eventsByDate[$date][] = $event;
                }
            }
            ksort($this->_eventsByDate);
        }
        return $this->_eventsByDate;
    }

    public function eventsByDateBetween($start, $end)
    {
        if ((string) (int) $start !== (string) $start) {
            $start = strtotime($start);
        }
        $start = date('Y-m-d', $start);
        if ((string) (int) $end !== (string) $end) {
            $end = strtotime($end);
        }
        $end = date('Y-m-d', $end);
        $return = array();
        foreach ($this->eventsByDate() as $date => $events) {
            if ($start <= $date && $date < $end) {
                $return[$date] = $events;
            }
        }
        return $return;
    }

    public function eventsByDateSince($start)
    {
        if ((string) (int) $start !== (string) $start) {
            $start = strtotime($start);
        }
        $start = date('Y-m-d', $start);
        $return = array();
        foreach ($this->eventsByDate() as $date => $events) {
            if ($start <= $date) {
                $return[$date] = $events;
            }
        }
        return $return;
    }

    public function parse($content)
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

    public function render():string {
        $output = "BEGIN:VCALENDAR" . "\r\n";
        $output .= 'VERSION:2.0' . "\r\n";
        $output .= "METHOD:PUBLISH\r\n";
        $output .= "CALSCALE:GREGORIAN" . "\r\n";
        $output .= "PRODID://TimeEditEdit@jlndk//\r\n";
        $output .= "X-WR-CALNAME:" . $this->title . "\r\n";
        $output .= "X-WR-CALDESC:" . $this->description . "\r\n";
        $output .= "X-PUBLISHED-TTL:" . $this->published . "\r\n";
        foreach($this->events() as $event) {
            $output .= $event->render();
        }
        $output .= "END:VCALENDAR";
        return $output;
    }

    /**
     * Alias for render
     */
    public function export():string {
        return $this->render();
    }

    /**
     * Magic method for automatic string conversion
     */
    public function __toString(): string {
        return $this->render();
    }

    public function toResponse($request)
    {
        $response = response($this->render())
            ->header('Content-Type', 'text/calendar; charset=UTF-8');

        if($request->has('plain')) {
            $response->header('Content-Type', 'text/plain; charset=UTF-8');
        }

        return $response;
    }
}
