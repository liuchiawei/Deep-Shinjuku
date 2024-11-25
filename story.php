<?php
require_once __DIR__ . '/class/StoryData.php';
require_once __DIR__ . '/class/LikeManager.php';
require_once __DIR__ . '/class/CommentManager.php';
require_once __DIR__ . '/api/Interaction.php';

session_start();

$nextStory = $storyData->getById($story->id + 1 <= $maxId ? $story->id + 1 : 1);
$prevStory = $storyData->getById($story->id - 1 > 0 ? $story->id - 1 : $maxId);

$commentManager = new CommentManager(__DIR__ . '/data/likes_data.json');
$comments = $commentManager->getComments($story->id);

$userComments = isset($_COOKIE['user_comments']) ? explode(',', $_COOKIE['user_comments']) : [];

// Xử lý xóa bình luận
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment_id'])) {
    $commentId = $_POST['delete_comment_id'];
    $commentManager->deleteComment($storyId, $commentId);
}

// Xử lý chỉnh sửa bình luận
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_comment_id'])) {
    $commentId = $_POST['edit_comment_id'];
    $newContent = $_POST['new_content'];
    $commentManager->editComment($storyId, $commentId, $newContent);
}
?>

<html>
<?php include __DIR__ . '/include/head.php'; ?>

<body>
    <div class="story-wrap">
        <div class="story-info">
            <div class="story-info-title"><?php echo $story->title; ?></div>
            <div class="story-info-subinfo">
                <div class="story-info-date"><?php echo $story->date; ?></div>
                <div class="story-info-location"><?php echo $story->location; ?></div>
            </div>
            <div class="story-info-rating">
                <!-- TODO:星の数を表示 -->
                <?php for ($i = 0; $i < 5; $i++) { ?>
                    <div class="story-info-rating-star"></div>
                <?php } ?>
            </div>
            <div class="story-info-brief"><?php echo $story->brief; ?></div>
        </div>
        <div class="story-item">
            <div class="story-item-title"><?php echo $story->title; ?></div>
            <div class="story-item-location"><?php echo $story->location; ?></div>
            <div class="story-item-image">
                <img src="image/<?php echo $story->id; ?>.jpg" alt="<?php echo $story->title; ?>">
            </div>
        </div>
    </div>
    <article class="story-article">
        <div class="story-article-title"><?php echo $story->title; ?></div>
        <div class="story-article-content">
            <p class="js_scroll fade-in"><?php echo $story->content; ?></p>
            <div class="story-article-buttons-wrap">
                <button type="button" id="likeButton" class="like-button" data-story-id="<?php echo $story->id; ?>">
                    <?php echo $hasAlreadyLiked ? '<i class="bi bi-heart-fill"></i>' : '<i class="bi bi-heart"></i>'; ?>
                </button>
                <div class="like-count" id="likeCount"><?php echo $likeCount; ?></div>
            </div>
        </div>
        <!-- <div class="story-article-explanation">
            <?php echo $story->explanation ?>
        </div> -->
        <div class="story-detail-wrap">
            <div class="story-comment-wrap">
                <div class="story-detail-title">目撃者の説述</div>
                <div class="story-comment-form">
                    <form action="story.php?id=<?php echo $story->id; ?>" method="POST">
                        <textarea name="content" id="content" cols="30" rows="10"></textarea>
                        <input type="text" name="author" id="author" placeholder="名前">
                        <button type="submit">コメントを投稿</button>
                    </form>
                </div>
                <!-- TODO:コメントを表示する(foreach) -->
                <div class="story-comment">
                    <div class="story-comment-btns">
                        <button type="button" id="editComment" class="story-comment-btn">編集</button>
                        <button type="button" id="deleteComment" class="user-comment-btn">削除</button>
                    </div>
                    <!-- <div class="user-comment-content">
                        コメント内容
                    </div>
                    <div class="user-comment-author">
                        名前
                    </div>
                    <div class="user-comment-time">
                        日時
                    </div> -->
                </div>
                <div id="comments-Popup" style="
                width: fit-content;
                height: fit-content;
                position:fixed;
                right: 1rem;
                bottom: 1rem;
                background-color: #ddd;
                color: black;
                ">
                    <?php if (!empty($comments)): ?>
                        <ul>
                            <?php foreach ($comments as $comment): ?>
                                <li>
                                    <strong><?= htmlspecialchars($comment['author']) ?>:</strong>
                                    <p><?= htmlspecialchars($comment['content']) ?></p>
                                    <em><?= htmlspecialchars($comment['time']) ?></em>

                                    <?php if (in_array($comment['comment_id'], $userComments)): ?>
                                        <!-- Nút xóa -->
                                        <form method="post" style="display: inline;">
                                            <input type="hidden" name="delete_comment_id" value="<?= $comment['comment_id'] ?>">
                                            <button type="submit"><svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <path d="M10 12V17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M14 12V17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M4 7H20" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </g>
                                                </svg></button>
                                        </form>

                                        <!-- Nút chỉnh sửa -->
                                        <form method="post" style="display: inline;">
                                            <input type="hidden" name="edit_comment_id" value="<?= $comment['comment_id'] ?>">
                                            <input type="text" name="new_content" placeholder="Edit content" required>
                                            <button type="submit"><svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <path d="M14.8686 4.13134L14.1615 3.42423L14.1615 3.42423L14.8686 4.13134ZM7.81459 7.48152L8.08931 8.44304L7.81459 7.48152ZM5.57564 9.83884L6.55004 10.0637V10.0637L5.57564 9.83884ZM3 21L2.02561 20.7751C1.94808 21.1111 2.04909 21.4633 2.29289 21.7071C2.5367 21.9509 2.8889 22.0519 3.22486 21.9744L3 21ZM14.1611 18.4243L13.9363 17.4499L13.9363 17.4499L14.1611 18.4243ZM16.5185 16.1854L15.5569 15.9107L16.5185 16.1854ZM19.8686 9.13134L20.5757 9.83845V9.83845L19.8686 9.13134ZM19.8686 6.8686L19.1615 7.57571H19.1615L19.8686 6.8686ZM17.1314 4.13134L17.8385 3.42423V3.42423L17.1314 4.13134ZM20.5368 8.30899L19.5858 7.99997L20.5368 8.30899ZM20.5368 7.69095L19.5858 7.99997L20.5368 7.69095ZM15.4404 18.0251L15.9601 18.8794H15.9601L15.4404 18.0251ZM16.0539 17.4424L16.8804 18.0054L16.8804 18.0054L16.0539 17.4424ZM6.55756 7.94607L7.12056 8.77253L7.12056 8.77253L6.55756 7.94607ZM5.97487 8.55957L6.82922 9.07928L6.82922 9.07928L5.97487 8.55957ZM15.691 3.46313L15.382 2.51207L15.691 3.46313ZM16.309 3.46313L16.618 2.51207L16.618 2.51207L16.309 3.46313ZM9.14645 16.2676C9.53697 15.8771 9.53697 15.2439 9.14644 14.8534C8.75591 14.4629 8.12275 14.4629 7.73223 14.8534L9.14645 16.2676ZM10 14.5C10 14.7761 9.77614 15 9.5 15V17C10.8807 17 12 15.8807 12 14.5H10ZM9.5 15C9.22386 15 9 14.7761 9 14.5H7C7 15.8807 8.11929 17 9.5 17V15ZM9 14.5C9 14.2238 9.22386 14 9.5 14V12C8.11929 12 7 13.1193 7 14.5H9ZM9.5 14C9.77614 14 10 14.2238 10 14.5H12C12 13.1193 10.8807 12 9.5 12V14ZM14.1615 3.42423L12.2929 5.29286L13.7071 6.70708L15.5757 4.83845L14.1615 3.42423ZM12.7253 5.03845L7.53987 6.51999L8.08931 8.44304L13.2747 6.96149L12.7253 5.03845ZM4.60125 9.61398L2.02561 20.7751L3.97439 21.2248L6.55004 10.0637L4.60125 9.61398ZM3.22486 21.9744L14.386 19.3987L13.9363 17.4499L2.77514 20.0256L3.22486 21.9744ZM17.48 16.4601L18.9615 11.2747L17.0385 10.7252L15.5569 15.9107L17.48 16.4601ZM18.7071 11.7071L20.5757 9.83845L19.1615 8.42424L17.2929 10.2929L18.7071 11.7071ZM20.5757 6.16149L17.8385 3.42423L16.4243 4.83845L19.1615 7.57571L20.5757 6.16149ZM20.5757 9.83845C20.7621 9.65211 20.9449 9.47038 21.0858 9.30446C21.2342 9.12961 21.3938 8.90772 21.4879 8.618L19.5858 7.99997C19.6057 7.93858 19.6292 7.92986 19.5611 8.01011C19.4854 8.09928 19.3712 8.21456 19.1615 8.42424L20.5757 9.83845ZM19.1615 7.57571C19.3712 7.78538 19.4854 7.90066 19.5611 7.98984C19.6292 8.07008 19.6057 8.06136 19.5858 7.99997L21.4879 7.38194C21.3938 7.09222 21.2342 6.87033 21.0858 6.69548C20.9449 6.52957 20.7621 6.34783 20.5757 6.16149L19.1615 7.57571ZM21.4879 8.618C21.6184 8.21632 21.6184 7.78362 21.4879 7.38194L19.5858 7.99997V7.99997L21.4879 8.618ZM14.386 19.3987C14.988 19.2598 15.5141 19.1507 15.9601 18.8794L14.9207 17.1708C14.8157 17.2346 14.6727 17.28 13.9363 17.4499L14.386 19.3987ZM15.5569 15.9107C15.3493 16.6373 15.2966 16.7778 15.2274 16.8794L16.8804 18.0054C17.1743 17.574 17.3103 17.0541 17.48 16.4601L15.5569 15.9107ZM15.9601 18.8794C16.3257 18.6571 16.6395 18.359 16.8804 18.0054L15.2274 16.8794C15.1471 16.9973 15.0426 17.0966 14.9207 17.1708L15.9601 18.8794ZM7.53987 6.51999C6.94585 6.68971 6.426 6.82571 5.99457 7.11961L7.12056 8.77253C7.22213 8.70334 7.36263 8.65066 8.08931 8.44304L7.53987 6.51999ZM6.55004 10.0637C6.71998 9.32729 6.76535 9.18427 6.82922 9.07928L5.12053 8.03986C4.84922 8.48586 4.74017 9.01202 4.60125 9.61398L6.55004 10.0637ZM5.99457 7.11961C5.64092 7.36052 5.34291 7.67429 5.12053 8.03986L6.82922 9.07928C6.90334 8.95742 7.00268 8.85283 7.12056 8.77253L5.99457 7.11961ZM15.5757 4.83845C15.7854 4.62878 15.9007 4.51459 15.9899 4.43889C16.0701 4.37076 16.0614 4.39424 16 4.41418L15.382 2.51207C15.0922 2.60621 14.8704 2.76578 14.6955 2.91421C14.5296 3.05506 14.3479 3.2379 14.1615 3.42423L15.5757 4.83845ZM17.8385 3.42423C17.6521 3.23789 17.4704 3.05506 17.3045 2.91421C17.1296 2.76578 16.9078 2.60621 16.618 2.51207L16 4.41418C15.9386 4.39424 15.9299 4.37077 16.0101 4.43889C16.0993 4.51459 16.2146 4.62877 16.4243 4.83845L17.8385 3.42423ZM16 4.41418H16L16.618 2.51207C16.2163 2.38156 15.7837 2.38156 15.382 2.51207L16 4.41418ZM12.2929 6.70708L17.2929 11.7071L18.7071 10.2929L13.7071 5.29286L12.2929 6.70708ZM7.73223 14.8534L2.29289 20.2929L3.70711 21.7071L9.14645 16.2676L7.73223 14.8534Z" fill="#000000"></path>
                                                    </g>
                                                </svg></button>
                                        </form>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                    <?php endif; ?>
                </div>
                <button type="button" id="seeAllCommentBtn" class="see-all-comment-btn">全てのコメントを見る</button>
            </div>
            <div class="story-photo-map-wrap">
                <div class="story-detail-title">写真と地図</div>
                <div class="story-photo">
                </div>
                <div class="story-photo-s">
                </div>
                <div class="story-map">
                    <div class="story-map-content">
                        <iframe
                            style="border:0"
                            width="100%"
                            height="100%"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAvCim13FGe1rH23MIoU3ARs45Ngx6z218&q=<?php echo $story->location; ?>">
                        </iframe>
                    </div>
                </div>
                <div class="story-map-title"><?php echo $story->location ?></div>
            </div>
    </article>
    <section class="prev-next-story-wrap">
        <a href="story.php?id=<?php echo $prevStory->id; ?>">
            <div class="prev-next-story" data-text="前の怪談">
                <div class="story-item">
                    <div class="story-item-title">
                        <?php echo $prevStory->title; ?>
                    </div>
                    <div class="story-item-location">
                        <?php echo $prevStory->location; ?>
                    </div>
                    <div class="story-item-image">
                        <img src="image/<?php echo $prevStory->id; ?>.jpg" alt="<?php echo $prevStory->title; ?>">
                    </div>
                </div>
                <div class="story-info">
                    <div class="story-info-title"><?php echo $prevStory->title; ?></div>
                    <div class="story-info-brief"><?php echo $prevStory->brief; ?></div>
                </div>
            </div>
        </a>
        <a href="story.php?id=<?php echo $nextStory->id; ?>">
            <div class="prev-next-story" data-text="次の怪談">
                <div class="story-item">
                    <div class="story-item-title">
                        <?php echo $nextStory->title; ?>
                    </div>
                    <div class="story-item-location">
                        <?php echo $nextStory->location; ?>
                    </div>
                    <div class="story-item-image">
                        <img src="image/<?php echo $nextStory->id; ?>.jpg" alt="<?php echo $nextStory->title; ?>">
                    </div>
                </div>
                <div class="story-info">
                    <div class="story-info-title"><?php echo $nextStory->title; ?></div>
                    <div class="story-info-brief"><?php echo $nextStory->brief; ?></div>
                </div>
            </div>
        </a>
    </section>
    <?php include __DIR__ . '/include/footer.php'; ?>
</body>
<script src="js/app.js?<?php echo time(); ?>"></script>
<script src="js/likes.js"></script>
<script src="js/comments.js"></script>

<script>
    document.getElementById('likeButton').dataset.storyId = <?php echo $story->id; ?>;
</script>

</html>