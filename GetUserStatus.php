<?php

require './vendor/autoload.php';
//composerという集団のまとめ役、ありがとう！！
require "functions.php";
// TwitterOAuthクラスをインポート
use Abraham\TwitterOAuth\TwitterOAuth;
//ここは.envで隠す必要あり
$CK = 'YN8eKeywLslpv4rqUBt6A8VMQ'; 
$CS = 'E10SLyzTnrtzSwKX7OWEAFAPoBOyKg8XML3wBRGYzsyJna1iVZ'; 
$AT = '376883364-FvyhZmxDlt3KR4wqIm1Txntbx3fh9eUqYLG0DgI1'; 
$AS = 'AzKgkodWCDCvlMN91JTpvz3ePgl9oZi3gilOwzIN3KBOf';

$target = $_POST;
if($target['name_1']!="++rank_generate"){
  //ここからユーザー登録の時の動作
     // TwitterOAuthクラスのインスタンスを作成
  $twitter = new TwitterOAuth($CK, $CS, $AT, $AS);
  //print('target='.$target[0]."<br>");
  
  // ステータスの取得
    $request= $twitter->get("users/show", ["screen_name" => $target]);
    $id              = $request->id;
    $name            = $request->name;
    $screen_name     = $request->screen_name;
    $followers_count = $request->followers_count;
    $friends_count   = $request->friends_count;
    $listed_count    = $request->listed_count;
  
  try{
      $dbh = ConnectDB();
      $sql = 'INSERT into users (id, name, screen_name, followers_count, friends_count, listed_count) values (?, ?, ?, ?, ?, ?)';
      $stmt = $dbh->prepare($sql);
      $flag = $stmt->execute(array($id,$name,$screen_name,$followers_count,$friends_count,$listed_count));
  }catch (PDOException $e){
      exit();
  }
  //オブジェクトを開放する
  //オブジェクトは実体を持たせるとメモリを消費するから
  $dbh = null;
  exit();
} else {

  $res = ORDERbyFollowers();

  foreach ( $res as $row) {
      $dataset = array();
      $dataset['backgroundColor'] = dynamicColors(intval($row['id']));
      //intvalで数値にしないと
      $dataset['label'] = $row['name'];
      $dataset['data'][] = array('x'=>strval($row['name']),'y'=>intval($row['followers_count']));
      $Data[] = $dataset;
  }

  echo json_encode($Data);
  
  $dbh = null;
  exit();
}

function ORDERbyFollowers(){
try{
    $dbh = ConnectDB();
    $sql = 'SELECT * from users ORDER BY followers_count DESC';
    $res = $dbh->query($sql);
    return $res;
}catch (PDOException $e){
    exit();
}

};
//色をバラバラにする関数
function dynamicColors( $seed ) {
    // srand($seed);
    $r = rand(0,255);
    $g = rand(0,255);
    $b = rand(0,255);
    return "rgb(".$r.",".$g.",".$b.")";
 };

?>
