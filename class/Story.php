<?php

class Story
{
    public int $id;
    public string $title;
    public string $location;
    public string $locationId;
    public string $content;
    public string $explanation;
    public string $brief;
    public string $date;
    public int $rating;

    public function __construct(int $id, string $title, string $location, int $locationId, string $content, string $explanation, string $brief, string $date, int $rating)
    {
        $this->id = $id;
        $this->title = $title;
        $this->location = $location;
        $this->locationId = $locationId;
        $this->content = $content;
        $this->explanation = $explanation;
        $this->brief = $brief;
        $this->date = $date;
        $this->rating = $rating;
    }
}
