<?php
require 'db.php';

$stmt = $pdo->query(
    "SELECT * FROM subscribers ORDER BY id DESC"
);

$rows = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email 列表</title>
</head>
<body>

<h2>Email 列表</h2>

<table border="1" cellpadding="10">

<tr>
    <th>No.</th>
    <th>Email</th>
</tr>

<?php foreach($rows as $row): ?>

<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['email'] ?></td>
</tr>

<?php endforeach; ?>

</table>

</body>
</html>