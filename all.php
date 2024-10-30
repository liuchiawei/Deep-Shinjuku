<?php

require_once __DIR__ . '/class/StoryData.php';

$storyData = new StoryData();
$stories = $storyData->getAll();

?>
<html>
<?php include __DIR__ . '/include/head.php'; ?>

<body>
    <div class="viewport">
        <div class="stories-wrap" id="stories-wrap">
            <?php foreach ($stories as $story) : ?>
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
            <?php endforeach; ?>
        </div>
    </div>
    <script src="js/app.js"></script>
</body>

</html>
