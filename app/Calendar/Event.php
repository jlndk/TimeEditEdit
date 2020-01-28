<?php

namespace App\Calendar;

use \Carbon\Carbon;

class Event
{
    public string $uid = "";

    public string $summary = "";

    public string $description = "";

    public ?Carbon $dateStart = null;

    public ?Carbon $dateEnd = null;

    public string $location = "";

    public string $status = "";

    public string $created = "";

    public string $updated = "";

    public string $timestamp = "";

    public function __construct(?string $content = null)
    {
        if ($content !== null) {
            $this->parse($content);
        }
    }

    public function duration(): int
    {
        if ($this->dateEnd) {
            return $this->dateStart->diffInSeconds($this->dateEnd);
        }
    }

    public function render(): string
    {
        Carbon::setToStringFormat('Ymd\THis\Z');

        $output = "BEGIN:VEVENT\r\n";

        $output .= "UID:" . escape_comma($this->uid) . "\r\n";
        $output .= "SUMMARY:" . escape_comma($this->summary) . "\r\n";
        $output .= "DESCRIPTION:" . escape_comma($this->description) . "\r\n";
        $output .= "LOCATION:" . escape_comma($this->location) . "\r\n";

        $output .= "DTSTART:" . $this->dateStart . "\r\n";
        $output .= "DTEND:" . $this->dateEnd . "\r\n";
        $output .= "LAST-MODIFIED:" . $this->updated;

        $output .= "END:VEVENT\r\n";

        return $output;
    }

    public function export(): string
    {
        return $this->render();
    }

    public function parse(string $content): Event
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
}
