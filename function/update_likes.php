<?php
require_once __DIR__ . '../class/LikeManager.php';

// Nhận trạng thái like và storyId từ yêu cầu POST
$hasLiked = $_POST['hasLiked'] ?? 'false';
$storyId = $_POST['storyId'] ?? null;  // Lấy storyId từ yêu cầu POST

// Kiểm tra xem storyId có hợp lệ không
if ($storyId !== null) {
   // Khởi tạo LikeManager với đường dẫn đến file JSON
   $likeManager = new LikeManager(__DIR__ . '../data/likes_data.json');

   // Gọi hàm toggleLike để cập nhật số lượt like cho câu chuyện cụ thể
   $newLikesCount = $likeManager->toggleLikeForStory($storyId, $hasLiked);  // Sử dụng phương thức mới toggleLikeForStory

   // Trả về số lượt like mới dưới dạng phản hồi AJAX
   echo $newLikesCount;
} else {
   echo "Error: Invalid story ID.";
}
