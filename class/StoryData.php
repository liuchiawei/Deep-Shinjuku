<?php

require_once __DIR__ . '/story.php';

class StoryData
{
    public string $file = __DIR__ . '/../data/stories.csv';

    /** @return Story[] */
    // public function getAll(): array
    // {
    //     $handle = fopen($this->file, 'r');
    //     $stories = [];

    //     while (!feof($handle)) {
    //         $row = fgetcsv($handle);

    //         if ($row === false || is_null($row[0])) {
    //             break;
    //         }

    //         $story = new Story(intval($row[0]), $row[1], $row[2], $row[3]);

    //         $stories[] = $story;
    //     }

    //     fclose($handle);

    //     return $stories;
    // }

    /** @return Story[] */
    public function getAll(): array
{
    $jsonContent = file_get_contents($this->file);
    $data = json_decode($jsonContent, true);
    $stories = [];

    foreach ($data as $row) {
        $story = new Story(intval($row['id']), $row['title'], $row['location'], $row['content']);
        $stories[] = $story;
    }

    return $stories;
}

    public function getById(int $id): ?Story
    {
        $stories = $this->getAll();

        // IDに一致する日記を返す
        foreach ($stories as $story) {
            if ($story->id === $id) {
                return $story;
            }
        }
        // 一致するものがなっかたら null
        return null;
    }

    public function getMaxId(): int
    {
        $stories = $this->getAll();
        return max(array_map(fn ($story) => $story->id, $stories));
    }
}

?>
