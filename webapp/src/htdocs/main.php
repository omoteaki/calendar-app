<?php
require_once ('../model/common.php');
require_once ('../model/date.php');
require_once ('../model/database.php');
require_once ('../model/schedule.php');

session_start();
if (empty($_POST['ajax'])) {
  require_once ('../view/parts/head.html');
  }

require_once ('../controller/main.php');
