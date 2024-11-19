<?php
require_once __DIR__ . '/../class/StoryData.php';
require_once __DIR__ . '/../class/LikeManager.php';
require_once __DIR__ . '/../api/Interaction.php';

$storyData = new StoryData();
$story = $storyData->getById($_GET['id']);

$maxId = $storyData->getMaxId();

$storyId = $_GET['id'] ?? null;

if ($storyId !== null) {
   //今のLike数を取得
   $likeManager = new LikeManager('/../data/likes_data.json');
   $likeCount = $likeManager->getLikes($storyId);

   //Like済みかどうかを取得
   $hasAlreadyLiked = isset($_SESSION['liked_stories'][$storyId]) ? true : false;

   if (isset($_POST['hasLiked'])) {
      $hasLiked = $_POST['hasLiked'] === 'true';
      $newLikeCount = $likeManager->toggleLikeForStory($storyId, $hasLiked);

      echo $newLikeCount;
   }
}
