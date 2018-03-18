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

  function editCategoryName($category_type){

    $user_id = $_SESSION['user_id'];

    if(isset($_POST['category_id'])){
      $category_id = $_POST['category_id'];
    } else $category_id = 0;
    if(isset($_POST['newCategoryName'])){
      $newName = $_POST['newCategoryName'];
    } else $newName = '';

    if($category_id > 0 && $newName != ''){
      switch ($category_type) {
        case 'expenses':
          if($this->editExpensesCategoryName($user_id, $category_id, $newName))
            return ACTION_OK;
          break;
        case 'incomes':
          if($this->editIncomesCategoryName($user_id, $category_id, $newName))
            return ACTION_OK;
          break;
        case 'payment':
          if($this->editPaymentName($user_id, $category_id, $newName))
            return ACTION_OK;
          break;
      }
    } else return ACTION_FAILED;

  }

  function editExpensesCategoryName($user_id, $category_id, $newName)
  {
    $query = "SELECT * FROM expenses_category WHERE user_id = '$user_id'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows <> 0){  
      $query = "UPDATE expenses_category SET `name` = '$newName' WHERE `user_id` = '$user_id' AND `id` = '$category_id'";

        if(!$result = $this->dbo->query($query)){
          //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
          return SERVER_ERROR;
        }
      return ACTION_OK;
    }
  }

  function editIncomesCategoryName($user_id, $category_id, $newName)
  {
    $query = "SELECT * FROM incomes_category WHERE user_id = '$user_id'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows <> 0){  
      $query = "UPDATE incomes_category SET `name` = '$newName' WHERE `user_id` = '$user_id' AND `id` = '$category_id'";

        if(!$result = $this->dbo->query($query)){
          //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
          return SERVER_ERROR;
        }
      return ACTION_OK;
    }
  }

   function editPaymentName($user_id, $category_id, $newName)
  {
    $query = "SELECT * FROM payment_methods WHERE user_id = '$user_id'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows <> 0){  
      $query = "UPDATE payment_methods SET `payname` = '$newName' WHERE `user_id` = '$user_id' AND `id` = '$category_id'";

        if(!$result = $this->dbo->query($query)){
          //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
          return SERVER_ERROR;
        }
      return ACTION_OK;
    }
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

    switch($this->verifyDate($expdate)){
      case TO_LOW_DATE:
        $control_test = false;
        return TO_LOW_DATE;
        break;
      case TO_HIGH_DATE:
        $control_test = false;
        return TO_HIGH_DATE;
        break;
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

  function addIncome()
  {
    $user_id =  $_SESSION['user_id'];

    $incamount = $_POST["incamount"];
    $incdate = $_POST["incdate"];
    $inccategory = $_POST["inccategory"];
    $inccomment = $_POST["inccomment"];

    $control_test = true; //if it will be positive at the end, record could be added to DB

    if ((!isset($incamount))
      ||(!isset($incdate))
      ||(!isset($inccategory))){
      $control_test = false;
      return FORM_DATA_MISSING;
    }

    if(!is_numeric($incamount)){
      $control_test = false;
      return AMOUNT_NOT_NUMERIC;
    }

    switch($this->verifyDate($incdate)){
      case TO_LOW_DATE:
        $control_test = false;
        return TO_LOW_DATE;
        break;
      case TO_HIGH_DATE:
        $control_test = false;
        return TO_HIGH_DATE;
        break;
    }

    if(strlen($inccomment)==0) $inccomment = '';

    if($control_test==false) return ACTION_FAILED;

    $query = "INSERT INTO Incomes VALUES "
           . "(NULL, '$inccategory', '$user_id', '$incamount', '$incdate', '$inccomment')";

    if(!$this->dbo->query($query)){
        //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
        return SERVER_ERROR;
      } else {
        unset($_POST["inccategory"]);
        unset($_POST["incamount"]);
        unset($_POST["incdate"]);
        unset($_POST["inccategory"]);
        return ACTION_OK;
      }
  }

  function getToday()
  {
    $date = getdate();
    $d = $date['mday'];
    $m = $date['mon'];
    $y = $date['year'];
    if($d<10) $d = '0'.$d;
    if($m<10) $m = '0'.$m;
    $today = $y.'-'.$m.'-'.$d;

    return $today;
  }

  function verifyDate($dateToVerify){

    $today = $this->getToday();
    $year = substr($today, 0, 4);
    $month = substr($today, 5, 2);

    $min_date = '2000-01-01';
    $current_month_last_day = date("t", strtotime($today));
    $max_date = $year.'-'.$month.'-'.$current_month_last_day;
    
    if($dateToVerify < $min_date){
      return TO_LOW_DATE;
    }
    if($dateToVerify > $max_date){
      return TO_HIGH_DATE;
    }

    return ACTION_OK;
  }

  function showIncomesCategoriesAsRadio($user_id){
    try{
      if(!$this->dbo) {
        return SERVER_ERROR;
      }  
      else {
        $this->dbo->query("SET CHARSET utf8");
        $this->dbo->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`"); 

        $query = "SELECT * FROM incomes_category WHERE user_id = '$user_id' ORDER BY `order` ASC";

        if(!$result = $this->dbo->query($query)){
          //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
          return SERVER_ERROR;
        }
        if($result->num_rows > 0){
          while ($row = $result->fetch_row()){
            echo '<label  class="radio">';
            echo '<input  type="radio" name="inccategory" value="'.$row[0].'"/>';
            echo $row[3];
            echo '</label>';
          }
        } 
        else {
          //echo 'Wystąpił błąd: brak kategorii dla danego Użytkownika';
          return SERVER_ERROR;
        }   
      }
    } catch (Exception $e){
      //echo 'Błąd: ' . $e->getMessage();
      exit('Aplikacja chwilowo niedostępna'); 
      }                            
  }

  function showExpensesCategoriesAsRadio($user_id){
    try{
      if(!$this->dbo) {
        return SERVER_ERROR;
      }
      else {
        $this->dbo->query("SET CHARSET utf8");
        $this->dbo->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`"); 
        
        $query = "SELECT * FROM expenses_category WHERE user_id = '$user_id' ORDER BY `order` ASC";

        if(!$result = $this->dbo->query($query)){
          //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
          return SERVER_ERROR;
        }
        if($result->num_rows > 0){
          while ($row = $result->fetch_row()){
            echo '<label  class="radio">';
            echo '<input  type="radio" name="expcategory" value="'.$row[0].'"/>';
            echo $row[3];
            echo '</label>';
          }
        } 
        else {
          //echo 'Wystąpił błąd: brak kategorii dla danego Użytkownika';
          return SERVER_ERROR;
        }   
      }
    } catch (Exception $e){
      //echo 'Błąd: ' . $e->getMessage();
      exit('Aplikacja chwilowo niedostępna'); 
    }
  }

  function showPaymentsAsRadio($user_id){
    try{
      if(!$this->dbo) {
        return SERVER_ERROR;
      }
      else {
        $this->dbo->query("SET CHARSET utf8");
        $this->dbo->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`"); 

        $query = "SELECT * FROM payment_methods WHERE user_id = '$user_id'  ORDER BY `order` ASC";

        if(!$result = $this->dbo->query($query)){
          //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
          return SERVER_ERROR;
        }
        if($result->num_rows > 0){
          while ($row = $result->fetch_row()){
            echo '<label  class="radio">';
            echo '<input  type="radio" name="payment" value="'.$row[0].'"/>';
            echo $row[3];
            echo '</label>';
          }
        } 
        else {
          //echo 'Wystąpił błąd: brak kategorii dla danego Użytkownika';
          return SERVER_ERROR;
        }   
      }
    } catch (Exception $e){
      //echo 'Błąd: ' . $e->getMessage();
      exit('Aplikacja chwilowo niedostępna'); 
    }                             
  }

}
?>