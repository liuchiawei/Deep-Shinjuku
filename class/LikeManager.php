<?php

class LikeManager {
   private $jsonFile;
   private $data;

   public function __construct($jsonFile) {
      $this->jsonFile = $jsonFile;
      $this->loadData();
   }

   private function loadData() {
      if (file_exists($this->jsonFile)) {
         $jsonData = file_get_contents($this->jsonFile);
         $this->data = json_decode($jsonData, true);
      } else {
         $this->data = ['likes' => 0];
      }
   }

   private function saveData() {
      file_put_contents($this->jsonFile, json_encode($this->data, JSON_PRETTY_PRINT));
   }

   public function like() {
      $this->data['likes']++;
      $this->saveData();
      return $this->data['likes'];
   }

   public function getLikes() {
      return $this->data['likes'];
   }
}

// Usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $likeManager = new LikeManager('/data/likes_data.json');
   $newLikesCount = $likeManager->like();
   echo $newLikesCount;
}

//TODO:Javascript でいいねボタンを押した時にリアルタイムで更新するようの機能を追加しよう
//codeは以下の通りと思います。

// function updateLikes() {
//    var xhr = new XMLHttpRequest();
//    xhr.open("POST", "update_likes.php", true);
//    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//    xhr.onreadystatechange = function () {
//        if (xhr.readyState === 4 && xhr.status === 200) {
//            document.getElementById("likeCount").innerText = xhr.responseText;
//        }
//    };
//    xhr.send();
// }