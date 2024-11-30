<?php

require_once __DIR__ . '/User.php';
require_once __DIR__ . '/UserData.php';

class CommentManager
{
    private $jsonFile;
    private $data;

    public function __construct($jsonFile)
    {
        $this->jsonFile = $jsonFile;
        $this->loadData();
    }

    private function loadData()
    {
        if (file_exists($this->jsonFile)) {
            $jsonData = file_get_contents($this->jsonFile);
            $this->data = json_decode($jsonData, true);
        } else {
            $this->data = [];
        }
    }

    private function saveData()
    {
        file_put_contents($this->jsonFile, json_encode($this->data, JSON_PRETTY_PRINT));
    }

    // Thêm bình luận
    public function addComment($storyId, $author, $content)
    {
        if (!isset($_SESSION['user_id'])) {
            return null; // Nếu chưa đăng nhập, không cho phép thêm
        }

        $userId = $_SESSION['user_id'];

        $newComment = [
            'user_id' => $userId,
            'author' => $author, // Giữ lại thông tin người viết bình luận
            'time' => date('Y-m-d H:i:s'),
            'content' => $content
        ];

        foreach ($this->data as &$story) {
            if ($story['id'] == $storyId) {
                $story['comments'][] = $newComment;
                $this->saveData();
                return $newComment;
            }
        }

        return null;
    }

    // Lấy tất cả bình luận
    public function getComments($storyId)
    {
        foreach ($this->data as $story) {
            if ($story['id'] == $storyId) {
                return $story['comments'] ?? [];
            }
        }
        return [];
    }

    // Sửa bình luận
    public function editComment($storyId, $commentIndex, $newContent)
    {
        if (!isset($_SESSION['user_id'])) {
            return false; // Chưa đăng nhập, không cho phép chỉnh sửa
        }

        $userId = $_SESSION['user_id'];

        foreach ($this->data as &$story) {
            if ($story['id'] == $storyId) {
                if (isset($story['comments'][$commentIndex]) && $story['comments'][$commentIndex]['user_id'] === $userId) {
                    $story['comments'][$commentIndex]['content'] = $newContent;
                    $story['comments'][$commentIndex]['time'] = date('Y-m-d H:i:s');
                    $this->saveData();
                    return true;
                }
            }
        }

        return false;
    }

    // Xóa bình luận
    public function deleteComment($storyId, $commentIndex)
    {
        if (!isset($_SESSION['user_id'])) {
            return false; // Chưa đăng nhập, không cho phép xóa
        }

        $userId = $_SESSION['user_id'];

        foreach ($this->data as &$story) {
            if ($story['id'] == $storyId) {
                if (isset($story['comments'][$commentIndex]) && $story['comments'][$commentIndex]['user_id'] === $userId) {
                    unset($story['comments'][$commentIndex]);
                    $story['comments'] = array_values($story['comments']); // Reset lại chỉ số mảng
                    $this->saveData();
                    return true;
                }
            }
        }

        return false;
    }
}
