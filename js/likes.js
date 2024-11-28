document.getElementById("likeButton").addEventListener("click", updateLikes);

function updateLikes() {

   var storyId = document.getElementById("likeButton").dataset.storyId;

   var hasLiked = localStorage.getItem("hasLiked_" + storyId) === "true";

   var likeCountElement = document.getElementById("likeCount");
   var currentLikeCount = parseInt(likeCountElement.innerText);

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

window.onload = function () {
   var storyId = document.getElementById("likeButton").dataset.storyId;
   var hasLiked = localStorage.getItem("hasLiked_" + storyId) === "true";

   var likeButton = document.getElementById("likeButton");
   likeButton.innerHTML = hasLiked
      ? '<i class="bi bi-heart-fill"></i>'
      : '<i class="bi bi-heart"></i>';
};