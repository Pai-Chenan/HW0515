<?php
// 1. 強制開啟 PHP 錯誤回報，這樣如果卡住或壞掉，畫面會直接印出原因，不會再變空白！
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 開啟輸出緩衝區，確保 progress 進度能即時刷出畫面
ob_start();

require '../db.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$subject = $_POST['subject'] ?? '';
$content = $_POST['content'] ?? '';
$mode = $_POST['mode'] ?? 'all';
$delay = (int)($_POST['delay'] ?? 2);
$random_count = (int)($_POST['random_count'] ?? 5);

if ($mode == 'all') {
    $stmt = $pdo->query("SELECT * FROM subscribers");
} else {
    // 這裡補上安全的整數轉型，防止 SQL 注入
    $stmt = $pdo->query("SELECT * FROM subscribers ORDER BY RAND() LIMIT $random_count");
}

$emails = $stmt->fetchAll();
$total = count($emails);
$current = 0;

if ($total === 0) {
    die("資料庫中沒有任何 Email 名單，請先去新增！");
}

foreach ($emails as $row) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'a1123359@mail.nuk.edu.tw'; 
        $mail->Password = 'qlcyhfynjfbydlhg'; // 你更新後的密碼
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // 2. 補上這段：解除 Mac 本機環境因缺乏 SSL 憑證導致 PHPMailer 崩潰的問題
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // 設定中文編碼防止主旨亂碼
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('a1123359@mail.nuk.edu.tw', '郵件系統');
        $mail->addAddress($row['email']);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = nl2br($content);

        $mail->send();
        echo "已寄送：" . $row['email'] . "<br>";

    } catch (Exception $e) {
        // 3. 這裡改為印出更詳細的錯誤原因，方便我們抓蟲
        echo "<span style='color:red;'>失敗：" . $row['email'] . " ｜ 錯誤原因: " . $mail->ErrorInfo . "</span><br>";
    }

    $current++;
    $progress = round(($current / $total) * 100);

    echo "進度：" . $progress . "%<hr>";

    // 強制將快取畫面推送到瀏覽器上
    ob_flush();
    flush();

    sleep($delay);
}

echo "<h3>全部寄送完成！</h3>";
?>