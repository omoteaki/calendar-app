<?php
require_once ('../model/database.php');
require_once ('../view/parts/head.html');

session_start();

$logout = new Database();

#DBに登録されているsessionIDと有効期限をクリア
$logout->sessionClear($userID);
unset($_SESSION['login']);
session_destroy();

require_once ('../view/logout.html');