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
         $this->data = ['likes' => 0];
      }
   }

   private function saveData()
   {
      file_put_contents($this->jsonFile, json_encode($this->data, JSON_PRETTY_PRINT));
   }

   public function toggleLike($hasLiked)
   {
      if ($hasLiked === "true") {
         //likeしたら減らす
         $this->data['likes']--;
      } else {
         //likeしてなかったら増やす
         $this->data['likes']++;
      }
      $this->saveData();
      return $this->data['likes'];
   }

   public function getLikes()
   {
      return $this->data['likes'];
   }
}

//usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $hasLiked = $_POST['hasLiked'];
   $likeManager = new LikeManager('/data/likes_data.json');
   $newLikesCount = $likeManager->toggleLike($hasLiked);
   echo $newLikesCount;
}

//TODO:以下のJavascriptコードをHTMLファイルに追加
// function updateLikes() {
//localStorageでlikeの状態を保存
//    var hasLiked = localStorage.getItem("hasLiked") === "true";
   
//    var xhr = new XMLHttpRequest();
//    xhr.open("POST", "update_likes.php", true);
//    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//    xhr.onreadystatechange = function () {
//        if (xhr.readyState === 4 && xhr.status === 200) {
//            document.getElementById("likeCount").innerText = xhr.responseText;
//            // localStorageの値を更新
//            if (hasLiked) {
//                localStorage.setItem("hasLiked", "false"); // いいねしてない
//            } else {
//                localStorage.setItem("hasLiked", "true"); // いいねしてる
//            }
//        }
//    };
   
//    xhr.send("hasLiked=" + hasLiked);
// }

// document.getElementById("likeButton").addEventListener("click", updateLikes);

// // ページ読み込み時にlocalStorageの値を読み込んでボタンのテキストを更新
// window.onload = function() {
//    var hasLiked = localStorage.getItem("hasLiked") === "true";
//    document.getElementById("likeButton").innerText = hasLiked ? "Unlike" : "Like";
// };