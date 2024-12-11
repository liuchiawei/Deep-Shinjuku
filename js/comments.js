// document.addEventListener("DOMContentLoaded", () => {
//     //comment carousel
//     let currentIndex = 0;
//     const comments = document.querySelectorAll("#commentList li");
//     const prevBtn = document.getElementById("prevBtn");
//     const nextBtn = document.getElementById("nextBtn");

//     function updateDisplay() {
//         comments.forEach((comment, index) => {
//             const commentId = `comment-${index}`;
//             const commentElement = document.getElementById(commentId);
//             if (commentElement) {
//                 commentElement.style.display = index === currentIndex ? "block" : "none";
//             }
//         });

//         prevBtn.disabled = currentIndex === 0;
//         nextBtn.disabled = currentIndex === comments.length - 1;
//     }

//     prevBtn.addEventListener("click", () => {
//         if (currentIndex > 0) {
//             currentIndex--;
//             updateDisplay();
//         }
//     });

//     nextBtn.addEventListener("click", () => {
//         if (currentIndex < comments.length - 1) {
//             currentIndex++;
//             updateDisplay();
//         }
//     });

//     updateDisplay();
// });

//無限繰り返しver
document.addEventListener("DOMContentLoaded", () => {
    const commentList = document.getElementById("commentList");
    const comments = document.querySelectorAll("#commentList li");
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");
    const noMoreComments = document.getElementById("noMoreComments");

    let currentIndex = 0;

    commentList.style.display = "block";

    function updateDisplay() {
        comments.forEach((comment, index) => {
            comment.style.display = index === currentIndex ? "block" : "none";
        });
    }

    function showNotification(message) {
        noMoreComments.textContent = message;
        noMoreComments.style.display = "block";
        setTimeout(() => {
            noMoreComments.style.display = "none";
        }, 2000);
    }

    prevBtn.addEventListener("click", () => {
        if (comments.length === 1) {
            showNotification("現在、他のコメントはありません。");
        } else {
            currentIndex = (currentIndex === 0) ? comments.length - 1 : currentIndex - 1;
            updateDisplay();
        }
    });

    nextBtn.addEventListener("click", () => {
        if (comments.length === 1) {
            showNotification("現在、他のコメントはありません。");
        } else {
            currentIndex = (currentIndex === comments.length - 1) ? 0 : currentIndex + 1;
            updateDisplay();
        }
    });

    updateDisplay();
});




let isConfirmed = false;

document.getElementById("commentForm").addEventListener("submit", postComment);

function postComment(event) {
    if (!isConfirmed) {
        event.preventDefault();
        console.log("Form not submitted yet. Waiting for confirmation.");
        return;
    }

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
            return response.json();  // Parse JSON from response body
        })
        .then(newComment => {
            if (newComment && newComment.comment_id) {
                console.log("Comment ID saved to LocalStorage:", newComment.comment_id);

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

const scrollPosition = window.scrollY;
localStorage.setItem('scrollPosition', scrollPosition);

document.getElementById("postButton").addEventListener("click", () => {
    const content = document.getElementById("content").value.trim();
    if (content) {
        console.log("Textarea is filled");

        const popup = document.getElementById("confirmationPopup");
        popup.style.display = "block";
        popup.classList.add("fadeIn");

        document.getElementById("confirmOk").onclick = function () {
            isConfirmed = true;
            document.getElementById("commentForm").dispatchEvent(new Event("submit"));

            popup.style.display = "none";
            setTimeout(function () {
                window.location.reload();
            }, 500);
        };

        document.getElementById("confirmCancel").onclick = function () {
            popup.style.display = "none";
        };
    } else {
        console.log("Textarea is empty");
    }
});

const textarea = document.getElementById('content');
const charCount = document.getElementById('charCount');

textarea.addEventListener('input', () => {
    const currentLength = textarea.value.length;
    const maxLength = textarea.getAttribute('maxlength');
    charCount.textContent = `${currentLength}/${maxLength}`;
});


window.onload = function () {
    const savedPosition = localStorage.getItem('scrollPosition');
    if (savedPosition) {
        window.scrollTo(0, parseInt(savedPosition, 10));
        localStorage.removeItem('scrollPosition');
    }
    var storyId = document.getElementById("likeButton").dataset.storyId;
    var hasLiked = getCookie("hasLiked_" + storyId) === "true";
    document.getElementById("likeButton").innerHTML = hasLiked ? '<i class="bi bi-heart-fill"></i>' : '<i class="bi bi-heart"></i>';
};