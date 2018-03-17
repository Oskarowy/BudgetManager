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
    
    $query = "SELECT `user_id`, `username`, `password`, `email` "
           . "FROM Users WHERE `username`='$username' OR `email`='$username'";

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
        $_SESSION['user_id'] = $loggedUserId;
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

  function setDefaultCategories()
  {
    $this->setDefaultExpensesCategories();
    $this->setDefaultIncomesCategories();
    $this->setDefaultPaymentCategories();
  }

  function setDefaultExpensesCategories()
  {
    $user_id =  $_SESSION['user_id'];

    $query = "SELECT * FROM expenses_category WHERE user_id = '$user_id'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows == 0){  
      
      $query = "INSERT INTO expenses_category SELECT def.id, '$user_id', def.order, def.name FROM default_expenses_category AS def";

      if(!$result = $this->dbo->query($query)){
        //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
        return SERVER_ERROR;
      } 
    } else return ACTION_OK;
  }

  function setDefaultIncomesCategories()
  {
    $user_id =  $_SESSION['user_id'];

    $query = "SELECT * FROM incomes_category WHERE user_id = '$user_id'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows == 0){  
      
      $query = "INSERT INTO incomes_category SELECT def.id, '$user_id', def.order, def.name FROM default_incomes_category AS def";

      if(!$result = $this->dbo->query($query)){
        //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
        return SERVER_ERROR;
      } 
    } else return ACTION_OK;
  }

  function setDefaultPaymentCategories()
  {
    $user_id =  $_SESSION['user_id'];

    $query = "SELECT * FROM payment_methods WHERE user_id = '$user_id'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows == 0){  
      
      $query = "INSERT INTO payment_methods SELECT def.id, '$user_id', def.order, def.name FROM default_payment_methods AS def";

      if(!$result = $this->dbo->query($query)){
        //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
        return SERVER_ERROR;
      } 
    } else return ACTION_OK;
  }

  function addExpense()
  {
    $user_id =  $_SESSION['user_id'];

    $expamount = $_POST["expamount"];
    $expdate = $_POST["expdate"];
    $payment = $_POST["payment"];
    $expcategory = $_POST["expcategory"];
    $expcomment = $_POST["expcomment"];

    $control_test = true; //if it will be positive at the end, record could be added to DB

    if ((!isset($payment))
      ||(!isset($expamount))
      ||(!isset($expdate))
      ||(!isset($expcategory))){
      $control_test = false;
      return FORM_DATA_MISSING;
    }

    if(!is_numeric($expamount)){
      $control_test = false;
      return AMOUNT_NOT_NUMERIC;
    }

    $date = getdate();
    $d = $date['mday'];
    $m = $date['mon'];
    $y = $date['year'];
    if($d<10) $d = '0'.$d;
    if($m<10) $m = '0'.$m;
    $today = $y.'-'.$m.'-'.$d;

    $min_date = '2000-01-01';
    $current_month_last_day = date("t", strtotime($today));
    $max_date = $y.'-'.$m.'-'.$current_month_last_day;
    
    //Check if date is correct
    if($expdate < $min_date){
      $control_test = false;
      return TO_LOW_DATE;
    }
    if($expdate > $max_date){
      $control_test = false;
      return TO_HIGH_DATE;
    }

    if(strlen($expcomment)==0) $expcomment = '';

    if($control_test==false) return ACTION_FAILED;

    $query = "INSERT INTO Expenses VALUES "
           . "(NULL, '$expcategory', '$payment', '$user_id', '$expamount', '$expdate', '$expcomment' )";

    if(!$this->dbo->query($query)){
        //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
        return SERVER_ERROR;
      } else {
        unset($_POST["expamount"]);
        unset($_POST["expdate"]);
        unset($_POST["payment"]);
        unset($_POST["expcategory"]);
        unset($_POST["expcomment"]);
        return ACTION_OK;
      }
  }

}
?>