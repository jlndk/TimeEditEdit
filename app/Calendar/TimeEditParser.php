<?php

namespace App\Calendar;

class TimeEditParser
{
    /**
     * The list of regular expression for each segment of the given text.
     * @var array
     */
    protected $expressions = [
        'study_activity' => '/Study Activity,?  : (.{1,}?)\. ([A-ZÆØÅ\-\d]+),/',
        
        //Edge case for study activity
        'study_assistance' => '/Study Activity,?  : (Study Assistance),/',

        'lector' => '/Name: ([^,]+)/',
        'programme' => '/Programme: ([^,]+)/',
        'course_type' => '/Course type: ([^,]+)/',
        'activity' => '/Activity: ([^,]+)/'
    ];

    /**
     * A map over which time edit fields matches this classes attributes
     * @var array
     */
    protected $attributeMap = [
        'activity' => 'activity',
        'study_activity' => 'studyActivities',
        'study_assistance' => 'studyActivities',
        'lector' => 'lectors',
        'programme' => 'programme',
        'course_type' => 'courseType'
    ];

    /**
     * @var \App\Calendar\Event
     */
    protected $event;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string|array
     */
    protected $studyActivities;

    /**
     * @var string|array
     */
    protected $activity;

    /**
     * @var string|array
     */
    protected $lectors;

    /**
     * @var string|array
     */
    protected $rooms;

    /**
     * @var string
     */
    protected $courseType;

    /**
     * @var string|array
     */
    protected $programme;

    public function id() : string
    {
        return $this->id;
    }

    public function studyActivities() : ?string
    {
        if (is_array($this->studyActivities)) {
            return natural_implode_unique($this->studyActivities);
        }

        return $this->studyActivities;
    }

    public function activity() : string
    {
        if (is_array($this->activity)) {
            $translatedActivities = $this->activity;

            foreach ($translatedActivities as $i => $activity) {
                $translatedActivities[$i] = __('calendar.activity.'.$activity);
            }

            return natural_implode_unique($translatedActivities);
        }

        if ($this->activity == "") {
            return "";
        }
        
        return __('calendar.activity.'.$this->activity);
    }

    public function lectors() : ?string
    {
        if (is_array($this->lectors)) {
            return natural_implode_unique($this->lectors);
        }

        return $this->lectors;
    }

    /**
     * Alias for lectors
     */
    public function lector() : string
    {
        return $this->lectors();
    }

    public function lectorPrefix() : string
    {
        return trans_choice('calendar.lectors', @count($this->lectors));
    }

    public function rooms() : string
    {
        if (is_array($this->rooms)) {
            return natural_implode_unique($this->rooms);
        }

        return $this->rooms;
    }

    /**
     * Alias for rooms
     */
    public function room() : string
    {
        return $this->rooms();
    }

    public function roomPrefix() : string
    {
        return trans_choice('calendar.rooms', @count($this->rooms));
    }

    public function courseType() : string
    {
        return $this->courseType;
    }

    public function programme() : ?string
    {
        if (is_array($this->programme)) {
            return natural_implode_unique($this->programme);
        }

        return $this->programme;
    }

    /**
     * Alias for programme
     */
    public function programmes() : ?string
    {
        return $this->programme();
    }

    public function __construct(Event $event)
    {
        $this->event = $event;

        $this->parse();
    }

    protected function parse() : void
    {
        //Fix inconsistencies in formatting
        $summary = str_replace("Study Activity,  :", "Study Activity  :", $this->event->summary);

        $attributes = $this->getAttributes($summary);

        /**
         * Assign each of the attributes to the correct class field.
         * @var [type]
         */
        foreach ($this->attributeMap as $attribute => $prop) {
            if (array_key_exists($attribute, $attributes)) {
                $this->$prop = $attributes[$attribute];
            }
        }

        //Handle ID seperately (since it's in the description field)
        $matches = [];
        if (preg_match("/ID (.+)/", $this->event->description, $matches)) {
            $this->id = $matches[1];
        }

        //Handle rooms seperately (since it's in the location field)
        $matches = [];
        if (preg_match_all("/Room: ([^,\\\]+)/", $this->event->location, $matches)) {
            array_shift($matches);
            $this->rooms = $matches[0];
        }
    }

    protected function getAttributes(string $summary) : array
    {
        $attributes = [];

        foreach ($this->expressions as $type => $expression) {
            if (preg_match_all($expression, $summary, $matches)) {
                foreach ($this->reorderMatches($matches) as $match) {
                    $parsedData = $this->formatMatchedData($type, $match);

                    if (!array_key_exists($type, $attributes)) {
                        $attributes[$type] = [$parsedData];
                    } else {
                        $attributes[$type][] = $parsedData;
                    }
                }
            }
        }

        return $this->flattenAttributes($attributes);
    }

    protected function flattenAttributes(array $attributes) : array
    {
        /**
         * If there's only one element in an array we flatten it
         */
        foreach ($attributes as $type => $attribute) {
            if (count($attribute) == 1) {
                $attributes[$type] = $attributes[$type][0];
            }
        }

        return $attributes;
    }

    protected function formatMatchedData(string $type, array $matches) : string
    {
        switch ($type) {
            case "activity":
                return strtolower($matches[1]);
            //Handle inconsistencies
            case "study_assistance":
                return "Study Assistance";
            case "study_activity":
            case "lector":
            case "programme":
            case "course_type":
                return $matches[1];
        }

        return "";
    }

    protected function reorderMatches(array $matches) : array
    {
        $newMatches = [];

        foreach ($matches as $j => $match) {
            for ($i = 0; $i < count($match); $i++) {
                $newMatches[$i][$j] = $match[$i];
            }
        }

        return $newMatches;
    }
}
