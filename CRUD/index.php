<?php
session_start();
require_once "pdo.php";
require_once "bootstrap.php";
$countrow = "";
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html>
<head>
<title>c45ae64f</title>
</head>
<body>
<div class="container">
<h1>Welcome to Autos Database</h1>
<?php
if ( isset($_SESSION["who"] ) ) {
echo('<table class = "table table-bordered table-hover table-condensed">');
echo "<tr><th>Make</th><th>Model</th><th>Year</th><th>Mileage</th><th>Action</th></tr>";
$stmt = $pdo->query("SELECT autos_id, make, model, year, mileage FROM autos");
$count=0;
while ( $row = $stmt->fetch() ) {
    echo "<td>";
    echo(htmlentities($row['make']));
    echo("</td><td>");
    echo(htmlentities($row['model']));
    echo("</td><td>");
    echo(htmlentities($row['year']));
    echo("</td><td>");
    echo(htmlentities($row['mileage']));
    echo("</td><td>");
    echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ');
    echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
    echo("</td></tr>\n");
    $count=$count+1;
}
if ($count < 1) {
  $countrow = "No rows found";
}
  echo "<p>$countrow </p>";
  echo('</table>');
  echo('<p><a href="add.php">Add New Entry</a> </p>');
  echo('<p><a href="logout.php">Logout</a></p>');
} else {
  echo('<p><a href="login.php">Please log in</a></p>');
  echo('<p>Attempt to <a href="add.php">add data</a> without logging in</p>');
}

?>
</div>
</body>
