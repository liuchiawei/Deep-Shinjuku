document.getElementById("likeButton").addEventListener("click", updateLikes);

// Function to set a cookie
function setCookie(name, value, days) {
   var expires = "";
   if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toUTCString();
   }
   document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

// Function to get a cookie
function getCookie(name) {
   var nameEQ = name + "=";
   var ca = document.cookie.split(';');
   for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
   }
   return null;
}

function updateLikes(event) {
   event.preventDefault(); // Ngăn chặn hành động mặc định của form (nếu có)

   // Lấy storyId từ thuộc tính data của nút like
   var storyId = document.getElementById("likeButton").dataset.storyId;

   // Get the like state from cookies instead of localStorage
   var hasLiked = getCookie("hasLiked_" + storyId) === "true";

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
         // Update the like state in cookies instead of localStorage
         setCookie("hasLiked_" + storyId, !hasLiked, 365);  // Store for 1 year

         // Cập nhật nội dung của nút "Like" hoặc "Unlike"
         document.getElementById("likeButton").innerHTML = hasLiked ? '<i class="bi bi-heart"></i>' : '<i class="bi bi-heart-fill"></i>';
      }
   };

   // Gửi giá trị trạng thái "hasLiked" và "storyId" tới server
   xhr.send("hasLiked=" + (hasLiked ? 'true' : 'false') + "&storyId=" + storyId);
}

// Khi tải trang, lấy trạng thái "hasLiked" từ localStorage dựa trên storyId và cập nhật nút
window.onload = function () {
   var storyId = document.getElementById("likeButton").dataset.storyId;
   var hasLiked = getCookie("hasLiked_" + storyId) === "true";
   document.getElementById("likeButton").innerHTML = hasLiked ? '<i class="bi bi-heart-fill"></i>' : '<i class="bi bi-heart"></i>';
};