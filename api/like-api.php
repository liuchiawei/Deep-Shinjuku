<?php
require_once __DIR__ . '/../class/LikeManager.php';

$likeManager = new LikeManager(__DIR__ . '/data/likes_data.json');

$data = json_decode(file_get_contents('php://input'), true);
$storyId = $data['storyId'];
$hasLiked = $data['hasLiked'];

$newLikeCount = $likeManager->toggleLikeForStory($storyId, $hasLiked);

echo json_encode(['success' => true, 'newLikeCount' => $newLikeCount]);