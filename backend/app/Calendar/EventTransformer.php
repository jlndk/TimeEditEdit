<?php

namespace App\Calendar;

class EventTransformer
{
    /**
     * @var \App\Calendar\Event
     */
    protected $event;

    /**
     * @var \App\Calendar\TimeEditParser
     */
    protected $info;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->info = new TimeEditParser($event);
    }

    public function summary() : string
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

    public function description() : string
    {
        $description = $this->info->lectorPrefix() . ": " . $this->info->lectors()."\\n";
        $description .= __('calendar.programme'). ": " . $this->info->programme()."\\n";
        $description .= __('calendar.timeedit_id'). ": " . $this->info->id();

        return $description;
    }

    public function location() : string
    {
        return $this->info->roomPrefix() . ": " . $this->info->rooms();
    }

    public function transform() : Event
    {
        $this->event->summary = $this->summary();
        $this->event->description = $this->description();
        $this->event->location = $this->location();

        return $this->event;
    }
}
