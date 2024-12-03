<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $storyId = $_POST['storyId'] ?? null;
   $hasLiked = filter_var($_POST['hasLiked'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

   if ($storyId === null || $hasLiked === null) {
      http_response_code(400);
      echo json_encode(['error' => 'Invalid input']);
      exit;
   }

   $filePath = '/data/likes_data.json';
   $data = json_decode(file_get_contents($filePath), true);

   if (!is_array($data)) {
      http_response_code(500);
      echo json_encode(['error' => 'Failed to read data']);
      exit;
   }

   foreach ($data as &$story) {
      if ($story['id'] == $storyId) {
         $story['likes'] += $hasLiked ? -1 : 1;
         break;
      }
   }

   file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));

   $updatedStory = array_filter($data, fn($s) => $s['id'] == $storyId);
   echo json_encode(['likes' => array_values($updatedStory)[0]['likes']]);
   exit;
}
