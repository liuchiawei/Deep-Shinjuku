<?php

class Story
{
    public int $id;
    public string $title;
    public string $location;
    public string $content;

    public function __construct(int $id, string $title, string $location, string $content)
    {
        $this->id = $id;
        $this->title = $title;
        $this->location = $location;
        $this->content = $content;
    }
}

?>
