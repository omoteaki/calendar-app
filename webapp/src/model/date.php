<?php
#作業している当日(ページを開いている日)を基準にカレンダーを作成
class Calendar {
  public $year;
  public $month;

  public function __construct($year, $month){
    $this->year = $year;
    $this->month = $month;
  }

  #日付を計算
  public function get_date($format,$targetday,$num) {
    return date($format, mktime(0, 0, 0, $this->month + $num, $targetday, $this->year));
  }


  #下記のメソッドは、makeCalendar()により不要
  // #配列の作成
  // public function make_days($num){
  //   $lastday = $this->get_date('j',0,1+$num);
  //   $newmonth = $this->get_date('n',1,0+$num);
  //   $array = range(1,$lastday);
  //   $array[0] =  $newmonth . '/1';
  //   return $array;
  // }

  #下記のメソッドは、makeCalendar()により不要
  #前の月の表示させる日数を決めることで、今月のスタート地点も決まる
  // public function make_calendar($num){
  //   $preLastday = $this->get_date('j',0,$num); //前の月の末日(一桁表示)
  //   $preMonth = $this->get_date('n',1,$num-1); //前の月(一桁表示)
  //   $weeknumber = $this->get_date ('w',1,$num);
  //   $mainDays = $this->make_days($num); //当月の日数が入っている配列
  //   $nextDays = $this->make_days($num+1); //次の月の日数が入っている配列

  //   $preDays = []; //カレンダーに表示させる前の月の日数を入れる配列
  //   $i = 0;
  //   while ($i< $weeknumber) {
  //     array_unshift($preDays, $preLastday - $i);
  //     $i++;
  //   }
  //   $calendar = array_merge($preDays,$mainDays,$nextDays);

  //   #カレンダーの表記を整理
  //   if ($weeknumber > 0) {
  //     $calendar[0] = $preMonth . '/' . $calendar[0];
  //   }else {
  //     $calendar[0] = 1;
  //   }

  //   return $calendar;
  // }

  #カレンダーに表示させる日付を"Y-m-d"の形で配列作成
  public function makeCalendar(){
    $weeknumber = $this->get_date ('w',1,0);
    $lastday = $this->get_date('j',0,1);
    $days = [];
    $i = 0;
    if($lastday + $weeknumber < 35) {
      while($i < 35){
        $days[$i] = $this->get_date ('Y-m-d',1 + $i - $weeknumber,0);
        $i++;
      }
      return $days;
    }else{
      while($i < 42){
        $days[$i] = $this->get_date ('Y-m-d',1 + $i - $weeknumber,0);
        $i++;
      }
      return $days;
    }


  }

  #カレンダーへの表示を調整
  public function showDate($date,$index){
    if($index === 0){
      h(date('n',strtotime($date)) . '/' . date('j',strtotime($date)));
    }elseif (date('j',strtotime($date)) == 1){
      h(date('n',strtotime($date)) . '/1');
    }else{
      h(date('j',strtotime($date)));
    }
  }

  #classをつける条件分岐
  public function addClass($date,$index){
    if ($index === 0){
      h('sunday ');
    }elseif($index === 6){
      h('saturday ');
    }
    if (date('n',strtotime($date)) == $this->month){
      h('main');
    }else{
      h('zengo');
    }
  }





  #動的なカレンダーのために
  public function date_validation($array){
    $dateY = (preg_match("/^[1-3][0-9]{3}$/",$array["dateYear"]) ); //4桁の1000~3999ならOK
    $dateM = (preg_match("/^[0-9]$|^[0][0-9]$|^[1][0-3]$/",$array["dateMonth"]));

    if ($array == null){
      return array($this->year,$this->month);
    }elseif ($dateY === 1 && $dateM === 1){
      $this->year = $array["dateYear"];
      $this->month = $array["dateMonth"];
      return array($this->year,$this->month);
    }elseif ($dateM === 1){
      echo '年を指定してください';
      return array($this->year,$this->month);
    }elseif ($dateY === 1){
      echo '1~12の月を指定してください';
      return array($this->year,$this->month);
    }else{
      echo '年と1~12の月を指定してください';
      return array($this->year,$this->month);
    }
  }



  //下記メソッドは一旦コメントに
  // public function pick_up_week($weeknumber,$lastday,$day){
  //   $dateDay = $day;
    
  //   // if($array == null){
  //     // $dateDay = date ('j',strtotime($today)); //当月表示のときは今日がある週を表示させる
  //   // }
    
  //   #"dateDay"で受け取った値が表示しているカレンダーにあるのか確認する
  //   #あったら JS用の配列を作成
  //   if ($dateDay >=1 && $dateDay <= $lastday ){
  //     $arr=[];
  //     for ( $X = 0; $X < 6; $X++){
  //       if ($dateDay + $weeknumber -1 >= $X * 7 && $dateDay + $weeknumber -1 < ($X+1) *7 ){
  //         $arr[]= 'Target';
  //       } elseif($dateDay + $weeknumber -1 < ($X+1) *7) {
  //         $arr[]= $X-1;
  //       } else{
  //         $arr[]= $X;
  //       }
  //     }
    
  //   #なかったらエラー表示
  //   }
  //   // } else {
  //     // echo '1〜' . $lastday . 'の日を指定してください';
  //   // }
  //   return $arr;
  // }

}



