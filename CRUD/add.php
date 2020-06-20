<?php
require_once "pdo.php";
require_once "bootstrap.php";
session_start();
if ( !isset($_SESSION["who"] ) ) {
    // Redirect the browser to autos.php
    die('ACCESS DENIED');
    return;
}
// If the user requested cansel go back to index.php
if ( isset($_POST['cansel']) ) {
    header('Location: index.php');
    return;
}
    // Data validation
    if ( isset($_POST['make']) || isset($_POST['model']) || isset($_POST['year'])
         || isset($_POST['mileage'])) {
           if (( strlen($_POST["make"]) > 1) && ( strlen($_POST["model"]) > 1) && ( strlen($_POST["year"]) > 1)
           && ( strlen($_POST["mileage"]) > 1)) {

    // Checking if the mileage and year are integers.
        if ( is_numeric($_POST['mileage']) === TRUE && is_numeric($_POST['year']) === TRUE ) {
          //Checking if the make is empty
            if ( strlen($_POST["make"]) > 1  ) {
                $stmt = $pdo->prepare('INSERT INTO autos
                   (make, model, year, mileage) VALUES ( :mk, :md, :yr, :mi)');
                $stmt->execute(array(
                   ':mk' => $_POST['make'],
                   ':md' => $_POST['model'],
                   ':yr' => $_POST['year'],
                   ':mi' => $_POST['mileage'])
               );
              $_SESSION['success'] = "Record added";
              header('Location: index.php');
              return;
          } else
              $_SESSION['error'] = "Make is required";
              header('Location: add.php');
              return;
        }   else
              $_SESSION['error'] = "Mileage and year must be numeric";
              header('Location: add.php');
              return;
    }
     $_SESSION['error'] = "All values are required";
     header('Location: add.php');
     return;
  }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <title>c45ae64f</title>
    </head>
    <body style="position:relative;left:15px">
    <h1>Tracking Autos for <?php echo($_SESSION["who"]) ?>  </h1>
    <p> <?php if ( isset($_SESSION['error']) ) {
        echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
        unset($_SESSION['error']);
    }
    ?>
    </p>
    <br>
    <!--Forms-->
    <form method="post">
    <p>Make:
    <input type="text" name="make" size="40"></p>
    <p>Model:
    <input type="text" name="model" size="40"></p>
    <p>Year:
    <input type="text" name="year"></p>
    <p>Mileage:
    <input type="text" name="mileage"></p>
    <p><input type="submit" value="Add"/>
    <input type="submit" name="cansel" value="Cansel"/></p>
    </form>
    </body>
