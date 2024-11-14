<?php
require_once __DIR__ . '/class/StoryData.php';
require_once __DIR__ . '/class/LikeManager.php';
require_once __DIR__ . '/class/CommentManager.php';
require_once __DIR__ . '/api/Interaction.php';

?>

<html>
<?php include __DIR__ . '/include/head.php'; ?>

<body>
    <div class="story-wrap">
        <div class="story-item">
            <div class="icon-set">
                <a href="story.php?id=<?php if ($story->id + 1 <= $maxId) {
                                            echo $story->id + 1;
                                        } else {
                                            echo 1;
                                        } ?>">
                    <div class="to-right"></div>
                </a>
                <a href="story.php?id=<?php if ($story->id - 1 > 0) {
                                            echo $story->id - 1;
                                        } else {
                                            echo $maxId;
                                        } ?>">
                    <div class="to-left"></div>
                </a>
            </div>
            <div class="story-item-title"><?php echo $story->title; ?></div>
            <div class="story-item-location"><?php echo $story->location; ?></div>
            <div class="story-item-image">
                <img src="image/<?php echo $story->id; ?>.jpg" alt="<?php echo $story->title; ?>">
            </div>
        </div>
    </div>
    <article class="story-article">
        <div class="story-article-title"><?php echo $story->title; ?></div>
        <div class="story-article-location"><?php echo $story->location; ?></div>
        <div class="story-article-content">
            <p class="js_scroll fade-in"><?php echo $story->content; ?></p>
            <div class="story-article-buttons-wrap">
                <form method="POST" action="story.php?id=<?php echo $story->id; ?>">
                    <button type="submit" name="hasLiked" value="<?php echo $hasAlreadyLiked ? 'false' : 'true'; ?>" id="likeButton" class="like-button">
                        <?php echo $hasAlreadyLiked ? 'Unlike' : 'Like'; ?>
                    </button>
                </form>
                <div class="like-count" id="likeCount"><?php echo $likeCount; ?></div>
            </div>
        </div>
        <div class="story-article-explanation">
            <?php echo $story->explanation ?>
        </div>
        <div class="story-article-map">
            <div class="story-article-map-content">
                <iframe
                    width="600"
                    height="450"
                    style="border:0"
                    loading="lazy"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAvCim13FGe1rH23MIoU3ARs45Ngx6z218
    &q=Space+Needle,Seattle+WA">
                </iframe>
            </div>
            <div class="story-article-map-title"><?php echo $story->location ?></div>
        </div>
    </article>
    <div class="user-comment-wrap">
        <form action="story.php?id=<?php echo $story->id; ?>" method="POST">
            <textarea name="comment" id="comment" cols="30" rows="10"></textarea>
            <button type="submit">コメントを投稿</button>
        </form>

        <!-- TODO:コメントを表示する(foreach) -->
        <div class="user-comment">
            <div class="user-comment-btns">
                <button type="button" id="editComment" class="user-comment-btn">編集</button>
                <button type="button" id="deleteComment" class="user-comment-btn">削除</button>
            </div>
            <?php foreach ($comments as $comment) : ?>
                <div class="user-comment-content">
                    <?php echo $comment['content']; ?>
                </div>
                <div class="user-comment-author">
                    <?php echo $comment['author']; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" id="seeAllCommentBtn" class="see-all-comment-btn">全ての<?php echo $commentCount ?>のコメントを表示する</button>
    </div>
    <div class="to-index">
        <a href="index.php">一覧に戻る</a>
    </div>
    <?php include __DIR__ . '/include/footer.php'; ?>
</body>
<script src="js/app.js?<?php echo time(); ?>"></script>
<script src="js/likes.js"></script>
<script src="js/comments.js"></script>

<script>
    document.getElementById('likeButton').dataset.storyId = <?php echo $story->id; ?>;
</script>

</html>