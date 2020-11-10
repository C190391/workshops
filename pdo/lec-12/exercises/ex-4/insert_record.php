<?php 
$dsn = "mysql:host=localhost;dbname=sampledb;charset=utf8"; 
$user = "sampledb_admin"; 
$password = "admin123"; 

$name = $_POST["name"];
$age = $_POST["age"];
$email = $_POST["email"];
isset($_POST["blood"]) ? $blood = $_POST["blood"] : $blood = "";

$message = "";
$options = [];
$params = [];

try {
  $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
  $options[PDO::ATTR_EMULATE_PREPARES] = true;
  $pdo = new PDO($dsn, $user, $password, $options);
  
  $sql  = "";
  $sql .= "insert into user (name, age, blood, email) ";
  $sql .= "values (:name, :age, :blood, :email)";
  
  $pstmt = $pdo->prepare($sql);

  $params = [];
  $params[":name"] = $name;
  $params[":age"] = $age;
  $params[":blood"] = $blood;
  $params[":email"] = $email;
  
  $pstmt->execute($params);
  $message = "レコードは登録されました。";

  // ADD START
  $sql = "select * from user";
  $pstmt = $pdo->prepare($sql);
  $pstmt->execute();

  $records = [];
  $records = $pstmt->fetchAll(PDO::FETCH_ASSOC);
  // ADD END
  
} catch (PDOException $e) {
  echo $e->getMessage();
  $message = "レコードは登録されませんでした。";
} finally {
  unset($pstmt);
  unset($pdo);
}
?>
<html>
<head>
  <meta charset="UTF-8" />
  <title>画面遷移を伴う検索結果を表示する - レコードの登録</title>
  <style>
    th, th {padding: 0.25rem 0.75rem;}
    th {color: lightgray;background-color: dimgray;}
    td[colspan="2"] {padding: 1.25rem;text-align: center;}
  </style>
</head>
<body>
  <h1>画面遷移を伴う検索結果を表示する</h1>
  <h2>レコードの登録</h2>
  <!-- ADD START -->
  <?php if (count($records) > 0): ?>
  <h3>実行結果</h3>
  <p><?= $message ?></p>
  <table border="1">
    <tr>
      <th>ID</th>
      <th>氏名</th>
      <th>年齢</th>
      <th>血液型</th>
      <th>電子メール</th>
    </tr>
    <?php foreach ($records as $record): ?>
    <tr>
      <td><?= $record["id"] ?></td>
      <td><?= $record["name"] ?></td>
      <td><?= $record["age"] ?></td>
      <td><?= $record["blood"] ?></td>
      <td><?= $record["email"] ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
  <?php endif; ?>
  <!-- ADD END -->
</body>
</html>