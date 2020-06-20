<html>
<head>
<title>6dfc35c4</title>
</head>
<body>
<h1>Welcome to my guessing game</h1>
<p>
<?php
session_start();
$guess = NULL;
  if ( ! isset($_POST['guess']) && ! isset($guess) ) {
    echo("Missing guess parameter");
    echo $_POST['guess'];
    echo $guess;
  } else if ( strlen($_POST['guess']) < 1 ) {
    echo("Your guess is too short");
    echo $_POST['guess'];
    echo $guess;
  } else if ( ! is_numeric($_POST['guess']) ) {
    echo("Your guess is not a number");
  } else if ( $_POST['guess'] < 47 ) {
    echo("Your guess is too low");
  } else if ( $_POST['guess'] > 47 ) {
    echo("Your guess is too high");
  } else {
    $_SESSION['guess'] = 'Congratulations - You are right';
    $guess = $_SESSION['guess'];
    echo $guess;
    header('Location: index.php');
  }
?>
</p>
<?php
$oldguess = isset($_POST['guess']) ? $_POST['guess'] : '';
?>
<P>Угадай игра..</p>
<form method="post">
  <p><label for="guess">Введи значение</label>
  <input type="text" name="guess" id= "guess"
   size="40" value="<?= htmlentities($oldguess) ?>"></P>
  <input type="submit">
</form>
</body>
</html>
