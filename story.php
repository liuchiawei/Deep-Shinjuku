<?php
require_once __DIR__ . '/class/StoryData.php';
require_once __DIR__ . '/class/LikeManager.php';
require_once __DIR__ . '/class/CommentManager.php';

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

$commentManager = new CommentManager('./data/likes_data.json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nhận dữ liệu từ AJAX
    $storyId = $_POST['storyId'];
    $author = $_POST['author'];
    $content = $_POST['content'];

    // Thêm bình luận vào JSON
    $newComment = $commentManager->addComment($storyId, $author, $content);

    // Trả về bình luận mới dưới dạng JSON để hiển thị ngay lập tức
    echo json_encode($newComment);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['storyId'])) {
    $storyId = $_GET['storyId'];

    // Lấy tất cả các bình luận cho câu chuyện từ JSON
    $comments = $commentManager->getComments($storyId);

    // Trả về danh sách bình luận dưới dạng JSON
    echo json_encode($comments);
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