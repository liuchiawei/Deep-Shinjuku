document.getElementById("commentForm").addEventListener("submit", postComment);

function postComment(event) {
    event.preventDefault();

    var storyId = document.getElementById("commentForm").dataset.storyId;
    var author = document.getElementById("author").value;
    var content = document.getElementById("content").value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "story.php?id=" + storyId, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {

                document.getElementById("author").value = '';
                document.getElementById("content").value = '';

                try {
                    var newComment = JSON.parse(xhr.responseText);

                    if (newComment && newComment.comment_id) {
                        localStorage.setItem("commentId_" + storyId, newComment.comment_id);
                        console.log("Comment ID saved to LocalStorage:", newComment.comment_id);
                    } else {
                        console.error("Comment ID not found in response:", xhr.responseText);
                    }

                    displayComment(newComment);
                } catch (e) {
                    console.error("Error when parsing JSON:", e, "Response:", xhr.responseText);
                }
            } else {
                console.error("Error:", xhr.status, xhr.statusText);
            }
        }
    };

    xhr.send("storyId=" + storyId + "&author=" + encodeURIComponent(author) + "&content=" + encodeURIComponent(content));
}


//新しいコメントを表示する
function displayComment(comment) {
    var commentSection = document.getElementById("commentSection");

    var commentDiv = document.createElement("div");
    commentDiv.className = "comment";
    commentDiv.innerHTML = `<strong>${comment.author}</strong> (${comment.time}): ${comment.content}`;
    
    commentSection.insertBefore(commentDiv, commentSection.firstChild);
}

function displayAllComments(comments) {
    var allCommentsDiv = document.getElementById("allComments");
    allCommentsDiv.innerHTML = ""; // Xóa các nội dung cũ nếu có

    comments.forEach(function(comment) {
        var commentDiv = document.createElement("div");
        commentDiv.className = "comment";

        var authorDiv = document.createElement("div");
        authorDiv.className = "author";
        authorDiv.textContent = comment.author;

        var contentDiv = document.createElement("div");
        contentDiv.className = "content";
        contentDiv.textContent = comment.content;

        commentDiv.appendChild(authorDiv);
        commentDiv.appendChild(contentDiv);

        allCommentsDiv.appendChild(commentDiv);
    });
}

document.getElementById("seeALLBtn").addEventListener("click", loadComments);

// Hàm để gọi API và lấy danh sách các bình luận
function loadComments() {
    // Get storyId from a valid source (e.g., dataset attribute or from the form)
    var storyId = document.getElementById("commentForm").dataset.storyId;
    
    // Log the storyId to ensure it's being set correctly
    console.log("Loading comments for story ID:", storyId);

    // Ensure storyId is valid before making the API call
    if (!storyId) {
        console.error("Story ID is not available.");
        return;
    }

    fetch("story.php?storyId=" + storyId)
        .then(response => response.json())
        .then(comments => {
            displayAllComments(comments);
        })
        .catch(error => console.error("Error loading comments:", error));
}




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
