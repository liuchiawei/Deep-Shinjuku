document.getElementById("commentForm").addEventListener("submit", postComment);

function postComment(event) {
    event.preventDefault();

    var storyId = document.getElementById("commentForm").dataset.storyId;
    var author = document.getElementById("author").value;
    var content = document.getElementById("content").value;

    // Tạo XMLHttpRequest để gửi dữ liệu qua AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "story.php?id=" + storyId, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Bình luận đã được gửi thành công, làm sạch form
            document.getElementById("author").value = '';
            document.getElementById("content").value = '';
            
            // Phản hồi từ PHP (JSON) sẽ bao gồm comment_id
            var newComment = JSON.parse(xhr.responseText);
            
            // Lưu comment_id vào LocalStorage
            if (newComment.comment_id) {
                localStorage.setItem('commentId', newComment.comment_id);
            }

            // Hiển thị bình luận mới
            displayComment(newComment);
        }
    };

    // Gửi dữ liệu qua POST (bao gồm storyId, author, content)
    xhr.send("storyId=" + storyId + "&author=" + encodeURIComponent(author) + "&content=" + encodeURIComponent(content));
}

// Hàm hiển thị bình luận mới
function displayComment(comment) {
    var commentSection = document.getElementById("commentSection");

    var commentDiv = document.createElement("div");
    commentDiv.className = "comment";
    commentDiv.innerHTML = `<strong>${comment.author}</strong> (${comment.time}): ${comment.content}`;
    
    // Thêm bình luận mới vào đầu danh sách
    commentSection.insertBefore(commentDiv, commentSection.firstChild);
}

// Tải lại bình luận khi trang được tải
window.onload = function () {
    var storyId = document.getElementById("commentForm").dataset.storyId;
    const storedCommentId = localStorage.getItem('commentId');

    fetch("story.php?storyId=" + storyId, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ storyId: storyId, commentId: storedCommentId })
    })
    .then(response => response.json())
    .then(comments => {
        // Hiển thị các bình luận (chỉ 2 bình luận mới nhất)
        comments.slice(0, 2).forEach(function (comment) {
            displayComment(comment);
        });
    })
    .catch(error => console.error('Error', error));
};
