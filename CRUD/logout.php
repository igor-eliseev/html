<?php // Do not put any HTML above this line
session_start();
session_destroy();
header('Location: index.php');
?>
