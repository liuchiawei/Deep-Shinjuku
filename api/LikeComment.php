<?php

$storyData = new StoryData();
$story = $storyData->getById($_GET['id']);
$maxId = $storyData->getMaxId();
$storyId = $_GET['id'] ?? null;

if ($storyId !== null) {
   //今のストーリーのLike数を取得
   $likeManager = new LikeManager('./data/likes_data.json');
   $likeCount = $likeManager->getLikes($storyId);
   //今のユーザーがLikeしているかどうかを取得
   $hasAlreadyLiked = isset($_SESSION['liked_stories'][$storyId]) ? true : false;

   if (isset($_POST['hasLiked'])) {
      $hasLiked = $_POST['hasLiked'] === 'true';
      $newLikeCount = $likeManager->toggleLikeForStory($storyId, $hasLiked);
      echo $newLikeCount;
   }
}

$commentManager = new CommentManager('./data/likes_data.json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $storyId = $_POST['storyId'];
   $author = $_POST['author'];
   $content = $_POST['content'];

   $newComment = $commentManager->addComment($storyId, $author, $content);
   echo json_encode($newComment);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['storyId'])) {
   $storyId = $_GET['storyId'];
   $comments = $commentManager->getComments($storyId);
   $commentCount = count($comments);
   $displayedComments = [];

   $input = json_decode(file_get_contents('php://input'), true);
   $storedCommentId = $input['commentId'] ?? null;

   if ($storedCommentId) {
      foreach ($comments as $comment) {
         if ($comment['comment_id'] == $storedCommentId) {
            $displayedComments[] = $comment;
            break;
         }
      }
   }

   foreach ($comments as $comment) {
      if (count($displayedComments) >= 2) {
         break;
      }
      if (!in_array($comment, $displayedComments)) {
         $displayedComments[] = $comment;
      }
   }

   echo json_encode($displayedComments);
}
