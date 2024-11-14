document.getElementById("commentForm").addEventListener("submit", postComment);

function postComment(event) {
    event.preventDefault();

    var storyId = document.getElementById("commentForm").dataset.storyId;
    var author = document.getElementById("author").value;
    var content = document.getElementById("content").value;
        //AjaxでPHPにデータを送信
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "story.php?id=" + storyId, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
                        //コメントを送信しらら、フォームをクリア
            document.getElementById("author").value = '';
            document.getElementById("content").value = '';
            //ページをリロードせずにコメントを追加
            var newComment = JSON.parse(xhr.responseText);
            displayComment(newComment);
        }
    };

    xhr.send("storyId=" + storyId + "&author=" + encodeURIComponent(author) + "&content=" + encodeURIComponent(content));
}
//新しいコメントを表示
function displayComment(comment) {
    var commentSection = document.getElementById("commentSection");

    var commentDiv = document.createElement("div");
    commentDiv.className = "comment";
    commentDiv.innerHTML = `<strong>${comment.author}</strong> (${comment.time}): ${comment.content}`;
    //新しいコメントを上に表示
    commentSection.insertBefore(commentDiv, commentSection.firstChild);
}

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
        comments.slice(0, 2).forEach(function (comment) {
            displayComment(comment);
        });
    })
    .catch(error => console.error('Error', error));
};
