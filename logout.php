<?php
  if(!isset($manager)) die();

  if($manager->logged){
    $manager->logout();
  }
  header("Location: index.php");
?>