<?php

class LikeManager
{
   private $filePath;
   private $dataFilePath;
   private $data;
   public $likes = 0;

   public function __construct($filePath = __DIR__ . '/../data/likes_data.json', $dataFilePath = __DIR__ . '/../data/likes_data.json')
   {
      $this->filePath = $filePath;
      $this->dataFilePath = $dataFilePath;
      $this->loadLikes();
      $this->loadData();
   }

   public function loadData()
   {
      if (file_exists($this->dataFilePath)) {
         $this->data = json_decode(file_get_contents($this->dataFilePath), true) ?? [];
      } else {
         $this->data = [];
      }
   }

   public function loadLikes()
   {
      if (file_exists($this->filePath)) {
         $this->likes = json_decode(file_get_contents($this->filePath), true) ?? 0;
      } else {
         $this->likes = 0;
      }
   }

   public function saveData()
   {
      // Save story data to the JSON file
      if (!file_put_contents($this->dataFilePath, json_encode($this->data, JSON_PRETTY_PRINT))) {
         error_log("ファイルを書き込めません: " . $this->dataFilePath);
      } else {
         echo "Data has been written to the file successfully.";
      }
   }

   public function saveLikes()
   {
      // Save total like count to the likes_data.json file
      if (!file_put_contents($this->filePath, json_encode($this->likes, JSON_PRETTY_PRINT))) {
         error_log("ファイルを書き込めません: " . $this->filePath);
      } else {
         echo "Like count has been written to the file successfully.";
      }
   }

   public function toggleLikeForStory($storyId, $hasLiked)
   {
      foreach ($this->data as &$story) {
         if ($story['id'] == $storyId) {
            if (!isset($story['likes'])) {
               $story['likes'] = 0;
            }
            if ($hasLiked) {
               $story['likes']--;
            } else {
               $story['likes']++;
            }

            // Save updated story data
            $this->saveData();

            // Update the global likes count and save it
            $this->likes = array_sum(array_column($this->data, 'likes'));
            $this->saveLikes();

            return $story['likes'];
         }
      }
      return 0;
   }

   public function getLikes($storyId)
   {
      foreach ($this->data as $story) {
         if ($story['id'] == $storyId) {
            return $story['likes'] ?? '∞';
         }
      }
      return 0;
   }
}
