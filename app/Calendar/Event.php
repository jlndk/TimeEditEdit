<?php

namespace App\Calendar;

use \Carbon\Carbon;

class Event
{
    /**
     * @var string
     */
    public $uid;

    /**
     * @var string
     */
    public $summary;

    /**
     * @var string
     */
    public $description;

    /**
     * @var Carbon\Carbon
     */
    public $dateStart;

    /**
     * @var Carbon\Carbon
     */
    public $dateEnd;

    /**
     * @var array
     */
    public $exdate = array();

    /**
     * @var string
     */
    public $location;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $created;

    /**
     * @var string
     */
    public $updated;

    /**
     * @var string
     */
    protected $timestamp;

    public function __construct($content = null)
    {
        if ($content) {
            $this->parse($content);
        }
    }

    public function summary()
    {
        return $this->summary;
    }

    public function title()
    {
        return $this->summary;
    }

    public function description()
    {
        return $this->description;
    }

    public function location() {
        return $this->location;
    }

    public function duration()
    {
        if ($this->dateEnd) {
            return $this->dateStart->diffInSeconds($this->dateEnd);
        }
    }

    public function timestamp() {
        return $this->timestamp;
    }

    public function render(): string {
        Carbon::setToStringFormat('Ymd\THi0\Z');

        $output = "BEGIN:VEVENT\r\n";

        $output .= "UID:". escape_comma($this->uid) ."\r\n";
        $output .= "SUMMARY:". escape_comma($this->summary()) ."\r\n";
        $output .= "DESCRIPTION:". escape_comma($this->description())."\r\n";
        $output .= "LOCATION:" . escape_comma($this->location()) . "\r\n";

        $output .= "DTSTART:".$this->dateStart."\r\n";
        $output .= "DTEND:".$this->dateEnd."\r\n";
        $output .= "LAST-MODIFIED:".$this->updated;

        $output .= "END:VEVENT\r\n";

        return $output;
    }

    public function export(): string {
        return $this->render();
    }

    public function parse($content)
    {
        $content = str_replace("\r\n ", '', $content);

        // UID
        if (preg_match('`^UID:(.*)$`m', $content, $m)) {
            $this->uid = trim($m[1]);
        }

        // Summary
        if (preg_match('`^SUMMARY:(.*)$`m', $content, $m)) {
            $this->summary = stripslashes(trim($m[1]));
        }

        // Description
        if (preg_match('`^DESCRIPTION:(.*)$`m', $content, $m)) {
            $this->description = trim($m[1]);
        }

        // Date start
        if (preg_match('`^DTSTART(?:;.+)?:([0-9]+(T[0-9]+Z)?)`m', $content, $m)) {
            $this->dateStart = new Carbon($m[1]);
        }

        // Date end
        if (preg_match('`^DTEND(?:;.+)?:([0-9]+(T[0-9]+Z)?)`m', $content, $m)) {
            $this->dateEnd = new Carbon($m[1]);
        }

        // Exdate
        if (preg_match_all('`^EXDATE(;.+)?:([0-9]+(T[0-9]+Z)?)`m', $content, $m)) {
            foreach ($m[2] as $dates) {
                $dates = explode(',', $dates);
                foreach ($dates as $d) {
                    $this->exdate[] = new Carbon($d);
                }
            }
        }

        // Location
        if (preg_match('`^LOCATION:(.*)$`m', $content, $m)) {
            $this->location = trim($m[1]);
        }

        // Status
        if (preg_match('`^STATUS:(.*)$`m', $content, $m)) {
            $this->status = trim($m[1]);
        }

        // Created
        if (preg_match('`^CREATED:(.*)`m', $content, $m)) {
            $this->created = new Carbon(trim($m[1]));
        }

        // Updated
        if (preg_match('`^LAST-MODIFIED:(.*)`m', $content, $m)) {
            $this->updated = $m[1];
        }

        // Timestamp
        if (preg_match('`^DTSTAMP:(.*)`m', $content, $m)) {
            $this->timestamp = $m[1];
        }

        return $this;
    }

    protected function _isExdate($date)
    {
        if ((string) (int) $date != $date) {
            $date = strtotime($date);
        }
        $date = date('Y-m-d', $date);
        return in_array($date, $this->exdate);
    }
}
