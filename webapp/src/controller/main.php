<?php
session_start();
if (!isset($_SESSION["login"])) {
  unset($_SESSION['login']);
  session_destroy();
  header("Location: /");
  exit();
}

$userID = $_SESSION["login"];
date_default_timezone_set ('Asia/Tokyo');
$today = date ('Y-n-j'); //今日を起点としたカレンダー作り
// $today = '2020-12-24'; //指定した日付(確認用)

$day = date ('j',strtotime($today));
$year = $_POST['year'];
$month = $_POST['month'];

if($year == NULL && $month == NULL){
  if(isset($_SESSION['year'])){
    $year = $_SESSION['year'];
    $month = $_SESSION['month'];
    unset($_SESSION['year']);
    unset($_SESSION['month']);

  }else{
    $year = date ('Y',strtotime($today));
    $month = date ('n',strtotime($today));
  }
}

#カレンダーを作成

$pre = new Calendar($year,$month);

#monthが0と13になってしまった時の表記を直す
$year = $pre->get_date('Y',1,0);
$month = $pre->get_date('n',1,0);

$main = new Calendar($year,$month);

#CSS,予定に関する情報
$weeknumber = $main->get_date ('w',1,0); //0~6
$lastday = $main->get_date('j',0,1); //月の日数(一桁表示)

#DBから予定を取り出す
$firstDate = $main->get_date('Y-m-d',1,0);
$nextFirstDate = $main->get_date('Y-m-d',1,1);

$calendar = $main->makeCalendar();
$dataBase = new Database();

$id = $_SESSION['id'];


$schedules1 = $dataBase->getData($firstDate,$nextFirstDate,$id); //配列として取得


#予定に関する情報
$schedules = new Schedule($year,$month);

unset($_SESSION['thema_message']);

$message = $_SESSION['message'];

if (isset($_POST['ajax']) && $_POST['ajax']) {
  require_once ('../view/parts/contents.html');
} else {
  require_once ('../view/main.html');
}
