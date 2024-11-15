document.getElementById("commentForm").addEventListener("submit", postComment);

function postComment(event) {
    event.preventDefault();

    var storyId = document.getElementById("commentForm").dataset.storyId;
    var author = document.getElementById("author").value;
    var content = document.getElementById("comment").value;

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

                    // Nếu phản hồi có chứa comment_id, lưu vào LocalStorage
                    if (newComment && newComment.comment_id) {
                        localStorage.setItem("commentId_" + storyId, newComment.comment_id);
                        console.log("Comment ID saved to LocalStorage:", newComment.comment_id);
                    } else {
                        console.error("Comment ID not found in response:", xhr.responseText);
                    }

                    displayComment(newComment);
                } catch (e) {
                    console.error("error when parse JSON:", e);
                }
            } else {
                console.error("Error:", xhr.status, xhr.statusText);
            }
        }
    };

    xhr.send("storyId=" + storyId + "&author=" + encodeURIComponent(author) + "&content=" + encodeURIComponent(content));
}

// Hiển thị bình luận mới
function displayComment(comment) {
    var commentSection = document.getElementById("commentSection");

    var commentDiv = document.createElement("div");
    commentDiv.className = "comment";
    commentDiv.innerHTML = `<strong>${comment.author}</strong> (${comment.time}): ${comment.content}`;
    
    commentSection.insertBefore(commentDiv, commentSection.firstChild);
}


// Tải lại bình luận khi trang được tải
window.onload = function () {
    var storyId = document.getElementById("commentForm").dataset.storyId;
    var storedCommentId = localStorage.getItem("commentId_" + storyId);

    fetch("story.php?storyId=" + storyId, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ storyId: storyId, commentId: storedCommentId })
    })
    .then(response => response.json())
    .then(comments => {
        comments.slice(0, 2).forEach(function (comment) {
            displayComment(comment);
        });
    })
    .catch(error => console.error('Error', error));
};
