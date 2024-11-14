document.getElementById("likeButton").addEventListener("click", updateLikes);

function updateLikes(event) {
   event.preventDefault();

   var storyId = document.getElementById("likeButton").dataset.storyId;

   //LocalStorageからhasLikedの値を取得
   var hasLiked = localStorage.getItem("hasLiked_" + storyId) === "true";

   //Likeの数をアップデートする
   var likeCountElement = document.getElementById("likeCount");
   var currentLikeCount = parseInt(likeCountElement.innerText);

   //Likeの数をアップデートする
   if (hasLiked) {
      likeCountElement.innerText = currentLikeCount - 1;
   } else {
      likeCountElement.innerText = currentLikeCount + 1;
   }

   //AJAXリクエストを送信
   var xhr = new XMLHttpRequest();
   xhr.open("POST", "story.php?id=" + storyId, true);  //story.phpにPOSTリクエストを送信
   xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
         //LocalStorageに保存
         localStorage.setItem("hasLiked_" + storyId, !hasLiked);

         //LikeかUnlikeかを切り替える
         document.getElementById("likeButton").innerText = hasLiked ? "Like" : "Unlike";
      }
   };

   xhr.send("hasLiked=" + (hasLiked ? 'true' : 'false') + "&storyId=" + storyId);
}

//ページが読み込まれた時に、LikeかUnlikeかを切り替える
window.onload = function () {
   var storyId = document.getElementById("likeButton").dataset.storyId;
   var hasLiked = localStorage.getItem("hasLiked_" + storyId) === "true";
   document.getElementById("likeButton").innerText = hasLiked ? "Unlike" : "Like";
};
