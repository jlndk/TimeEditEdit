<?php

namespace App\Calendar;

class TimeEditParser
{
    /**
     * The list of regular expression for each segment of the given text.
     * @var array
     */
    protected $expressions = [
        'study_activity' => '/Study Activity  : ([^\.]+)\.? ([^,]+)/',
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
     * @var string
     */
    protected $activity;

    /**
     * @var string|array
     */
    protected $lectors;

    /**
     * @var string
     */
    protected $courseType;

    /**
     * @var string
     */
    protected $programme;

    public function id()
    {
        return $this->id;
    }

    public function studyActivities()
    {
        if (is_array($this->studyActivities)) {
            return implode(', ', $this->studyActivities);
        }

        return $this->studyActivities;
    }

    public function activity()
    {
        //If the event is Study Assistance we should not display an activity type
        if ($this->studyActivities() == "Study Assistance") {
            return "";
        }

        if (is_array($this->activity)) {
            return implode(', ', $this->activity);
        }

        return $this->activity;
    }

    public function lectors()
    {
        if (is_array($this->lectors)) {
            $lastItem = array_pop($this->lectors);
            $lectors = implode(', ', $this->lectors);
            $lectors .= ' & '.$lastItem;
            return $lectors;
        }

        return $this->lectors;
    }

    public function lectorPrefix()
    {
        $lectorPrefix = "Lektor: ";

        if (@count($this->lectors) > 1) {
            $lectorPrefix = "Lektorer: ";
        }

        return $lectorPrefix;
    }

    /**
     * Alias for lectors
     */
    public function lector()
    {
        return $this->lectors();
    }

    public function courseType()
    {
        return $this->courseType;
    }

    public function programme()
    {
        return $this->programme;
    }

    public function __construct(Event $event)
    {
        $this->event = $event;

        $this->parse();
    }

    protected function parse()
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

        /**
         * Since id is in the description instead of the title, we need to
         * parse it seperately
         */
        $matches = [];
        if (preg_match("/ID (.+)/", $this->event->description, $matches)) {
            $this->id = $matches[1];
        }
    }

    protected function getAttributes($summary)
    {
        //Break the string into an array of segments (maked by a ,)
        $parts = explode(",", $summary);

        $attributes = [];

        foreach($parts as $part) {
            foreach ($this->expressions as $type => $expression) {
                if (preg_match($expression, $part, $matches)) {
                    $parsedData = $this->formatMatchedData($type, $matches);

                    if (!array_key_exists($type, $attributes)) {
                        $attributes[$type] = [$parsedData];
                    }
                    else {
                        $attributes[$type][] = $parsedData;
                    }
                }
            }
        }

        return $this->flattenAttributes($attributes);
    }

    protected flattenAttributes($attributes)
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

    protected function formatMatchedData($type, $matches)
    {
        switch ($type)
        {
            case "activity":
                if($matches[1] == "Lecture") {
                    return "Forelæsning";
                }

                return $matches[1];
            case "study_activity":
                //Handle inconsistencies
                if($matches[1] == "Study" && $matches[2] == "Assistance") {
                    return "Study Assistance";
                }

                return $matches[1];
            case "lector":
            case "programme":
            case "course_type":
                return $matches[1];
        }

        return [];
    }
}
