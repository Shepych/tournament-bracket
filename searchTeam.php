<?php

$postData = json_decode(file_get_contents("php://input"), true);
require_once './database.php';
$search = preg_replace('/[^a-zA-Zа-яА-Я. -_]+/u', '', $postData['search']);

$query = "SELECT * FROM clubs WHERE name LIKE '%" . $search . "%' LIMIT 20 ";
$clubs = $mysql->query($query)->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    'status' => true,
    'clubs' => $clubs,
    'query' => $query
]);