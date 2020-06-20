<?php
session_start();
require_once "pdo.php";
require_once "bootstrap.php";
if ( !isset($_SESSION["user_id"] ) ) {
    // Stops emplementing
    die('ACCESS DENIED');
    return;
}
// If the user requested cansel go back to index.php
if ( isset($_POST['cansel']) ) {
    header('Location: index.php');
    return;
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
            //Adding in the database first part
                $stmt = $pdo->prepare('INSERT INTO Profile
                (user_id, first_name, last_name, email, headline, summary)
                VALUES ( :uid, :fn, :ln, :em, :he, :su)');
                $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'])
              );
if ($rez === 'true_Posit') {
    //Adding in the database second part
      $profile_id = $pdo->lastInsertId();
      for ($i=1; $i<=9 ; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

  $stmt = $pdo->prepare('INSERT INTO Position (profile_id, ranking, year, description)
  VALUES ( :pid, :ranking, :year, :desc)');

  $stmt->execute(array(
    ':pid' => $profile_id,
    ':ranking' => $i,
    ':year' => $year,
    ':desc' => $desc)
  );
}
$_SESSION['success'] = "Profile and Position added";
header('Location: index.php');
return;
}
                $_SESSION['success'] = "Profile added";
                header('Location: index.php');
                return;
          } else {
              $_SESSION['error'] = "Email must have an at-sign(@)";
              header('Location: add.php');
              return;
                  }
          } else {
            $_SESSION['error'] = $rez;
            header('Location: add.php');
            return;
                  }
                                                                          }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <title>17df6762</title>
    </head>
    <body>
<div class="container">
    <h1>Adding Profile for <?php echo($_SESSION["name"]) ?>  </h1>
    <p> <?php if ( isset($_SESSION['error']) ) {
        echo('<p style="color: red;">'.($_SESSION['error'])."</p>");
        unset($_SESSION['error']);
      }
    ?>
    </p>
    <br>
    <!--Forms-->
  <form method="post">

    <div class="input-group mb-3">
        <div class="input-group-prepend">
    <span class="input-group-text" id="inputGroup-sizing-default">First name:</span>
        </div>
    <input type="text" name="first_name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
    </div>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
    <span class="input-group-text" id="inputGroup-sizing-default">Last name:</span>
        </div>
        <input type="text" name="last_name" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
    </div>

    <div class="input-group mb-3">
      <div class="input-group-prepend">
    <span class="input-group-text" id="inputGroup-sizing-default">Email:</span>
      </div>
        <input type="text" name="email" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
    </div>

    <div class="input-group mb-3">
      <div class="input-group-prepend">
    <span class="input-group-text" id="inputGroup-sizing-default">Headline:</span>
      </div>
    <input type="text" name="headline" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
    </div>

    <p>Summary:</p>
    <p><textarea rows="5" cols="100" name="summary"></textarea></p>
    <p>Position: <input type="submit" id="addPos" value="+">
      <div id="position_fields">
      </div>
    </p>
    <p><input type="submit" value="Add"/>
    <input type="submit" name="cansel" value="Cansel"/></p>
</form>
</div>
<script>
countPos = 0;
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
</div>
    </body>
