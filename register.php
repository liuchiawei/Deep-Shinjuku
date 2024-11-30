<?php

session_start();

require_once 'class/UserData.php';
require_once 'class/User.php';

$userManager = new UserManager();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
   $username = $_POST['username'];
   $password = $_POST['password'];

   $userId = $userManager->register($username, $password);

   if ($userId) {
      setcookie('user_id', $userId, time() + (86400 * 30), "/");
      // header('Location: story.php');
      echo $_COOKIE['user_id'];
      exit();

   } else {
      echo "<p style='color:red;'>すでに登録されているユーザーネームです。別の名前を試してください。</p>";
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <title>登録</title>
</head>

<body>
   <h2>登録</h2>
   <form method="POST">
      <label for="username">ユーザーネーム</label>
      <input type="text" name="username" required><br><br>

      <label for="password">パスワード</label>
      <input type="password" name="password" required><br><br>

      <button type="submit" name="register">登録</button>
   </form>
   <a href="./login.php">ログイン</a>
</body>

</html>