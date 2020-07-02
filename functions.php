<?php
function ConnectDB()
{
    $dsn = 'mysql:dbname=gsacf_d06_07;port=3306;host=localhost';
    $user = 'root';
    $password = '';
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET 'utf8'");
    //ut8でよろしく
    try {
        $dbh = new PDO($dsn, $user, $password, $options);
        return $dbh;
    } catch (PDOException $e) {
        // DB接続に失敗した場合はここでエラーを出力し，以降の処理を中止する
        echo json_encode(["db error" => "{$e->getMessage()}"]);
        exit();
    }
}
