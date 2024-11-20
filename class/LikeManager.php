<?php

class LikeManager
{
   private $jsonFile;
   private $data;

   public function __construct($jsonFile)
   {
      $this->jsonFile = $jsonFile;
      $this->loadData();
   }

   private function loadData()
   {
      if (file_exists($this->jsonFile)) {
         $jsonData = file_get_contents($this->jsonFile);
         $this->data = json_decode($jsonData, true);
      } else {
         $this->data = [];
      }
   }

   private function saveData()
   {
      file_put_contents($this->jsonFile, json_encode($this->data, JSON_PRETTY_PRINT));
   }

   public function toggleLikeForStory($storyId, $hasLiked)
   {
      //IDに一致するストーリーを検索
      foreach ($this->data as &$story) {
         if ($story['id'] == $storyId) {
            if ($hasLiked) {
               $story['likes']--; //Like済みの場合、Like数を減らす
            } else {
               $story['likes']++; //Likeしていない場合、Like数を増やす
            }
            $this->saveData();
            return $story['likes'];
         }
      }
      return 0;
   }

   public function getLikes($storyId)
   {
      foreach ($this->data as $story) {
         if ($story['id'] == $storyId) {
            return $story['likes'];
         }
      }
      return 0;
   }
}