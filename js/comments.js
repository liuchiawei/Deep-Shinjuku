document.getElementById("commentForm").addEventListener("submit", postComment);

function postComment(event) {
    event.preventDefault();

    var storyId = document.getElementById("commentForm").dataset.storyId;
    var author = document.getElementById("author").value;
    var content = document.getElementById("content").value;

    // Tạo AJAX request để gửi bình luận đến PHP
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "story.php?id=" + storyId, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Xóa dữ liệu input sau khi gửi bình luận
            document.getElementById("author").value = '';
            document.getElementById("content").value = '';

            // Thêm bình luận vào giao diện mà không cần làm mới trang
            var newComment = JSON.parse(xhr.responseText);
            displayComment(newComment);
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

    // Thêm bình luận mới vào phần đầu của danh sách bình luận
    commentSection.insertBefore(commentDiv, commentSection.firstChild);
}

// Khi trang tải, lấy các bình luận từ server để hiển thị
window.onload = function () {
    var storyId = document.getElementById("commentForm").dataset.storyId;

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "story.php?storyId=" + storyId, true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var comments = JSON.parse(xhr.responseText);
            comments.forEach(function (comment) {
                displayComment(comment);
            });
        }
    };

    xhr.send();
};