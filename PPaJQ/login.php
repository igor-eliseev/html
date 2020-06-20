<?php
session_start();
require_once 'pdo.php';
require_once 'bootstrap.php';
$salt = 'XyZzy12*_';
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}
if ( !isset($_SESSION['error']) ) {
$_SESSION['error'] = false;  // If we have no POST data
}

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
}
      elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Email must have an at-sign(@)';
        header('Location: login.php');
        return;
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users
        WHERE email = :em AND password = :pw');
        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch();

      if ( $row !== false ) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        // Redirect the browser to index.php
        header('Location: index.php');
        return;
        } else {
            $_SESSION['error'] = 'Incorrect password';
            error_log('Login fail '.$_POST['email'].' $check');
            header('Location: login.php');
            return;
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<title>17df6762</title>

<script type='text/javascript'>
function doValidate() {
console.log('Validating...');
try {
mail = document.getElementById('id_1722').value;
pw = document.getElementById('id_1723').value;
console.log("Validating pw="+pw);
if (pw == null || pw == "" || mail == null || mail == "") {
alert("Both fields must be filled out");
return false;
} else {
console.log("Validating mail="+mail);
var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
  if(mail.match(mailformat))  {
console.log("Validated mail="+mail);
  return true;
} else {
alert("Invalid email address");
return false;
}
}
} catch(e) {
return false;
} }
</script>

</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
if ( $_SESSION['error'] !== false ) {
    echo('<p style="color: red;">'.$_SESSION['error']."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="post">

  <div class="input-group mb-3">
  <div class="input-group-prepend">
  <span class="input-group-text" id="inputGroup-sizing-default">Email:</span>
  </div>
  <input type="text" name="email" id="id_1722" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
  </div>

  <div class="input-group mb-3">
  <div class="input-group-prepend">
  <span class="input-group-text" id="inputGroup-sizing-default">Password:</span>
  </div>
  <input type="password" name="pass" id="id_1723" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
  </div>

<input type="submit" onclick="doValidate()" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
</div>
</body>
