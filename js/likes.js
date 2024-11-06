document.getElementById("likeButton").addEventListener("click", updateLikes);

function updateLikes(event) {
   event.preventDefault(); // Ngăn chặn hành động mặc định của form (nếu có)

   // Lấy storyId từ thuộc tính data của nút like
   var storyId = document.getElementById("likeButton").dataset.storyId;

   // Lấy trạng thái like từ localStorage dựa trên storyId, mặc định là false
   var hasLiked = localStorage.getItem("hasLiked_" + storyId) === "true";

   // Cập nhật trực tiếp giao diện, thay đổi số lượt like mà không chờ phản hồi từ server
   var likeCountElement = document.getElementById("likeCount");
   var currentLikeCount = parseInt(likeCountElement.innerText);

   // Thực hiện thay đổi số lượt like ngay lập tức
   if (hasLiked) {
      likeCountElement.innerText = currentLikeCount - 1;
   } else {
      likeCountElement.innerText = currentLikeCount + 1;
   }

   // Gửi yêu cầu AJAX đến server để cập nhật likes trong JSON
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "story.php?id=" + storyId, true);  // Gửi POST đến chính file story.php
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
         // Cập nhật trạng thái like vào localStorage dựa trên storyId
         localStorage.setItem("hasLiked_" + storyId, !hasLiked);  // Đảo ngược trạng thái like

         // Cập nhật nội dung của nút "Like" hoặc "Unlike"
         document.getElementById("likeButton").innerText = hasLiked ? "Like" : "Unlike";
      }
   };

   // Gửi giá trị trạng thái "hasLiked" và "storyId" tới server
   xhr.send("hasLiked=" + (hasLiked ? 'true' : 'false') + "&storyId=" + storyId);
}

// Khi tải trang, lấy trạng thái "hasLiked" từ localStorage dựa trên storyId và cập nhật nút
window.onload = function () {
   var storyId = document.getElementById("likeButton").dataset.storyId;
   var hasLiked = localStorage.getItem("hasLiked_" + storyId) === "true";
   document.getElementById("likeButton").innerText = hasLiked ? "Unlike" : "Like";
};
