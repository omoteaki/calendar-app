<?php

class Update{

  function __construct() {
    require_once ('../model/database.php');

  }

  public function getScheduleData($scheduleId){
    $database = new Database();
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        #scheduleとschedule_detailを内部結合
        "SELECT
          schedule.date, schedule.delete_flag, schedule_detail.title
        FROM schedule
        INNER JOIN schedule_detail
        ON schedule.id = schedule_detail.schedule_id
        #表示月の予定のみ抽出
        WHERE schedule.id >= ?"
        );
      $stmt->execute([$scheduleId]);
      // var_dump($firstDate);
      // var_dump($nextFirstDate);
      return $stmt->fetch();

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }





}