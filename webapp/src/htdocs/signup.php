<?php
// session_start();
require_once ('../model/common.php');
require_once ('../model/date.php');
require_once ('../model/database.php');
require_once ('../model/schedule.php');
require_once ('../view/parts/head.html');

session_start();

$signup = new Database();

#入力されたユーザーIDとパスワードを保存する
$userId = filter_input(INPUT_POST, 'userID');
$inputPassword = filter_input(INPUT_POST, 'password');
// $inputSessionId = filter_input(INPUT_POST, 'sessionID');

$message = '';

#パスワードを暗号化する
$password = $signup->getPass($inputPassword);

#入力されたユーザーIDが他に登録されてないか確認して、
#登録されていなかったら
$check = $signup->valiUserId($userId);



if($check === 1){
  $message = '入力いただいたユーザーIDはすでに登録されています。他のIDで登録してください。';
} else {

  #暗号化されたパスワードを、DBに新規登録
  if($userId == NULL && $inputPassword == NULL && $userId == "" && $inputPassword == "" ){
    #デフォルト
    // $message = 'ログインIDとパスワードを入力してください';
  
  }else{
  
    $signup->newUser($userId, $password);
    // $signup->newUserDetail($userId);
    
    session_regenerate_id();
    $sessionId = session_id();
    $signup->sessionIdUpdate($sessionId,$userId);

    $signup->setExpiration($userId);
    $_SESSION["login"] = $userId;
    $user = $signup->getId($userId);
    $_SESSION['id'] = $user[id];
    $_SESSION['color_id'] = 1;
    
    #メインページを開く
    require_once ('../view/parts/head.html');
    header('Location: /main');
    exit;
  }
};



require_once ('../view/signup.html');
