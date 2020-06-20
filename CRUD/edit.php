<?php
require_once "pdo.php";
session_start();
if ( !isset($_SESSION["who"] ) ) {
    // Redirect the browser to autos.php
    die('ACCESS DENIED');
    return;
    // Data validation
}
if ( isset($_POST['make']) || isset($_POST['model']) || isset($_POST['year'])
     || isset($_POST['mileage'])) {
       if (( strlen($_POST["make"]) > 1) && ( strlen($_POST["model"]) > 1) && ( strlen($_POST["year"]) > 1)
       && ( strlen($_POST["mileage"]) > 1)) {
       // Checking if the mileage and year are integers.
           if ( is_numeric($_POST['mileage']) === TRUE && is_numeric($_POST['year']) === TRUE ) {
             //Checking if the make is not empty
               if ( strlen($_POST["make"]) > 1  ) {
                 $sql = "UPDATE autos SET make = :make,
                         model = :model, year = :year, mileage = :mileage
                         WHERE autos_id = :autos_id";
                 $stmt = $pdo->prepare($sql);
                 $stmt->execute(array(
                     ':make' => $_POST['make'],
                     ':model' => $_POST['model'],
                     ':year' => $_POST['year'],
                      ':mileage' => $_POST['mileage'],
                     ':autos_id' => $_POST['autos_id']));
                 $_SESSION['success'] = 'Record updated';
                 header( 'Location: index.php' ) ;
                 return;

             } else
                 $_SESSION['error'] = 'Make is required';
                 header( 'Location: edit.php?autos_id='. $_GET['autos_id'] );
                 return;
           }   else
                 $_SESSION['error'] = 'Mileage and year must be numeric';
                 header( 'Location: edit.php?autos_id='. $_GET['autos_id'] );
                 return;

}
$_SESSION['error'] = 'All values are required';
header( 'Location: edit.php?autos_id='. $_GET['autos_id'] );
return;
}




// Guardian: Make sure that autos_id is present
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = 'Missing autos_id';
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch();
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$mk = htmlentities($row['make']);
$md = htmlentities($row['model']);
$yr = htmlentities($row['year']);
$ml = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];
?>
<p>Edit User</p>
<form method="post">
<p>Make:
<input type="text" name="make" value="<?= $mk ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $md ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $yr ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $ml ?>"></p>
<input type="hidden" name="autos_id" value="<?= $autos_id ?>">
<p><input type="submit" value="Save"/>
<a href="index.php">Cancel</a></p>
</form>
