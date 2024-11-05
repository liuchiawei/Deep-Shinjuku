<?php

class Story
{
    public int $id;
    public string $title;
    public string $location;
    public string $content;
    public string $explanation;

    public function __construct(int $id, string $title, string $location, string $content, string $explanation)
    {
        $this->id = $id;
        $this->title = $title;
        $this->location = $location;
        $this->content = $content;
        $this->explanation = $explanation;
    }
}
