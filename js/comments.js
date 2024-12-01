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
