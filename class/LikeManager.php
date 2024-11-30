<?php

require_once __DIR__ . '/User.php';

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

   public function toggleLikeForStory($storyId, $userId)
   {
      // Tìm câu chuyện với ID tương ứng
      foreach ($this->data as &$story) {
         if ($story['id'] == $storyId) {
            if (!isset($story['likes'])) {
               $story['likes'] = []; // Tạo mảng nếu chưa tồn tại
            }

            if (in_array($userId, $story['likes'])) {
               // Nếu userId đã tồn tại, gỡ bỏ (unlike)
               $story['likes'] = array_filter($story['likes'], function ($id) use ($userId) {
                  return $id !== $userId;
               });
            } else {
               // Nếu userId chưa tồn tại, thêm vào (like)
               $story['likes'][] = $userId;
            }

            $this->saveData();
            return count($story['likes']); // Trả về số lượng like hiện tại
         }
      }

      return 0; // Nếu không tìm thấy story, trả về 0
   }

   public function getLikes($storyId)
   {
      foreach ($this->data as $story) {
         if ($story['id'] == $storyId) {
            $likes = isset($story['likes']) ? $story['likes'] : [];
            return $likes; // Always return an array
         }
      }
      return []; // Return an empty array if the story is not found
   }

   public function getLikeCount($storyId)
   {
      $likes = $this->getLikes($storyId);
      return count($likes); // Trả về số lượng like
   }
}
