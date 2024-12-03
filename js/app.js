const storiesItems = document.querySelectorAll(".stories-item");
const storiesItem = document.querySelector(".stories-item");
const storiesRow = document.querySelectorAll(".stories-row");
const storiesWrap = document.querySelector(".stories-wrap");

const introBtn = document.getElementById("introBtn");
const text = [
  "新宿――世界で最も活気に満ち、人々で溢れる街のひとつ。",
  "しかし、高層ビルやきらめくネオンの下、この地にはどんな知られざる伝説が隠されているのでしょうか?",
  "このサイトでは、新宿区にまつわる25の都市伝説を集めてご紹介しています。",
  "さあ、新宿の深い奥を探検しましょう。",
];


let currentChar = 0;
let currentText = text[0];
let currentTextIndex = 0;

/**
   * intro text animation
   * イントロテキストのアニメーション
   * @function  typeText()
  */
function typeText() {
  const introText = document.getElementById("introText");

  if (!introText) {
    return;
  }

  if (currentChar < currentText.length) {
    introText.style.opacity = 1;
    introText.innerHTML += currentText.charAt(currentChar);
    currentChar++;
    setTimeout(typeText, 40);
  } else if (currentTextIndex < text.length - 1) {
    introText.innerHTML += "<br><br>";
    currentChar = 0;
    currentText = text[currentTextIndex + 1];
    currentTextIndex++;
    setTimeout(typeText, 500);
  }
}

// DOMContentLoadedイベントを待ってから実行
document.addEventListener("DOMContentLoaded", () => introBtn && setTimeout(typeText, 700));

/**
 * fade out animation
 * イントロからindexへのアニメーション
 * @function fadeOut()
 */
function fadeOut() {
  const content = document.querySelector(".content");
  content.style.display = "block";

  introBtn.style.transition = "transform 1s ease, opacity 1s ease";
  introBtn.style.transform = "translateX(-50%) translateY(-100px)";
  introBtn.style.opacity = "0";

  introText.style.transition = "2s ease 1s";
  introText.style.transform = "translateX(-50%) translateY(-400px)";
  introText.style.opacity = "0";

  const overlayFront = document.getElementById("overlayFront");
  overlayFront.style.transition = "transform 2s ease 2s";
  overlayFront.style.transform = "translateY(-110%)";

  const intro = document.querySelector(".intro");
  intro.style.transition = "transform 1s ease 3s";
  intro.style.transform = "translateY(-100%)";
}
if (introBtn) {
  introBtn.onclick = fadeOut;
}

// 中心に移動2
function scrollToElement(element) {
  // 中心を計算
  const rect = element.getBoundingClientRect();
  let targetX =
    rect.left + window.scrollX + rect.width / 2 - window.innerWidth / 2;
  let targetY =
    rect.top + window.scrollY + rect.height / 2 - window.innerHeight / 2;

  // 中心に移動
  window.scrollTo({
    left: targetX,
    top: targetY,
    behavior: "smooth",
    duration: 6000,
  });
}

// 本を開いて中心に移動
storiesItems.forEach((item) => {
  item.addEventListener("click", function (event) {
    event.preventDefault(); // prevent immediate relink
    let cover = this.querySelector(".stories-item-cover");
    let link = this.querySelector("a").getAttribute("href");
    cover.classList.add("openBook");
    item.style.transform = "rotateX(0deg) rotateY(0deg)";
    scrollToElement(this);
    setTimeout(() => {
      cover.classList.remove("openBook");
      item.style.transform = "rotateX(20deg) rotateY(30deg)";
    }, 3000);

    // 次のページに移動
    cover.addEventListener(
      "animationend",
      function () {
        window.location.href = link;
      },
      { once: false }
    );
  });
});
// 本を開いて中心に移動

// Page Load Animation

// ↓↓↓↓↓↓↓↓↓ page scroll animation ↓↓↓↓↓↓↓↓↓
const scrollElements = document.querySelectorAll(".js_scroll");

// check element's scrollTop height
const elementInView = (el, percentageScroll = 100) => {
  const elementTop = el.getBoundingClientRect().top;

  return (
    elementTop <=
    (window.innerHeight || document.documentElement.clientHeight) *
      (percentageScroll / 100)
  );
};

// add scrolled class
const displayScrollElement = (element) => {
  element.classList.add("scrolled");
};

// reset if it's not in the window.
const hideScrollElement = (element) => {
  element.classList.remove("scrolled");
};

// toggle element's class
const handleScrollAnimation = () => {
  scrollElements.forEach((el) => {
    if (elementInView(el, 100)) {
      displayScrollElement(el);
    } else {
      hideScrollElement(el);
    }
  });
};

// efficiency
let throttleTimer = false;

const throttle = (callback, time) => {
  if (throttleTimer) return;

  throttleTimer = true;

  setTimeout(() => {
    callback();
    throttleTimer = false;
  }, time);
};

// event listener
window.addEventListener("scroll", () => {
  throttle(handleScrollAnimation, 250);
});

// ページ読み込み時に実行
window.onload = function () {
  handleScrollAnimation();
};

// ↑↑↑↑↑↑↑↑↑ page scroll animation ↑↑↑↑↑↑↑↑↑