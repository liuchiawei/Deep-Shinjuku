<?php
session_start();

require_once 'class/UserData.php';
require_once 'class/User.php';

$userManager = new UserManager();

echo $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
   $username = $_POST['username'];
   $password = $_POST['password'];

   if ($userManager->login($username, $password)) {
      echo "ログイン成功!";
      header('Location: story.php');
      exit();
   } else {
      echo "ユーザーネームまたはパスワードが間違っています.";
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <title>ログイン</title>
</head>

<body>
   <h2>ログイン</h2>
   <form method="POST">
      <label for="username">ユーザーネーム:</label>
      <input type="text" name="username" required><br><br>

      <label for="password">パスワード:</label>
      <input type="password" name="password" required><br><br>

      <button type="submit" name="login">ログイン</button>
   </form>
   <a href="./register.php">登録</a>
</body>

</html>