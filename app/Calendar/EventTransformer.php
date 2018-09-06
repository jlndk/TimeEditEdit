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

    public function summary()
    {
        $activity = $this->info->activity();

        //Only add the colon after the activity if there is an activity
        if ($activity != "") {
            $activity .= ": ";
        }

        return $activity . $this->info->studyActivities();
    }

    public function description()
    {
        $description = $this->info->lectorPrefix() . $this->info->lectors()."\\n";
        $description .= "Programme: " . $this->info->programme()."\\n";
        $description .= "TimeEdit ID: " . $this->info->id();

        return $description;
    }

    public function transform()
    {
        $this->event->summary = $this->summary();
        $this->event->description = $this->description();

        return $this->event;
    }
}
