<?php
#スケジュール管理に関するクラス
class Schedule{
  public $year;
  public $month;

  public function __construct($year, $month){
    $this->year = $year;
    $this->month = $month;
  }

  #最終的に表示させるメソッド
  public function show($array,$date){
    $scheduleDate = date('Y-m-d',strtotime($array['date']));

    #表示を整える
    #時間の部分を取り出す
    $scheduleTime = substr($array['date'],11,8);
    #取り出せたら
    if($scheduleTime !== false){
      $scheduleTime = date('H:i',strtotime($scheduleTime)) . '~';
    #取り出せなかったら(false)
    }else{
      $scheduleTime = NULL;
    }

    if($scheduleDate == $date){
      return sprintf('%s %s' ,$array['title'], $scheduleTime);
    }
  }

  #delete_flagに関するCSSクラス付与のメソッド
  public function addFlag($array,$date){
    $scheduleDate = date('Y-m-d',strtotime($array['date']));
    if($array['delete_flag'] === 1 && $scheduleDate == $date){
      return 'delete';
    }
  }
  public function getScheduleId($array,$date){
    $scheduleDate = date('Y-m-d',strtotime($array['date']));
    if($scheduleDate == $date){
      return $array['id'];
    }
  }

}







