const storiesItems = document.querySelectorAll(".stories-item");
const storiesItem = document.querySelector(".stories-item");
const storiesRow = document.querySelectorAll(".stories-row");
const storiesWrap = document.querySelector(".stories-wrap");

// マウスでスクロール
function enableDragging() {
  let isDragging = false;
  let startX, startY;

  document.addEventListener("mousedown", function (e) {
    isDragging = true;
    // ドラッグ開始位置を計算
    startX = e.pageX - window.scrollX;
    startY = e.pageY - window.scrollY;
  });

  document.addEventListener("mousemove", function (e) {
    if (!isDragging) return;
    e.preventDefault();
    let newX = e.pageX - startX;
    let newY = e.pageY - startY;
    window.scrollTo(newX, newY);
    console.log(newX, newY);
  });

  document.addEventListener("mouseup", function () {
    isDragging = false;
  });
}
// マウスでスクロール

// 中心に移動
function centerView() {
  // 中心点を計算
  let x = (document.body.scrollWidth - window.innerWidth) / 2;
  // let y = (document.body.scrollHeight - window.innerHeight) / 2;

  window.scrollTo(x, 0);
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

// キーボードでスクロール
function keyboardScrolling() {
  let scrollSpeed = 40; // スクロール速度

  document.addEventListener("keydown", function (e) {
    switch (e.key) {
      case "ArrowLeft":
        window.scrollBy(-scrollSpeed, 0);
        break;
      case "ArrowRight":
        window.scrollBy(scrollSpeed, 0);
        break;
      case "ArrowUp":
        window.scrollBy(0, -scrollSpeed);
        break;
      case "ArrowDown":
        window.scrollBy(0, scrollSpeed);
        break;
    }
  });
}
// キーボードでスクロール

// 本を開いて中心に移動
storiesItems.forEach((item) => {
  item.addEventListener("click", function (event) {
    event.preventDefault(); // prevent immediate relink
    let cover = this.querySelector(".stories-item-cover");
    let link = this.querySelector("a").getAttribute("href");
    cover.classList.add("openBook");
    scrollToElement(this);
    setTimeout(() => {
      cover.classList.remove("openBook");
    }, 1550);

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
function fadeInItems() {
  storiesRow.forEach((item, index) => {
    item.style.opacity = "0";
    setTimeout(() => {
      item.classList.add("fadeInBottom");

      setTimeout(() => {
        item.style.opacity = "1";
        item.classList.remove("fadeInBottom");
      }, 800);
    }, index * 200); // 100msずつ遅延させて順番にフェードイン
  });
}
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

// ↑↑↑↑↑↑↑↑↑ page scroll animation ↑↑↑↑↑↑↑↑↑

if (document.querySelector(".stories-wrap")) {
  window.onload = function () {
    // centerView();
    enableDragging();
    keyboardScrolling();
    fadeInItems();
  };
}
