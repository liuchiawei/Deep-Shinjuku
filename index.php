<?php

require_once __DIR__ . '/class/StoryData.php';

$storyData = new StoryData();
$stories = $storyData->getAll();
// shuffle($stories); // 配列をランダムに並び替え

?>
<html>
<?php include __DIR__ . '/include/head.php'; ?>

<body>
    <div class="viewport">
        <div class="stories-wrap" id="stories-wrap">
            <?php for ($i = 0; $i < 5; $i++) : ?>
                <div class="stories-row">
                    <?php for ($j = 0; $j < 10; $j++) : ?>
                        <?php $story = $stories[($i * 5 + $j) % (5 *($i+1))]; ?>
                        <div class="stories-item" id="<?= $story->id ?>">
                            <div class="stories-item-cover">
                                <a href="./story.php?id=<?= $story->id ?>">
                                    <div class="stories-item-image">
                                        <img src="image/<?= $story->id ?>.jpg" alt="<?= $story->title ?>">
                                    </div>
                                    <h2 class="stories-item-title"><?= $story->title ?></h2>
                                    <h3 class="stories-item-location"><?= $story->location ?></h3>
                                </a>
                            </div>
                            <div class="stories-item-background"></div>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
    <script src="js/app.js?v=<?= time() ?>"></script>
</body>

</html>
