<?php
session_start();
require_once ('../model/date.php');
require_once ('../model/database.php');
require_once ('../model/schedule.php');
// require_once ('../view/parts/head.html');


$update = new Database;


if(empty($_POST['scheduleDate'])){
  
  // $scheduleId = $_GET['schedule'];
  $scheduleId = $_POST['schedule'];
  $detail = $update->getScheduleData($scheduleId);
  
  $title = $detail["title"];
  $date = $detail["date"];
  $scheduleDetail = $detail["detail"];
  
  $dateDay = date('Y-m-d',strtotime($date));
  
  
  $dateTime = substr($detail['date'],11,8);
  #取り出せたら
  if($dateTime !== false){
    $dateTime = date('H:i',strtotime($dateTime));
    #取り出せなかったら(false)
  }else{
    $dateTime = '終日';
  }
}else{
  // $title = '新規予定';

  $id = $_SESSION['id'];
  $dateDay = $_POST['scheduleDate'];
}
require_once ('../view/detail.html');
