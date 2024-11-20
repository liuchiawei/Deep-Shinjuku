<?php
require_once __DIR__ . '/class/StoryData.php';
require_once __DIR__ . '/class/LikeManager.php';

$storyData = new StoryData();
$story = $storyData->getById($_GET['id']);

$maxId = $storyData->getMaxId();

$storyId = $_GET['id'] ?? null;

if ($storyId !== null) {
    //今のLike数を取得
    $likeManager = new LikeManager('./data/likes_data.json');
    $likeCount = $likeManager->getLikes($storyId);

    //Like済みかどうかを取得
    $hasAlreadyLiked = isset($_SESSION['liked_stories'][$storyId]) ? true : false;

    if (isset($_POST['hasLiked'])) {
        $hasLiked = $_POST['hasLiked'] === 'true';
        $newLikeCount = $likeManager->toggleLikeForStory($storyId, $hasLiked);

        echo $newLikeCount;
    }
}

$nextStory = $storyData->getById($story->id + 1 <= $maxId ? $story->id + 1 : 1);
$prevStory = $storyData->getById($story->id - 1 > 0 ? $story->id - 1 : $maxId);
?>
<html>
<?php include __DIR__ . '/include/head.php'; ?>

<body>
    <?php include __DIR__ . '/include/nav.php'; ?>
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
                <form method="POST" action="story.php?id=<?php echo $story->id; ?>">
                    <button type="submit" name="hasLiked" value="<?php echo $hasAlreadyLiked ? 'false' : 'true'; ?>" id="likeButton" class="like-button">
                        <?php echo $hasAlreadyLiked ? '<i class="bi bi-heart-fill"></i>' : '<i class="bi bi-heart"></i>'; ?>
                    </button>
                </form>
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
                        <textarea name="comment" id="comment" cols="30" rows="10"></textarea>
                        <button type="submit">コメントを投稿</button>
                    </form>
                </div>
                <!-- TODO:コメントを表示する(foreach) -->
                <div class="story-comment">
                    <div class="story-comment-btns">
                        <button type="button" id="editComment" class="story-comment-btn">編集</button>
                        <button type="button" id="deleteComment" class="user-comment-btn">削除</button>
                    </div>
                    <div class="user-comment-content">
                        コメント内容
                    </div>
                    <div class="user-comment-author">
                        名前
                    </div>
                    <div class="user-comment-time">
                        日時
                    </div>
                </div>
                <button type="button" id="seeAllCommentBtn" class="see-all-comment-btn">全てのコメントを見る（TODO:こちらに全部のコメントの数を表示）</button>
            </div>
            <div class="story-photo-map-wrap">
                <div class="story-detail-title">写真と地図</div>
                <div class="story-photo">
                    <div class="story-photo-l"></div>
                    <div class="story-photo-s"></div>
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

<script>
    document.getElementById('likeButton').dataset.storyId = <?php echo $story->id; ?>;
</script>

</html>