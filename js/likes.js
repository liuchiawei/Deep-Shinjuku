document.addEventListener("DOMContentLoaded", () => {
   let userID = localStorage.getItem("userID");
   if (!userID) {
      userID = `user_${Date.now()}`;
      localStorage.setItem("userID", userID);
   }
});
const getUserID = () => localStorage.getItem("userID");

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
   event.preventDefault();

   var storyId = document.getElementById("likeButton").dataset.storyId;

   var hasLiked = getCookie("hasLiked_" + storyId) === "true";

   var likeCountElement = document.getElementById("likeCount");
   var currentLikeCount = parseInt(likeCountElement.innerText);

   if (hasLiked) {
      likeCountElement.innerText = currentLikeCount - 1;
   } else {
      likeCountElement.innerText = currentLikeCount + 1;
   }

   var xhr = new XMLHttpRequest();
   xhr.open("POST", "story.php?id=" + storyId, true);
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
         setCookie("hasLiked_" + storyId, !hasLiked, 365);

         document.getElementById("likeButton").innerHTML = hasLiked ? '<i class="bi bi-heart"></i>' : '<i class="bi bi-heart-fill"></i>';
      }
   };

   xhr.send("hasLiked=" + (hasLiked ? 'true' : 'false') + "&storyId=" + storyId);
}
