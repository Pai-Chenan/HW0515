<?php
require 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = trim($_POST['email']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $stmt = $pdo->prepare(
            "INSERT IGNORE INTO subscribers(email)
             VALUES(?)"
        );

        $stmt->execute([$email]);

        $message = "新增成功";

    } else {

        $message = "Email 格式錯誤";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>新增 Email</title>
</head>
<body>

<h2>新增 Email</h2>

<form method="POST">

    <input type="email" name="email" required>

    <button type="submit">
        新增
    </button>

</form>

<p><?= $message ?></p>

</body>
</html>