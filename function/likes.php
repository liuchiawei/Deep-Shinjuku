<?php
$jsonFile = '/data/stories.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

$likes = (int)$data['likes'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $likes++;
   file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
}
