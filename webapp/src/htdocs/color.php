<?php
require_once ('../model/common.php');
require_once ('../model/date.php');
require_once ('../model/database.php');
require_once ('../model/schedule.php');


session_start();
require_once ('../view/parts/head.html');

$thema = new Database();

$id = $_SESSION['id'];
$colorId = $_POST['color'];

$color = [1 => 'デフォルト', 2 => 'pink', 3 => 'blue'];

$thema->changeThema($id,$colorId);
$_SESSION['thema_message'] = 'テーマを' . $color[$colorId] . 'に変更しました';
$_SESSION['color_id'] = $colorId;


header('Location: /thema');