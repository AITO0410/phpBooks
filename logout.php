<?php
/* 
【機能】
	セッション情報を削除しログイン画面に遷移する。
*/

//①セッションを開始する。
session_start();
$_SESSION = array();
//②セッションを削除する。
session_destroy();
//③ログイン画面へ遷移する。
//<<<<<<< HASAN-ZAHID>>
header("Location: login.php");
exit;
?>

