document.getElementById("likeButton").addEventListener("click", updateLikes);

function updateLikes(event) {
   event.preventDefault();

   // Lấy `storyId` từ dataset của nút
   var storyId = document.getElementById("likeButton").dataset.storyId;

   // Lấy trạng thái `hasLiked` từ LocalStorage
   var hasLiked = localStorage.getItem("hasLiked_" + storyId) === "true";

   // Cập nhật số lượt thích trong giao diện
   var likeCountElement = document.getElementById("likeCount");
   var currentLikeCount = parseInt(likeCountElement.innerText);

   if (hasLiked) {
      likeCountElement.innerText = currentLikeCount - 1;
   } else {
      likeCountElement.innerText = currentLikeCount + 1;
   }

   // Gửi yêu cầu AJAX đến máy chủ
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "story.php", true); // Gửi POST đến story.php
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
         // Cập nhật trạng thái trong LocalStorage
         localStorage.setItem("hasLiked_" + storyId, !hasLiked);

         // Cập nhật giao diện nút
         var likeButton = document.getElementById("likeButton");
         likeButton.innerHTML = !hasLiked
            ? '<i class="bi bi-heart-fill"></i>'
            : '<i class="bi bi-heart"></i>';
      }
   };

   // Gửi dữ liệu đến máy chủ
   xhr.send("hasLiked=" + (!hasLiked ? "true" : "false") + "&storyId=" + storyId);
}

// Khi tải trang, thiết lập trạng thái của nút
window.onload = function () {
   var storyId = document.getElementById("likeButton").dataset.storyId;
   var hasLiked = localStorage.getItem("hasLiked_" + storyId) === "true";

   var likeButton = document.getElementById("likeButton");
   likeButton.innerHTML = hasLiked
      ? '<i class="bi bi-heart-fill"></i>'
      : '<i class="bi bi-heart"></i>';
};
