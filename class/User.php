<?php

class User
{
   public string $userId;
   public string $username;
   public string $password;

   public function __construct(string $userId, string $username, string $password)
   {
      $this->userId = $userId;
      $this->username = $username;
      $this->password = $password;
   }
}
