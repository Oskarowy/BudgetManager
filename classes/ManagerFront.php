<?php
class ManagerFront extends Manager
{
  public $logged = null;
  
  function __construct($host, $user, $pass, $db)
  {
    $this->dbo = $this->initDB($host, $user, $pass, $db);
    $this->logged = $this->getActualUser();
  }
  
  function getActualUser()
  {
    if(isset($_SESSION['logged'])){
      return $_SESSION['logged'];
    }
    else{
      return null;
    }
  }
  
  function setMessage($message)
  {
    $_SESSION['message'] = $message;
  }
  
  function getMessage()
  {
    if(isset($_SESSION['message'])){
      $message = $_SESSION['message'];
      unset($_SESSION['message']);
      return $message;
    }
    else {
      return null;
    }
  }

  function login()
  {
    if(!$this->dbo) return SERVER_ERROR;
    
    if($this->logged){
      return NO_LOGIN_REQUIRED;
    }
    
    if(!isset($_POST["username"]) || !isset($_POST["password"])){
      return FORM_DATA_MISSING;
    }
    
    $username = $_POST["username"];
    $pass = $_POST["password"];
    
    $usernameLength = mb_strlen($username, 'utf8');
    $userPassLength = mb_strlen($pass, 'utf8');
    
    if($usernameLength < 3 || $usernameLength > 25 ||
      $userPassLength < 3 || $userPassLength > 40){
      return ACTION_FAILED;
    }
  
    $username = $this->dbo->real_escape_string($username);
    $pass = $this->dbo->real_escape_string($pass);
    
    $query = "SELECT `user_id`, `uname`, `upass`, `email` "
           . "FROM Users WHERE `uname`='$username'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows <> 1){
      return ACTION_FAILED;
    }
    else{
      $row = $result->fetch_row();
      $pass_db = $row[2];

      if(crypt($pass, $pass_db) != $pass_db){
        return ACTION_FAILED;
      }
      else{
        $loggedUsername = $row[1];
        $loggedUserId = $row[0];
        $_SESSION['logged'] = new User($loggedUserId, $loggedUsername);
        return ACTION_OK;
      }
    }
  }
  
  function logout()
  {
    $this->logged = null;
    if(isset($_SESSION['logged'])){
      unset($_SESSION['logged']);
    }
  }
  
  function showRegistrationForm()
  {
    $reg = new Registration($this->dbo);
      return $reg->showRegistrationForm();
  }
  
  function registerUser()
  {
    $reg = new Registration($this->dbo);
      return $reg->registerUser();
  }

}
?>