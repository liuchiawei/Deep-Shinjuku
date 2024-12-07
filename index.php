<?php

require_once __DIR__ . '/class/StoryData.php';

$storyData = new StoryData();
$stories = $storyData->getAll();

?>
<html lang="ja">
<?php include __DIR__ . '/include/head.php'; ?>

<body>
    <div class="container">
        <?php include __DIR__ . '/include/nav.php'; ?>

        <div class="intro">
            <div id="overlayFront" class="overlay">
                <p id="introText" class="intro-text"></p>
            </div>
            <button class="intro-btn" id="introBtn">EXPLORE</button>
            <div id="overlayBack" class="overlay"></div>
        </div>

        <div class="content" id="content">
            <div class="stories-wrap" id="stories-wrap">
                <?php for ($i = 0; $i < 5; $i++) : ?>
                    <div class="stories-row">
                        <?php for ($j = 0; $j < 10; $j++) : ?>
                            <?php $story = $stories[$i * 5 + $j % 5]; ?>
                            <div class="stories-item" id="<?= $story->id ?>">
                                <div class="stories-item-cover" style="background: url('image/<?= $story->id ?>.jpg') no-repeat center center / cover;">
                                    <a href="./story.php?id=<?= $story->id ?>">
                                        <h2 class="stories-item-title"><?= $story->title ?></h2>
                                        <h3 class="stories-item-location"><?= $story->location ?></h3>
                                    </a>
                                </div>
                                <div class="stories-item-open"></div>
                                <div class="stories-item-spine" data-title="<?= $story->title ?>"></div>
                                <div class="stories-item-background" style="background: url('image/<?= $story->id ?>.jpg') no-repeat center center / cover;"></div>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <script src="js/app.js?v=<?= time() ?>"></script>
</body>

</html>