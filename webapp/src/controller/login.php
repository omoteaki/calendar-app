<?php
require_once ('../model/date.php');
require_once ('../model/database.php');
require_once ('../model/schedule.php');

session_start();

$login = new Database();

#ログイン情報があり、セッションの有効期限内であればログイン
if (isset($_SESSION["login"])) {
  #最終アクセスとDBに保存されてる有効期限を比べる
  $accessTime = time(); //最終アクセス時間
  $userID = $_SESSION["login"];
  $sessionExpire = $login->getExpiration($userID);

  if($sessionExpire >= $accessTime){
    #有効期限以内
    #新しい有効期限を登録
    $login->setExpiration($userID);

    #メインページを開く
    require_once ('../view/parts/head.html');
    header('Location: /main');
    exit;
  }else{
    #有効期限超えていたらloginページを表示
    #DBに登録されているsessionIDと有効期限をクリア
    $login->sessionClear($userID);
    unset($_SESSION["login"]);
    header('Location: /');
    exit;
  }
}


#ログインの情報がない
session_regenerate_id();
$sessionID = session_id();

#ユーザーIDとパスワードをフォームから取得
#フォームから受け取った値
$userID = filter_input(INPUT_POST, 'userID');
$inputPassword = filter_input(INPUT_POST, 'password');
$inputSessionId = filter_input(INPUT_POST, 'sessionID');

$pass = $login->getPass($inputPassword);
$resurt = $login->getPassword($userID);

#取得したuserIDで登録されているpasswordを返す
$userData = $login->getPassword($userID);
$password= $userData["password"]; // DBに保存されているpassword

if($userID == NULL && $inputPassword == NULL && $userID == "" && $inputPassword == "" ){
  #デフォルト
  $message = 'ログインIDとパスワードを入力してください';

}elseif($password === NULL || $password != $pass ){
  #一致しなかった場合
  $message = 'もう一度ユーザーIDとパスワードを確認してください。';

}else{
  #一致したら
  #DBに登録されているsessionIDを更新
  $login->sessionIdUpdate($inputSessionId,$userID);

  #新しい有効期限を登録
  $login->setExpiration($userID);

  #必要な情報を渡す
  $user = $login->getId($userID);
  $_SESSION['id'] = $user[id];

  $color = $login->getThema($user[id]);
  $_SESSION['color_id'] = $color["color_id"];

  $_SESSION["login"] = $userID;

  #メインページを開く
  require_once ('../view/parts/head.html');
  header('Location: /main');
  exit;
}

require_once ('../view/parts/head.html');
require_once ('../view/login.html');