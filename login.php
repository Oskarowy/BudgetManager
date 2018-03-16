<?php
  if(!isset($manager)) die();

  if(!$manager->logged){
    switch ($manager->login()){
      case LOGIN_OK:
        header("Location:index.php?action=showMain");
        exit();
      case LOGIN_FAILED:
        $manager->setMessage("Nieprawidłowa nazwa lub hasło!");
        break;
      case SERVER_ERROR:
      default:
        $manager->setMessage("Błąd serwera!");
    }
  }
  else{
    $manager->setMessage("Najpierw musisz się wylogować!");
  }
  header("Location:index.php?action=showLoginForm");
?>