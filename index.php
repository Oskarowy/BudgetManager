<?php
include 'constants.php';
spl_autoload_register('classLoader');
session_start();

try{


} catch (Exception $e){
  	//echo 'Błąd: ' . $e->getMessage();
  	exit('Portal chwilowo niedostępny');	
}


function classLoader($className){
  if(file_exists("classes/$className.php")){
    require_once("classes/$className.php");
  } else {
    throw new Exception("Brak pliku z definicją klasy.");
  }
}
?>