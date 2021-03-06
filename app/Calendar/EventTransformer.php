<?php

namespace App\Calendar;

use \App\Calendar\Event;
use \App\Calendar\TimeEditParser;

class EventTransformer
{
    protected Event $event;
    protected TimeEditParser $info;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->info = new TimeEditParser($event);
    }

    public function summary(): string
    {
        $activity = $this->info->activity();

        //If the event is Study Assistance we should not display an activity type
        if ($this->info->studyActivities() == "Study Assistance") {
            $activity = "";
        } else if ($activity != "") {
            //Only add the colon after the activity if there is an activity
            $activity .= ": ";
        }

        return $activity . $this->info->studyActivities();
    }

    public function description(): string
    {
        $lectors = $this->info->lectors();
        $programme = $this->info->programme();

        $description = "";

        if ($lectors !== null) {
            $description .= $this->info->lectorPrefix() . ": " . $lectors . "\\n";
        }

        if ($programme !== null) {
            $description .= __('calendar.programme') . ": " . $programme . "\\n";
        }

        $description .= __('calendar.timeedit_id') . ": " . $this->info->id();

        return $description;
    }

    public function location(): string
    {
        return $this->info->roomPrefix() . ": " . $this->info->rooms();
    }

    public function transform(): Event
    {
        $this->event->summary = $this->summary();
        $this->event->description = $this->description();
        $this->event->location = $this->location();

        return $this->event;
    }
}
