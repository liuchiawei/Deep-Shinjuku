<?php
$jsonFile = '/data/stories.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

$likes = (int)$data['likes'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $likes++;
   file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
}


//TODO:Javascript でいいねボタンを押した時にリアルタイムで更新するようの機能を追加しよう
//codeは以下の通りと思います。

// function updateLikes() {
//    var xhr = new XMLHttpRequest();
//    xhr.open("POST", "update_likes.php", true);
//    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//    xhr.onreadystatechange = function () {
//        if (xhr.readyState === 4 && xhr.status === 200) {
//            document.getElementById("likeCount").innerText = xhr.responseText;
//        }
//    };
//    xhr.send();
// }