<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>寄送郵件</title>
</head>
<body>

<h2>寄送郵件</h2>

<form action="mail/send_mail.php" method="POST">

<label>主旨：</label>
<br>

<input type="text" name="subject" required>

<br><br>

<label>內容：</label>
<br>

<textarea name="content"
rows="10"
cols="50"
required></textarea>

<br><br>

<label>寄送模式：</label>

<select name="mode">

    <option value="all">
        全部寄送
    </option>

    <option value="random">
        隨機寄送
    </option>

</select>

<br><br>

<label>隨機寄送數量：</label>

<input type="number"
name="random_count"
value="5">

<br><br>

<label>寄送間隔秒數：</label>

<input type="number"
name="delay"
value="2">

<br><br>

<button type="submit">
開始寄送
</button>

</form>

</body>
</html>