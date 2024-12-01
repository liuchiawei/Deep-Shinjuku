<?php

require_once __DIR__ . '/class/StoryData.php';

$storyData = new StoryData();
$stories = $storyData->getAll();
// shuffle($stories); // 配列をランダムに並び替え

?>
<html>
<?php include __DIR__ . '/include/head.php'; ?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style-intro.css">
    <link href="https://fonts.googleapis.com/css?family=Proza+Libre" rel="stylesheet">
    <script src="main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.0/TweenMax.min.js"></script>
</head>

<body style="overflow-x: hidden;">
    <div class="container">
        <?php include __DIR__ . '/include/nav.php'; ?>

        <div class="overlay">
            <p class="screen" style="font-size: 40px; margin-bottom: 200px; width: 90%; opacity: 0;"></p>
            <script>
                const text1 = "新宿――世界で最も活気に満ち、人々で溢れる街のひとつ。しかし、高層ビルやきらめくネオンの下、この地にはどんな知られざる伝説が隠されているのでしょうか?";
                const text2 = "ようこそ DEEP SHINJUKU へ。このサイトでは、新宿区にまつわる25の都市伝説を集めてご紹介しています。";
                
                let screen = document.querySelector('.screen');
                let currentChar = 0;
                let currentText = text1;
                let isFirstTextDone = false;

                function typeText() {
                    if (!screen) {
                        console.error('screen element not found');
                        return;
                    }

                    if(currentChar < currentText.length) {
                        screen.style.opacity = 1;
                        screen.innerHTML += currentText.charAt(currentChar);
                        currentChar++;
                        setTimeout(typeText, 50);
                    } else if(!isFirstTextDone) {
                        screen.innerHTML += "<br><br><br>";
                        currentChar = 0;
                        currentText = text2;
                        isFirstTextDone = true;
                        setTimeout(typeText, 1000);
                    } else {
                        const exploreBtn = document.querySelector('.myBtn');
                        if (exploreBtn) {
                            exploreBtn.style.opacity = 1;
                        }
                    }
                }

                // DOMContentLoadedイベントを待ってから実行
                document.addEventListener('DOMContentLoaded', () => {
                    screen = document.querySelector('.screen');
                    document.querySelector('.myBtn').style.opacity = 0;
                    setTimeout(typeText, 1500);
                });
            </script>
        </div>
        </div>
            <div class="intro">
                <button class="myBtn" onclick="fadeOut()">EXPLORE</button>
            </div>
        </div>

        <div class="overlay-2" style="background-color: #2a0707;"></div>

        <div class="content" id="content" style="display: none;">
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
    <script>
        function fadeOut() {
            document.getElementById("content").style.display = "block";

            TweenMax.to(".myBtn", 1, {
                y: -100,
                opacity: 0,
            });

            TweenMax.to(".screen", 2, {
                y: -400,
                opacity: 0,
                ease: Power2.easeInOut,
                delay: 2
            });

            TweenMax.from(".overlay", 2, {
                ease: Power2.easeInOut
            });

            TweenMax.to(".overlay", 2, {
                delay: 2.6,
                top: "-110%",
                ease: Expo.easeInOut
            });

            TweenMax.to(".overlay-2", 2, {
                delay: 3,
                top: "-110%",
                ease: Expo.easeInOut
            });

            TweenMax.from(".content", 2, {
                delay: 3.2,
                opacity: 0,
                ease: Power2.easeInOut
            });

            TweenMax.to(".content", 2, {
                opacity: 1,
                y: -300,
                delay: 3.2,
                ease: Power2.easeInOut
            });
        }
    </script>
</body>

</html>