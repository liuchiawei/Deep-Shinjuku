<?php
require_once __DIR__ . '/class/StoryData.php';
require_once __DIR__ . '/class/LikeManager.php';
require_once __DIR__ . '/class/CommentManager.php';
require_once __DIR__ . '/api/Interaction.php';

session_start();

header('Content-Type: application/json');

$nextStory = $storyData->getById($story->id + 1 <= $maxId ? $story->id + 1 : 1);
$prevStory = $storyData->getById($story->id - 1 > 0 ? $story->id - 1 : $maxId);

$commentManager = new CommentManager(__DIR__ . 'data/likes_data.json');
$comments = $commentManager->getComments($story->id);

$userComments = isset($_COOKIE['user_comments']) ? explode(',', $_COOKIE['user_comments']) : [];

// Xử lý xóa bình luận
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment_id'])) {
    $commentId = $_POST['delete_comment_id'];
    $commentManager->deleteComment($storyId, $commentId);
}

// Xử lý chỉnh sửa bình luận
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_comment_id'])) {
    $commentId = $_POST['edit_comment_id'];
    $newContent = $_POST['new_content'];
    $commentManager->editComment($storyId, $commentId, $newContent);
}
