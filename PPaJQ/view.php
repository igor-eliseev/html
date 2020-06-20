<?php
require_once "pdo.php";
require_once "bootstrap.php";
$failure = false;
$done = false;
$addnew = "";
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to logout.php
    header("Location: logout.php");
    return;
    }
if ( isset($_SESSION['success']) ) {
  //sucsess message
        echo('<p style="color: green;">'.$_SESSION['success']."</p>");
      //  echo('<p style="color: yellow;">'.$_SESSION['$q']."</p>");
        unset($_SESSION['success']);
    }
    if ( isset($_SESSION['error']) ) {
      //error message
            echo('<p style="color: red;">'.$_SESSION['error']."</p>");
            unset($_SESSION['error']);
        }
    //preparing for Profile GET
    if ( isset($_GET['profile_id']) ) {
    $pr = $_GET['profile_id'];
    $stmt2 = $pdo->query("SELECT *
    FROM Profile
    WHERE Profile.profile_id = $pr");
    $rows2 = $stmt2->fetchAll();


  $stmt3 = $pdo->query("SELECT year, description
  FROM Profile
  LEFT OUTER JOIN Position
  ON Profile.profile_id = Position.profile_id
  WHERE Profile.profile_id = $pr
  ORDER BY Position.ranking ASC");
  $rows3 = $stmt3->fetchAll();
}

    //preparing for index.php
      $stmt = $pdo->query("SELECT *
      FROM users
      INNER JOIN Profile
      ON users.user_id = Profile.user_id
      ORDER BY Profile.last_name ASC");
      $rows = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>17df6762</title>
</head>
<body>
<div class="container">
<p style="color:red"><?php echo "$failure"; ?></p>
<table class = "table table-bordered table-hover table-condensed">
<?php
//If there is GET
if ( isset($_GET['profile_id']) ) {
  echo'<h1>Profile information</h1>';
      foreach ( $rows2 as $row ) {
          echo("<p>First Name:");
          echo(htmlentities($row['first_name']));
          echo("</p><p>Last Name: ");
          echo(htmlentities($row['last_name']));
          echo("</p><p>Email:");
          echo(htmlentities($row['email']));
          echo("</p><p>Headline:<br/>");
          echo(htmlentities($row['headline']));
          echo("</p><p>Summary:<br/>");
          echo(htmlentities($row['summary']));
          echo("</p>");
        }
        if (($rows3[0]['year'])!==NULL) {
          echo("<p>Positions:<br/>");
          echo("</p>");
          foreach ( $rows3 as $row ) {
          echo('<ul><li>'.htmlentities($row['year']));
          echo('</li><li>'.htmlentities($row['description']).'</li></ul>');
          }
        }
      echo("<a href='index.php'>Done</a>");
return;
}

//If user is loged in
if ( isset($_SESSION['user_id']) ) {
  echo "<tr><th>Name</th><th>Headline</th><th>Action</th></tr>";
      foreach ( $rows as $row ) {
          echo '<tr><td>';
          echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name'])."&ensp;"
          .htmlentities($row['last_name']).'</a>');
          echo('</td><td>');
          echo(htmlentities($row['headline']));
          echo('</td><td>');
          echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a>');
          echo('&ensp;'.'<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
          echo('</td></tr>');
}
          $addnew = '<p><a href="add.php">Add New Entry</a></p>';
} else {
  //If user isn't loged in
    echo '<tr><th>Name</th><th>Headline</th></tr>';
      foreach ( $rows as $row ) {
          echo '<tr><td>';
          echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name'])."&ensp;"
          .htmlentities($row['last_name']).'</a>');
          echo('</td><td>');
          echo(htmlentities($row['headline']));
          echo('</td></tr>');
      }
}
?>
</table>
<?php echo $addnew;?>
</div>
</body>
