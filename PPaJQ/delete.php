<?php
require_once "pdo.php";
require_once "bootstrap.php";
session_start();
// IF there is user_id
if ( !isset($_SESSION["user_id"] ) ) {
die('ACCESS DENIED');
return;
}
// If the user requested cansel go back to index.php
if ( isset($_POST['cansel']) ) {
    header('Location: index.php');
    return;
  }


//Deleting data
if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM Profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}


// If profile_id is exist and user is owner
if ( isset($_GET['profile_id']) ) {
  $p_id = $_GET['profile_id'];
  $u_id = $_SESSION["user_id"];
  $stmt = $pdo->query("SELECT * FROM Profile WHERE profile_id = $p_id
    AND user_id = $u_id");
    $rows = $stmt->fetchAll();
  if ( empty($rows) === FALSE ) {
      //preparing for Profile GET
      $stmt = $pdo->query("SELECT * FROM Profile WHERE profile_id = $p_id");
      $rows = $stmt->fetchAll();
      //preparing for output in form
      foreach ($rows as $row) {
      $first_name = htmlentities($row['first_name']);
      $last_name = htmlentities($row['last_name']);
    }


  } else {
  $_SESSION['error'] = "Could not load profile";
  header('Location: index.php');
  return;
}
} else {
$_SESSION['error'] = "Missing profile_id";
header('Location: index.php');
return;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>17df6762</title>
</head>
<body>
<div class="container">
<h1>Deleting Profile</h1>

<!--Forms-->
<form method="post">

  <div class="input-group mb-3">
    <div class="input-group-prepend">
<span class="input-group-text" id="inputGroup-sizing-default">First name:</span>
</div>
<input type="text" value="<?= $first_name ?>" name="first_name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
</div>

<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="inputGroup-sizing-default">Last name:</span>
</div>
<input type="text" value="<?= $last_name ?>" name="last_name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
</div>

<input type="hidden" name="profile_id" value="<?= $p_id ?>">
<input type="submit" value="Delete" name="delete">
<input type="submit" name="cansel" value="Cansel"/></p>
</form>
</div>
</body>
