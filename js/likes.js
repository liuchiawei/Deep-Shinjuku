document.getElementById("likeButton").addEventListener("click", updateLikes);

function updateLikes() {
   // Lấy trạng thái like từ localStorage
   var hasLiked = localStorage.getItem("hasLiked") === "true";

   // Lấy storyId từ thuộc tính data của nút like
   var storyId = document.getElementById("likeButton").dataset.storyId;

   // Gửi yêu cầu AJAX đến server để cập nhật likes
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "update_likes.php", true);
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
         // Cập nhật số lượng like trên giao diện
         document.getElementById("likeCount").innerText = xhr.responseText;

         // Lưu lại trạng thái like vào localStorage
         if (hasLiked) {
            localStorage.setItem("hasLiked", "false"); // Hủy like
         } else {
            localStorage.setItem("hasLiked", "true");  // Đã like
         }

         // Cập nhật nội dung của nút "Like" hoặc "Unlike"
         document.getElementById("likeButton").innerText = hasLiked ? "Like" : "Unlike";
      }
   };

   // Gửi giá trị trạng thái "hasLiked" và "storyId" tới server
   xhr.send("hasLiked=" + hasLiked + "&storyId=" + storyId);
}

// Khi tải trang, lấy trạng thái "hasLiked" từ localStorage và cập nhật nút
window.onload = function () {
   var hasLiked = localStorage.getItem("hasLiked") === "true";
   document.getElementById("likeButton").innerText = hasLiked ? "Unlike" : "Like";
};
