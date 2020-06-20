<?php
session_start();
require_once "pdo.php";
require_once "bootstrap.php";
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
  // If profile_id is exist and user is owner
  if ( isset($_GET['profile_id']) ) {
    $p_id = $_GET['profile_id'];
    $u_id = $_SESSION["user_id"];
    $stmt = $pdo->query("SELECT * FROM Profile WHERE profile_id = $p_id
      AND user_id = $u_id");
      $rows = $stmt->fetchAll();
    if ( empty($rows) === FALSE ) {

    //preparing for Profile GET first part
    if ( isset($_GET['profile_id']) ) {
  $stmt3 = $pdo->query("SELECT first_name,last_name,email,headline,summary
  FROM Profile
  LEFT OUTER JOIN Position
  ON Profile.profile_id = Position.profile_id
  WHERE Profile.profile_id = $p_id");
  $rows3 = $stmt3->fetchAll();
  //preparing for Profile GET second part
  $stmt4 = $pdo->query("SELECT year, description, ranking, position_id
  FROM Profile
  INNER JOIN Position
  ON Profile.profile_id = Position.profile_id
  WHERE Profile.profile_id = $p_id
  ORDER BY Position.ranking ASC");
  $rows4 = $stmt4->fetchAll();
  $rows4_nb = count($rows4);
  $_SESSION['$rows4_nb'] = $rows4_nb;
}

    //preparing for output in form
    foreach ($rows3 as $row) {
    $first_name = htmlentities($row['first_name']);
    $last_name = htmlentities($row['last_name']);
    $email = htmlentities($row['email']);
    $headline = htmlentities($row['headline']);
    $summary = htmlentities($row['summary']);
  }



  // Data validation. Function
              function validatePos() {
                if (( strlen($_POST["first_name"]) > 1) && ( strlen($_POST["last_name"]) > 1)
                  && ( strlen($_POST["headline"]) > 1)
                  && ( strlen($_POST["summary"]) > 1)
                ){
                for($i=1; $i<=9; $i++) {
                  if ( ! isset($_POST['year'.$i]) ) continue;
                  if ( ! isset($_POST['desc'.$i]) ) continue;

                  $year = $_POST['year'.$i];
                  $desc = $_POST['desc'.$i];
// Data validation. If there isn't an empty line second part
                  if ( strlen($year) == 0 || strlen($desc) == 0 ) {
                    return 'All values are required';
                  }

                  if ( ! is_numeric($year) ) {
                    return 'Position year must be numeric';
                }
                return 'true_Posit';
              }
              return 'true_No_Posit';
             } else {
            return 'All values are required';
             }
                                    }
// Data validation. If there is the compleate POST first part
  if ( isset($_POST['first_name']) || isset($_POST['last_name']) || isset($_POST['email'])
       || isset($_POST['headline']) || isset($_POST['summary']))        {
          // Data validation. If there isn't empty lines
          $rez = validatePos();
          if (($rez === 'true_Posit') || ($rez === 'true_No_Posit')) {
  // Checking if Email is valid.
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            //Preparing the database
                $sql = "UPDATE Profile SET first_name = :fn,
                    last_name = :ln, email = :em, headline = :he, summary = :su
                    WHERE profile_id = :pr";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'],
                ':pr' => $_POST['profile_id']));

                if ($rez === 'true_Posit') {
                //Updating and deleting in the database second part
        //        if ( ! isset($_POST['year'.$i]) ) {
                    $q = 1;
                  for ($i=1; $i<=$rows4_nb ; $i++) {
                    if ( ! isset($_POST['year'.$i]) ) {
                      $sql = "DELETE FROM Position WHERE profile_id = :pid
                      AND ranking = :ranking";
                      $stmt = $pdo->prepare($sql);
                      $stmt->execute(array(
                        ':pid' => $p_id,
                        ':ranking' => $i)
                    );
                    continue;
                  };
                    $year = $_POST['year'.$i];
                    $desc = $_POST['desc'.$i];
                    $pos_id = $_POST['position_id'.$i];
                    $sql = "UPDATE Position SET year = :year,
                        description = :desc, ranking = :ranking_f
                        WHERE Position.position_id = :position_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(array(
                      ':year' => $year,
                      ':desc' => $desc,
                      ':ranking_f' => $q,
                      ':position_id' => $pos_id)
                      );
                      $q = $q + 1;
            }
            //Adding new in the database second part
              for ($i=$rows4_nb+1; $i<=9 ; $i++) {
                if ( ! isset($_POST['year'.$i]) ) continue;
                if ( ! isset($_POST['desc'.$i]) ) continue;
                $year = $_POST['year'.$i];
                $desc = $_POST['desc'.$i];

          $stmt = $pdo->prepare('INSERT INTO Position (profile_id, ranking, year, description)
          VALUES ( :pid, :ranking, :year, :desc)');
          $stmt->execute(array(
            ':pid' => $p_id,
            ':ranking' => $q,
            ':year' => $year,
            ':desc' => $desc)
          );
        }
              //  }
                $_SESSION['success'] = "Profile and Position updated";
                header('Location: index.php');
                return;
                }
                $_SESSION['success'] = "Profile updated";
                header('Location: index.php');
                return;
          } else {
              $_SESSION['error'] = "Email must have an at-sign(@)";
              header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
              return;
}
    } else {
     $_SESSION['error'] = "All values are required";
     header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
     return;
   }
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
<h1>Editing Profile for <?php echo($_SESSION['name']) ?>  </h1>
<p> <?php if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.($_SESSION['error'])."</p>");
    unset($_SESSION['error']);}
?>
</p>
<br>
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

<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="inputGroup-sizing-default">Email:</span>
</div>
<input type="text" value="<?= $email ?>" name="email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
</div>

<div class="input-group mb-3">
<div class="input-group-prepend">
<span class="input-group-text" id="inputGroup-sizing-default">Headline:</span>
</div>
<input type="text" value="<?= $headline ?>" name="headline" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
</div>

<p>Summary:
<p><textarea rows="5" cols="100" name="summary"><?= $summary ?></textarea></p>
<input type="hidden" name="profile_id" value="<?= $p_id ?>">

<p>Position: <input type="submit" id="addPos" value="+">
<?php
if ($rows4 !== Array()) {
  $i = 1;
  foreach ( $rows4 as $row ) {
  echo('<div id="position'.$i.'">'."\n");
  echo('<p>Year: <input type="text" name="year'.$i.'"value="'.htmlentities($row['year']).'"/>'."\n");
  echo('<input type="button" value="-" onclick="$(\'#position'.$i.'\').remove();return false;">'."\n");
  echo('</p><textarea name="desc'.$i.'" rows="8" cols="80">'."\n".htmlentities($row['description'])."</textarea>\n");
  echo('<p><input type="hidden" name="position_id'.$i.'"value="'.$row['position_id'].'"/>'."\n");
  echo "</div>";
  $i++;
  }
}
?>
<div id="position_fields">
</div>
<p><input type="submit" value="Save"/>
<input type="submit" name="cansel" value="Cansel"/></p>
</form>
</div>
<script>
<?php
echo ('countPos = '.$rows4_nb.';');
 ?>
$(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value=""> \
            <input type="button" value="-" \
                onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });
});
</script>
<?php echo $_SESSION['$rows4_nb'] ;?>

</body>
