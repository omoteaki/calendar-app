<?php
#データベースから予定を取得
class Database {
  #MySQLに接続
  public function connectDb(){
    $dns = 'mysql:host=db;dbname=calendar;charset=utf8mb4';
    $usr = 'user';
    $pwd = 'root';
    $opt = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //カラム名がついた結果だけを表示させる
      PDO::ATTR_EMULATE_PREPARES   => false, //型を保つ
    ];

    try{
      $pdo = new PDO($dns, $usr, $pwd,$opt);
      return $pdo;
    #例外処理
    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }




  
  
  #ユーザーが登録したpasswordの暗号化
  public function getPass($password) {
    // 暗号化する処理
    return // 暗号化したpasswordを返す;
  }

  #暗号化されたpasswordをDBに登録
  public function setPassword($userId, $spass) {

    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "UPDATE user
        SET password = ?
        WHERE user_id = ?"
        );
      $stmt->execute([$spass,$userId]);
  
  
    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }


  #予定に関して必要な情報を返す
  public function getData($firstDate,$nextFirstDate,$userId){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        #scheduleとschedule_detailを内部結合
        "SELECT
          schedule.id, schedule.date, schedule.delete_flag, schedule_detail.title
        FROM schedule
        INNER JOIN schedule_detail
        ON schedule.id = schedule_detail.schedule_id
        #表示月の予定のみ抽出
        WHERE schedule.date >= ?
        AND schedule.date < ?
        AND schedule.user_id = ?"
        );
      $stmt->execute([$firstDate,$nextFirstDate,$userId]);
      return $stmt->fetchAll();

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }

  #入力されたuserIDに登録されているpasswordを返す
  public function getPassword($userID){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "SELECT user.password
         FROM user
         WHERE user.user_id = ?"
        );
      $stmt->execute([$userID]);
      return $stmt->fetch();

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }

  #渡されたuserIDに登録されているsession_idを返す
  public function getSessionId($userID){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "SELECT user.session_id
        FROM user
        WHERE user.user_id = ?"
        );
      $stmt->execute([$userID]);

      return $stmt->fetch();

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }

  #sessionIDを更新
  public function sessionIdUpdate($sessionID,$userID){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "UPDATE user SET session_id = ?
        WHERE user_id = ?"
        );
      $stmt->execute([$sessionID,$userID]);

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }

  #userテーブルのIDを取得
  public function getId($userID){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "SELECT user.id
        FROM user
        WHERE user.user_id = ?"
        );
      $stmt->execute([$userID]);

      return $stmt->fetch();

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }

  #sessionIDを取得する
  public function getExpiration($userID){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "SELECT user.session_expire
        FROM user
        WHERE user.user_id = ?"
        );
      $stmt->execute([$userID]);
      $result = $stmt->fetch();
      return $result["session_expire"];

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }


  #DBにsessionIDの有効期限を登録する
  public function setExpiration($userID){
    $accessTime = time(); //最終アクセス時間
    $newSessionExpire = $accessTime + (60 * 60 * 2); //2時間

    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "UPDATE user
        SET session_expire = ?
        WHERE user_id = ?"
        );
      $stmt->execute([$newSessionExpire,$userID]);


    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }


  #sessionの情報をクリア
  public function sessionClear($userID){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "UPDATE user
        SET session_expire = NULL ,session_id = NULL
        WHERE user_id = ?"
        );
      $stmt->execute([$userID]);


    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }



  #schedule_idから予定に関するデータを取得
  public function getScheduleData($scheduleId){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        #scheduleとschedule_detailを内部結合
        "SELECT
          schedule.id, schedule.date, schedule.delete_flag, schedule_detail.title, schedule_detail.detail
        FROM schedule
        INNER JOIN schedule_detail
        ON schedule.id = schedule_detail.schedule_id
        -- #表示月の予定のみ抽出
        WHERE schedule.id >= ?"
        );
      $stmt->execute([$scheduleId]);
      return $stmt->fetch();

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }

  #予定の変更
  public function updateSchedule($scheduleId,$array){
    $title = trim($array['title']);
    $detail = trim($array['detail']);

    $dateDay = date("Y-m-d",strtotime(trim($array['dateDay'])));
    $dateTime = trim($array['dateTime']);

    if($dateTime == '終日' || $dateTime == NULL || $dateTime == ""){
      $date = $dateDay;
    }else{
      $date = $dateDay . ' ' . $dateTime . ':00';
    }

    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "UPDATE schedule
        SET date = ?
        WHERE schedule.id = ?"
        );
      $stmt->execute([$date,$scheduleId]);
      $stmt = $pdo->prepare(
        "UPDATE schedule_detail
        SET
          title = ?,
          detail = ?
        WHERE schedule_id = ?"
        );
      $stmt->execute([$title,$detail,$scheduleId]);

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }


  #delete flagを追加する
  public function holdSchedule($scheduleId){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "UPDATE schedule
        SET delete_flag = 1
        WHERE schedule.id = ?"
        );
      $stmt->execute([$scheduleId]);

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }

  #予定の削除
  public function deleteSchedule($scheduleId){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "DELETE FROM schedule_detail
        WHERE schedule_id = ?"
        );
      $stmt->execute([$scheduleId]);
      $stmt = $pdo->prepare(
        "DELETE FROM schedule
        WHERE schedule.id = ?"
        );
      $stmt->execute([$scheduleId]);

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }

  #新しい予定の登録
  public function setSchedule($userId,$array){
    $title = trim($array['title']);
    $detail = trim($array['detail']);

    $dateDay = date("Y-m-d",strtotime(trim($array['dateDay'])));
    $dateTime = trim($array['dateTime']);

    if($dateTime == '終日' || $dateTime == NULL || $dateTime == ""){
      $date = $dateDay;
    }else{
      $date = $dateDay . ' ' . $dateTime . ':00';
    }

    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "INSERT INTO schedule (user_id, date)
         VALUES (?, ?)"
        );
      $stmt->execute([$userId,$date]);
      $stmt = $pdo->prepare(
        "INSERT INTO schedule_detail (schedule_id, title, detail)
         VALUES (LAST_INSERT_ID(), ?, ?)"
        );
      $stmt->execute([$title,$detail]);

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }

  }


  #ユーザーのテーマカラーを変更する
  public function changeThema($id,$colorId){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "UPDATE user_detail
        SET color_id = ?
        WHERE user_id = ?"
        );
      $stmt->execute([$colorId,$id]);

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }

  #ユーザーidからテーマカラーを取得する
  public function getThema($id){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        #scheduleとschedule_detailを内部結合
        "SELECT color_id
        FROM user_detail
        WHERE user_id >= ?"
        );
      $stmt->execute([$id]);
      return $stmt->fetch();

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }

  #新しいユーザーの登録(ユーザーIDとpasswordの登録)
  
  // 確認事項
  // - どのカラムに登録するのか
  // - どういう引数でデータを受け取るのか
  // - メソッドの通例的な名前は？？(一応newUserにする)

  public function newUser($userId, $hashPass){

    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "INSERT INTO user (user_id, password)
         VALUES (?, ?)"
        );
      $stmt->execute([$userId,$hashPass]);

      $id = $pdo->lastInsertId();

      $name = "guest";
      $stmt = $pdo->prepare(
        "INSERT INTO user_detail (name, user_id)
         VALUES (?, ?)"
        );
      $stmt->execute([$name, $id]);

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }

  // // 今登録できたユーザーの詳細を仮登録する
  // public function newUserDetail($userId){
  //   try {
  //     $pdo = $this->connectDb();
  //     $stmt = $pdo->prepare(
  //       "SELECT user.id
  //       FROM user
  //       WHERE user.user_id = ?"
  //       );
  //     $stmt->execute([$userId]);
  //     $result = $stmt->fetch();
  //     $id = $result["id"];
  //     $name = "guest";
  //     $stmt = $pdo->prepare(
  //       "INSERT INTO user_detail (name, user_id)
  //        VALUES (?, ?)"
  //       );
  //     $stmt->execute([$name, $id]);


  //   } catch (PDOException $e){
  //     echo $e->getMessage();
  //     exit;
  //   }
  // }

#入力されたユーザーIDが他に登録されてないか確認する
//登録されていたら1が返る
  public function valiUserId($userId){
    try {
      $pdo = $this->connectDb();
      $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM user WHERE user_id = ?"
        );
      $stmt->execute([$userId]);
      return $stmt->fetchColumn();

    } catch (PDOException $e){
      echo $e->getMessage();
      exit;
    }
  }





}
