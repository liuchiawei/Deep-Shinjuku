<?php
require_once 'User.php';

class UserManager
{
   private $usersFile = 'data/users.json';

   // Đăng ký người dùng mới
   public function register(string $username, string $password): ?string
   {
      $users = $this->loadUsers();

      // Kiểm tra người dùng đã tồn tại chưa
      foreach ($users as $user) {
         if ($user->username === $username) {
            return null; // Trả về null nếu username đã tồn tại
         }
      }

      // Mã hóa mật khẩu
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Tạo userId ngẫu nhiên bằng uniqid
      $userId = uniqid('', true);

      // Tạo đối tượng User và thêm vào mảng
      $user = new User($userId, $username, $hashedPassword);

      $users[] = $user;

      // Lưu vào file JSON
      $this->saveUsers($users);

      // Trả về userId khi đăng ký thành công
      return $userId;
   }

   // Đăng nhập người dùng
   public function login(string $username, string $password): bool
   {
      $users = $this->loadUsers();

      // Duyệt qua danh sách người dùng để tìm người dùng phù hợp
      foreach ($users as $user) {
         if ($user->username === $username && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->userId; // Lưu userId vào session
            return true;
         }
      }

      return false;
   }

   // Kiểm tra xem người dùng có đang đăng nhập không
   public function isLoggedIn(): bool
   {
      return isset($_SESSION['user_id']);
   }

   // Nạp dữ liệu người dùng từ file
   private function loadUsers(): array
   {
      if (file_exists($this->usersFile)) {
         $jsonData = file_get_contents($this->usersFile);
         $usersArray = json_decode($jsonData, true);
         $users = [];

         // Chuyển dữ liệu từ mảng sang đối tượng User
         foreach ($usersArray as $userData) {
            $users[] = new User($userData['userId'], $userData['username'], $userData['password']);
         }
         return $users;
      }
      return [];
   }

   // Lưu dữ liệu người dùng vào file
   private function saveUsers(array $users): void
   {
      $usersArray = [];

      // Chuyển đối tượng User sang mảng để lưu vào JSON
      foreach ($users as $user) {
         $usersArray[] = [
            'userId' => $user->userId,
            'username' => $user->username,
            'password' => $user->password
         ];
      }

      file_put_contents($this->usersFile, json_encode($usersArray, JSON_PRETTY_PRINT));
   }
}
