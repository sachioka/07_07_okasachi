<?php

//データベース接続
require "functions.php";
//エラーが出た時の処理（授業のでできる）
try {
  $dbh = ConnectDB();

  $sql = 'SELECT * FROM users';

  $result = $dbh->query($sql);

  //クエリー失敗
  if (!$result) {
    echo $dbh->error;
    exit();
  }

  //レコード件数（エラー確認処理で取れたものを使う）
  $row_count = $result->rowCount();

  //連想配列で取得
  $rows = $result->fetchAll();
  //print_r($rows);

} catch (PDOException $e) {
  print('Error:' . $e->getMessage());
  die();
};

?>

<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <title>ユーザ一覧</title>
  <meta charset="utf-8">
</head>

<body>
  <h1>ユーザ表示</h1>

  レコード件数：<?php echo $row_count; ?>

  <table border='1'>
    <tr>
      <th>id</th>
      <th>name</th>
      <th>screen_name</th>
      <th>フォロワー数</th>
      <th>フォロー数</th>
      <th>listed_count</th>
    </tr>
    <?php
    foreach ($rows as $row) {
      $link = "https://twitter.com/" . $row['screen_name'];
    ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td><a href="<?php echo $link ?>"><?php echo htmlspecialchars($row['screen_name'], ENT_QUOTES, 'UTF-8'); ?></a></td>
        <td><?php echo $row['followers_count']; ?></td>
        <td><?php echo $row['friends_count']; ?></td>
        <td><?php echo $row['listed_count']; ?></td>
      </tr>
    <?php
    }
    ?>

  </table>
<!-- HTMLの冒頭の記述があれば返すデータのところはいらないかも -->
</body>

</html>