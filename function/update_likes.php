<?php
$jsonFile = '/data/likes_data.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $data['likes'] = (int)$data['likes'] + 1;
   file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
   echo $data['likes'];
}
