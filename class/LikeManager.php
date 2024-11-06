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
      // Tìm câu chuyện theo ID
      foreach ($this->data as &$story) {
         if ($story['id'] == $storyId) {
            // Kiểm tra trạng thái like và cập nhật số lượt like
            if ($hasLiked) {
               $story['likes']--; // Nếu đã like, giảm số lượt like
            } else {
               $story['likes']++; // Nếu chưa like, tăng số lượt like
            }
            $this->saveData();  // Ghi lại dữ liệu vào file JSON
            return $story['likes'];  // Trả về số lượt like mới
         }
      }
      return 0;  // Nếu không tìm thấy câu chuyện, trả về 0
   }

   public function getLikes($storyId)
   {
      // Trả về số lượt like của câu chuyện với ID
      foreach ($this->data as $story) {
         if ($story['id'] == $storyId) {
            return $story['likes'];
         }
      }
      return 0;  // Nếu không tìm thấy câu chuyện, trả về 0
   }
}
