<?php
require_once __DIR__ . '/class/StoryData.php';
require_once __DIR__ . '/class/LikeManager.php';
require_once __DIR__ . '/class/CommentManager.php';

session_start();

date_default_timezone_set('Asia/Tokyo');
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $storyId = (int)$_GET['id'];
} else {
    echo "Invalid or missing ID.";
}

$storyData = new StoryData();
$story = $storyData->getById($storyId);
$maxId = $storyData->getMaxId();
$storyId = $_GET['id'] ?? null;

$nextStory = $storyData->getById($story->id + 1 <= $maxId ? $story->id + 1 : 1);
$prevStory = $storyData->getById($story->id - 1 > 0 ? $story->id - 1 : $maxId);

$commentManager = new CommentManager(__DIR__ . '/data/likes_data.json');
$comments = $commentManager->getComments($story->id);


$likeManager = new LikeManager(__DIR__ . '/data/likes_data.json');
$likeCount = $likeManager->getLikes($story->id);

if (isset($_POST['hasLiked'])) {
    $hasLiked = $_POST['hasLiked'] === 'true';
    $newLikeCount = $likeManager->toggleLikeForStory($storyId, $hasLiked);
    echo $newLikeCount;
}

?>

<html lang="ja">
<?php include __DIR__ . '/include/head.php'; ?>

<body>
    <?php include __DIR__ . '/include/nav.php'; ?>
    <div class="story-wrap" style="background-image: url('image/background/<?php echo $story->locationId; ?>.jpg');">
        <div class="story-info story-info-top">
            <div class="story-info-title"><?php echo $story->title; ?></div>
            <div class="story-info-subinfo">
                <div class="story-info-date"><?php echo $story->date; ?></div>
                <div class="story-info-location"><?php echo $story->location; ?></div>
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
                </button>
                <div class="like-count" id="likeCount"><?php echo $likeCount; ?></div>
            </div>
        </div>
        <div class="story-detail-wrap">
            <div class="story-comment-wrap">
                <div class="story-detail-title">
                    コメント
                    <div class="comments-display-btn-wrap">
                        <button id="prevBtn" class="comments-display-btn"><i class="bi bi-caret-left"></i></button>
                        <button id="nextBtn" class="comments-display-btn"><i class="bi bi-caret-right"></i></button>
                    </div>
                </div>
                <div id="noMoreComments" style="display: none; position: relative; background: rgba(0, 0, 0, 0.8); color: white; padding: 10px; border-radius: 5px; z-index: 1000;">
                </div>
                <div class="story-comment">
                    <div id="comments-Popup" class="comments-popup">
                        <?php if (!empty($comments)): ?>
                            <ul id="commentList" style="display: block;">
                                <?php foreach ($comments as $index => $comment): ?>
                                    <?php
                                    $author = trim($comment['author']) !== '' ? htmlspecialchars($comment['author']) : '名無しさん';
                                    ?>
                                    <li id="comment-<?= $index ?>" style="display: none;" class="comment-body">
                                        <strong><?= htmlspecialchars($comment['author']) ?></strong>
                                        <div class="comment-content"><?= htmlspecialchars($comment['content']) ?></div>
                                        <em class="comment-time"><?= htmlspecialchars($comment['time']) ?></em>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>コメントはございません</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div id="confirmationPopup" class="confirmation-popup">
                    <div class="confirmation-popup-content">
                        <h1>コメント投稿しますか？</h1>
                        <button id="confirmCancel">戻る</button>
                        <button id="confirmOk">確定</button>
                    </div>
                </div>
                <div class="story-comment-form">
                    <form id="commentForm" data-story-id="<?php echo $story->id; ?>" action="story.php?id=<?php echo $story->id; ?>" method="POST">
                        <textarea maxlength="120" name="content" id="content" cols="30" rows="10" placeholder="コメント" required></textarea>
                        <p id="charCount">0/120</p>
                        <div class="story-comment-form-post">
                            <input class="story-comment-form-post-author" type="text" name="author" id="author" placeholder="名前">
                            <button id="postButton" type="submit" class="story-comment-btn">投稿</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="story-map">
                <div class="story-detail-title">地図</div>
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
                <div class="story-map-title"><?php echo $story->location ?></div>
            </div>
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
<script src="js/likes.js?<?php echo time(); ?>"></script>
<script src="js/comments.js?<?php echo time(); ?>"></script>

<script>
    document.getElementById('likeButton').dataset.storyId = <?php echo $story->id; ?>;
</script>

</html>