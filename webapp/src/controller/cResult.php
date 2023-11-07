<?php
session_start();
require_once ('../model/date.php');
require_once ('../model/database.php');
require_once ('../model/schedule.php');
require_once ('../view/parts/head.html');

$action = new Database;
$scheduleId = $_POST['scheduleId'];
$dateDay = $_POST['dateDay'];
$_SESSION['year'] = date('Y',strtotime($dateDay));
$_SESSION['month'] = date('n',strtotime($dateDay));

if($_POST['action'] == 'update'){
  $action->updateSchedule($scheduleId,$_POST);
  header('Location: /main');
  exit;

}elseif($_POST['action'] == 'delete'){
  $action->deleteSchedule($scheduleId,$_POST);
  header('Location: /main');
  exit;

}elseif($_POST['action'] == 'hold'){
  $action->holdSchedule($scheduleId);
  header('Location: /main');
  exit;

}elseif($_POST['action'] == 'set'){
  if($_POST['title'] != NULL){
    $_SESSION["message"] = "新規予定を登録しました";
    $action->setSchedule($_POST[id],$_POST);
    header('Location: /main');
    exit;

  }else{
    $_SESSION["message"] = "タイトルを入力してください";
    $_SESSION['year'] = date('Y');
    $_SESSION['month'] = date('n'); 
    header('Location: /main');
    exit;
  }

}