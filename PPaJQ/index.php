<?php
session_start();
require_once "pdo.php";
require_once "bootstrap.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>17df6762</title>
</head>
<body>
<div class="container">
<?php
if ( !isset($_SESSION['user_id']) ) {
echo("<h1>Resume Registry</h1>");
echo('<a href="login.php">Please log in</a>');
}
else {
  $u_id = $_SESSION['user_id'];
  $stmt3 = $pdo->query("SELECT name FROM users
    WHERE user_id = $u_id");
        $rows3 = $stmt3->fetchAll();
    foreach ( $rows3 as $row ) {
      echo("<h1>".htmlentities($row['name'])."'s Resume Registry</h1>");
      $_SESSION['name'] = $row['name'];
}
echo('<a href="logout.php">Logout</a>');
}
require_once "view.php";
?>

</div>
</body>
