<?php
/**
 * Тренировка одна из
*
*class Player
*{
*  public $name;
*  public $adress = "Lipechkaya";
*  function __construct($name)
*  {
*    $this->name = $name;
*    if ($this->name == "Igor") {
*      echo $this->adress;
*    }
*  }
*}
*$obj = new Player('Igo');
*/
echo "<pre \n>";
$pdo=new PDO('mysql:host=localhost;port=3306;dbname=roster','root','i77257725I');
$stmt = $pdo->query("SELECT * FROM User");
while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
  print_r ($row);
}
echo "</pre \n" ;?>
