document.getElementById("likeButton").addEventListener("click", updateLikes);

function updateLikes() {
   var storyId = document.getElementById("likeButton").dataset.storyId;

   // Kiểm tra trạng thái đăng nhập qua cookie
   var userId = document.cookie.split('; ').find(row => row.startsWith('user_id='));

   if (!userId) {
      // Hiển thị popup nếu chưa đăng nhập
      showLoginPopup();
      return;
   }

   // Nếu đã đăng nhập, tiếp tục xử lý like
   handleLike(storyId);
}

function handleLike(storyId) {
   var likeCountElement = document.getElementById("likeCount");
   var currentLikeCount = parseInt(likeCountElement.innerText);

   var hasLiked = localStorage.getItem("hasLiked_" + storyId) === "true";

   if (hasLiked) {
      likeCountElement.innerText = currentLikeCount - 1;
   } else {
      likeCountElement.innerText = currentLikeCount + 1;
   }

   var xhr = new XMLHttpRequest();
   xhr.open("POST", "story.php", true);
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
         localStorage.setItem("hasLiked_" + storyId, !hasLiked);

         var likeButton = document.getElementById("likeButton");
         likeButton.innerHTML = !hasLiked
            ? '<i class="bi bi-heart-fill"></i>'
            : '<i class="bi bi-heart"></i>';
      }
   };

   xhr.send("hasLiked=" + (!hasLiked ? "true" : "false") + "&storyId=" + storyId);
}

function showLoginPopup() {
   document.getElementById("loginPopup").style.display = "block";
   document.getElementById("overlay").style.display = "block";
}

function closeLoginPopup() {
   document.getElementById("loginPopup").style.display = "none";
   document.getElementById("overlay").style.display = "none";
}

function redirectToLogin() {
   window.location.href = "/login-page";
}

document.getElementById("closePopup").addEventListener("click", closeLoginPopup);
