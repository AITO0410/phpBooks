<?php
session_start();


$username = "";
$password = "";
$error_message = "";

if(isset($_POST['decision']) && $_POST['decision'] == 1) 
    // Check if both username and password are submitted
    if(isset($_POST['name']) && isset($_POST['pass'])) {
        $username = $_POST['name'];
        $password = $_POST['pass'];

        // Check if username and password are not empty
        if (!empty($username) && !empty($password)) {
            // Check credentials
            if ($username === "yse" && $password === "2021") {
                // Set username in session
                $_SESSION['username'] = $username;
                // Set login flag to true
                $_SESSION['login'] = true;
                // Redirect to inventory.php
                header("Location: zaiko_ichiran.php");
                exit; // Ensure script termination after redirect
            } else {
                $error_message = "ユーザー名かパスワードが間違っています";
            }
        } else {
            $error_message = "名前かパスワードが未入力です";
        }
    }

if (isset($_SESSION['error2'])) {
    // Retrieve error message from session
    $error_message = $_SESSION['error2'];
    // Clear the session variable
    unset($_SESSION['error2']);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ログイン</title>
<link rel="stylesheet" href="css/login.css" type="text/css" />
</head>
<body id="login">
    <div id="main">
        <h1>ログイン</h1>
        <?php
        // Display error message if exists
        if (!empty($error_message)) {
            echo "<div id='error'>$error_message</div>";
        }
        ?>
        <form action="login.php" method="post" id="log">
            <p>
                <input type='text' name="name" size='5' placeholder="Username">
            </p>
            <p>
                <input type='password' name='pass' size='5' maxlength='20' placeholder="Password">
            </p>
            <p>
                <button type="submit" name="decision" value="1" id="button">Login</button>
            </p>
        </form>
    </div>

</body>
</html>