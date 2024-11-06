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
            <div class="story-article-map-content"></div>
            <div class="story-article-map-title"><?php echo $story->location ?></div>
        </div>
    </article>
    <div class="to-index">
        <a href="index.php">一覧に戻る</a>
    </div>
    <?php include __DIR__ . '/include/footer.php'; ?>
</body>
<script src="js/app.js?<?php echo time(); ?>"></script>
<script src="js/likes.js"></script>

<script>
    document.getElementById('likeButton').dataset.storyId = <?php echo $story->id; ?>;
</script>

</html>