<?php
require_once __DIR__ . '/class/StoryData.php';
require_once __DIR__ . '/class/LikeManager.php';
require_once __DIR__ . '/class/CommentManager.php';
require_once __DIR__ . '/api/Interaction.php';

session_start();

header('Content-Type: application/json');

$nextStory = $storyData->getById($story->id + 1 <= $maxId ? $story->id + 1 : 1);
$prevStory = $storyData->getById($story->id - 1 > 0 ? $story->id - 1 : $maxId);

$commentManager = new CommentManager(__DIR__ . '/data/likes_data.json');
$comments = $commentManager->getComments($story->id);