<?php
/* 
【機能】
書籍の入荷数を指定する。確定ボタンを押すことで確認画面へ入荷個数を引き継いで遷移す
る。なお、在庫数は各書籍100冊を最大在庫数とする。

【エラー一覧（エラー表示：発生条件）】
このフィールドを入力して下さい(吹き出し)：入荷個数が未入力
最大在庫数を超える数は入力できません：現在の在庫数と入荷の個数を足した値が最大在庫数を超えている
数値以外が入力されています：入力された値に数字以外の文字が含まれている
*/

/*
 * ①session_status()の結果が「PHP_SESSION_NONE」と一致するか判定する。
 * 一致した場合はif文の中に入る。
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//③SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
if (!isset($_SESSION['login']) || $_SESSION['login'] !== false) {
    $_SESSION['error2'] = "ログインしてください";
    header("Location: login.php");
    exit;
}

//⑥データベースへ接続し、接続情報を変数に保存する
$host = 'localhost';
$dbname = 'phpBooks';
$username = 'phpBooks';
$password = 'zaiko';

//⑦データベースで使用する文字コードを「UTF8」にする
try {
    // データベースに接続する
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // データベースで使用する文字コードをUTF-8に設定する
    $pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// 入荷処理
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 入荷個数のバリデーションと最大在庫数のチェック
    foreach ($_POST['books'] as $book_id => $quantity) {
        // 入荷個数が未入力かどうかをチェックする
        if (empty($quantity)) {
            $_SESSION['error2'] = "入荷する商品が選択されていません";
            header("Location: zaiko_ichiran.php");
            exit;
        }
        function getId($id, $con){
            // その際にWHERE句でメソッドの引数の$idに一致する書籍のみ取得する。
            $sql = "SELECT * FROM books WHERE id = :id";
        
            //⑪書籍を取得するSQLを作成する実行する。
            $stmt = $con->prepare($sql);
        
            //SQLの実行結果を変数に保存する。
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
            
            $stmt->execute();
        
            // ⑫実行した結果から1レコード取得し、returnで値を返す。
            $book = $stmt->fetch(PDO::FETCH_ASSOC);
        
         
            $stmt->closeCursor();
        
            return $book;
        }}
        
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>入荷</title>
    <link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
    <!-- ヘッダ -->
    <div id="header">
        <h1>入荷</h1>
    </div>

    <!-- メニュー -->
    <div id="menu">
        <nav>
            <ul>
                <li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
            </ul>
        </nav>
    </div>

    <form action="nyuka_kakunin.php" method="post">
        <div id="pagebody">
            <!-- エラーメッセージ -->
            <div id="error">
            <?php
            /*
             * ⑬SESSIONの「error」にメッセージが設定されているかを判定する。
             * 設定されていた場合はif文の中に入る。
             */ 
            if(isset($_SESSION['error'])){
                // ⑭SESSIONの「error」の中身を表示する。
                echo $_SESSION['error'];
                // エラーメッセージを表示した後にセッション変数をクリアする
                $_SESSION['error'] = '';
            }
            ?>
            </div>
            <div id="center">
                <table>
                    <thead>
                        <tr>
                            <th id="id">ID</th>
                            <th id="book_name">書籍名</th>
                            <th id="author">著者名</th>
                            <th id="salesDate">発売日</th>
                            <th id="itemPrice">金額(円)</th>
                            <th id="stock">在庫数</th>
                            <th id="in">入荷数</th>
                        </tr>
                    </thead>
                    <?php 
                    /*
                     * ⑮POSTの「books」から一つずつ値を取り出し、変数に保存する。
                     */
                    foreach($_POST['books'] as $book_id){
                        // ⑯「getId」関数を呼び出し、変数に戻り値を入れる。その際引数に⑮の処理で取得した値と⑥のDBの接続情報を渡す。
                        $book = getId($book_id, $pdo);
                    ?>
                    <input type="hidden" value="<?php echo $book['id'];?>" name="books[]">
<tr>
    <td><?php echo $book['id'];?></td>
    <td><?php echo $book['title'];?></td>
    <td><?php echo $book['author'];?></td>
    <td><?php echo $book['salesDate'];?></td>
    <td><?php echo $book['price'];?></td>
    <td><?php echo $book['stock'];?></td>
    <td><input type='text' name='stock[]' size='5' maxlength='11' required></td>
</tr>
                    <?php
                     }
                    ?>
                </table>
                <button type="submit" id="kakutei" formmethod="POST" name="decision" value="1">確定</button>
            </div>
        </div>
    </form>
    <!-- フッター -->
    <div id="footer">
        <footer>株式会社アクロイト</footer>
    </div>
</body>
</html>
