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

    //コメントを追加する
    public function addComment($storyId, $author, $content)
    {
        // jsonファイルを読み込む
        $data = json_decode(file_get_contents($this->jsonFile), true);

        $newComment = [
            'comment_id' => uniqid(),
            'author' => $author,
            'time' => date('Y-m-d H:i:s'),
            'content' => $content
        ];

        foreach ($data as &$story) {
            if ($story['id'] == $storyId) {
                $story['comments'][] = $newComment;
                file_put_contents($this->jsonFile, json_encode($data, JSON_PRETTY_PRINT));
                return $newComment;
            }
        }

        return null;
    }

    //全てのコメントを取得する
    public function getComments($storyId)
    {
        foreach ($this->data as $story) {
            if ($story['id'] == $storyId) {
                return $story['comments'];
            }
        }
        return [];
    }

    //コメントIDに一致するコメントを編集
    public function editComment($storyId, $commentId, $newContent)
    {
        foreach ($this->data as &$story) {
            if ($story['id'] == $storyId) {
                foreach ($story['comments'] as &$comment) {
                    if ($comment['comment_id'] == $commentId) {
                        $comment['content'] = $newContent;
                        $comment['time'] = date('Y-m-d H:i:s');
                        $this->saveData();
                        return true;
                    }
                }
            }
        }
        return false;
    }
    //コメントIDに一致するコメントを削除
    public function deleteComment($storyId, $commentId)
    {
        foreach ($this->data as &$story) {
            if ($story['id'] == $storyId) {
                foreach ($story['comments'] as $index => $comment) {
                    if ($comment['comment_id'] == $commentId) {
                        unset($story['comments'][$index]);
                        $story['comments'] = array_values($story['comments']);
                        $this->saveData();
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
