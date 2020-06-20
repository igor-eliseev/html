<!DOCTYPE html>
<html>
<head>
<title>Угадай число</title>
</head>
<body>
<h1>Добро пожаловать в мою игру</h1>

<?php
session_start();
$outcome_game = 'false';
if ( ! isset($_POST['guess'])) {
$outcome_game = 'Параметр не установлен';
} else if ( strlen($_POST['guess']) < 1) {
  $outcome_game = 'Your guess is too short';
  $_SESSION['gues']='';
    header("Location: index.php");
} else if ( ! is_numeric($_POST['guess'])) {
$outcome_game = 'Your guess is not a number';
$_SESSION['gues']='';
  header("Location: index.php");
} else if ( $_POST['guess'] < 47) {
  $outcome_game = 'Your guess is too low';
  $_SESSION['gues']='';
    header("Location: index.php");
} else if ( $_POST['guess'] > 47) {
  $outcome_game = 'Your guess is too high';
  $_SESSION['gues']='';
    header("Location: index.php");
} else if ( $_POST['guess'] == 47 ) {
  $_SESSION['gues'] = 'Congratulations - You are right';
  $_SESSION['oldguess'] = $_POST['guess'];
  $_SESSION['outcome_game'] = '';
  header("Location: index.php");
  return;
}
if ( isset($_POST['guess'])) {
echo $_SESSION['outcome_game'],'<br>';
$_SESSION['oldguess'] = $_POST['guess'];
$_SESSION['outcome_game'] = $outcome_game;
} else if ( ! isset($_SESSION['gues'])) {
$_SESSION['oldguess'] = 0;
$_SESSION['outcome_game'] = 0;
$_SESSION['gues'] = 0;
}

If ($_SESSION['gues'] !== 0) {echo $_SESSION['gues'],'<br>';}
If ($_SESSION['outcome_game'] !==0) {echo $_SESSION['outcome_game'];}
?>

<form method="post">
  <p><label for="guess">Введи значение</label>
  <input type="text" name="guess" id= "guess"
   size="40" value="<?= htmlentities($_SESSION['oldguess']) ?>"></P>
  <input type="submit">
</form>
</body>
</html>
