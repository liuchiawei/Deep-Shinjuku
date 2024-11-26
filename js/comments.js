document.getElementById("commentForm").addEventListener("submit", postComment);

function postComment(event) {
    event.preventDefault();

    const storyId = document.getElementById("commentForm").dataset.storyId;
    const author = document.getElementById("author").value;
    const content = document.getElementById("content").value;

    console.log(storyId, author, content);

    fetch("story-api.php?id=" + storyId, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `storyId=${storyId}&author=${encodeURIComponent(author)}&content=${encodeURIComponent(content)}`
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json(); // Parse JSON from response body
        })
        .then(newComment => {
            if (newComment && newComment.comment_id) {
                //Save comment_id to LocalStorage
                addCommentToLocalStorage(newComment.comment_id);

                console.log("Comment ID saved to LocalStorage:", newComment.comment_id);

                // Reset form
                document.getElementById("author").value = '';
                document.getElementById("content").value = '';
            } else {
                console.error("Invalid response from server:", newComment);
            }
        })
        .catch(error => {
            console.error("Error during fetch:", error);
        });
}

function addCommentToLocalStorage(commentId) {
    let savedCommentIds = JSON.parse(localStorage.getItem("commentIds")) || [];
    if (!savedCommentIds.includes(commentId)) {
        savedCommentIds.push(commentId);
        localStorage.setItem("commentIds", JSON.stringify(savedCommentIds));
    }
}

function canEditOrDeleteComment(commentId) {
    const savedCommentIds = JSON.parse(localStorage.getItem("commentIds")) || [];
    return savedCommentIds.includes(commentId);
}

// // Hàm hiển thị comment (giả sử bạn đã có một container để hiển thị comment)
// function displayComment(comment) {
//     const commentContainer = document.getElementById("allComments");
//     const commentHTML = `
//         <div class="comment" id="comment-${comment.comment_id}">
//             <p><strong>${comment.author}</strong> (${comment.time}):</p>
//             <p>${comment.content}</p>
//         </div>
//     `;
//     commentContainer.insertAdjacentHTML("beforeend", commentHTML);
// }


// //新しいコメントを表示する
// function displayComment(comment) {
//     var commentSection = document.getElementById("commentSection");

//     var commentDiv = document.createElement("div");
//     commentDiv.className = "comment";
//     commentDiv.innerHTML = `<strong>${comment.author}</strong> (${comment.time}): ${comment.content}`;

//     commentSection.insertBefore(commentDiv, commentSection.firstChild);
// }

// function displayAllComments(comments) {
//     var allCommentsDiv = document.getElementById("allComments");
//     allCommentsDiv.innerHTML = "";

//     comments.forEach(function (comment) {
//         var commentDiv = document.createElement("div");
//         commentDiv.className = "comment";

//         var authorDiv = document.createElement("div");
//         authorDiv.className = "author";
//         authorDiv.textContent = comment.author;

//         var contentDiv = document.createElement("div");
//         contentDiv.className = "content";
//         contentDiv.textContent = comment.content;

//         commentDiv.appendChild(authorDiv);
//         commentDiv.appendChild(contentDiv);

//         allCommentsDiv.appendChild(commentDiv);
//     });
// }





// window.onload = function () {
//     var storyId = document.getElementById("commentForm").dataset.storyId;
//     var storedCommentId = localStorage.getItem("commentId_" + storyId);

//     fetch("story.php?storyId=" + storyId, {
//         method: "POST",
//         headers: {
//             "Content-Type": "application/json"
//         },
//         body: JSON.stringify({ storyId: storyId, commentId: storedCommentId })
//     })
//     .then(response => response.json())
//     .then(comments => {
//         comments.slice(0, 2).forEach(function (comment) {
//             displayComment(comment);
//         });
//     })
//     .catch(error => console.error('Error', error));
// };
