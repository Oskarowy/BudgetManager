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
        $userId = $row[0];
        $_SESSION['user_id'] = $userId;
        $username = $row[1];
        $userMail = $row[3];
        $_SESSION['logged'] = new User($userId, $username, $userMail);    
        return ACTION_OK;
      }
    }
  }
  
  function logout()
  {
    $this->logged = null;
    if(isset($_SESSION['logged'])){
      unset($_SESSION['logged']);
      unset($_SESSION['user_id']);
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

  function editUser()
  {
    $user_id =  $_SESSION['user_id'];

    $newEmail = $_POST['editemail'];
    $newName = $_POST['editname'];
    $newPass = $_POST['editpass'];
    $newPass2 = $_POST['editpass2'];

    $query = "SELECT COUNT(*) FROM Users WHERE Email='$newEmail'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows <> 1){
        unset($_POST['editpass']);
        unset($_POST['editpass2']);
        return USER_NAME_ALREADY_EXISTS;
      }
    if(($newPass != "") && ($newPass2 != "")){
      if($newPass != $newPass2){
        unset($_POST['editpass']);
        unset($_POST['editpass2']);
        return PASSWORDS_DO_NOT_MATCH;
      }
      unset($_POST['editpass2']);
      
      $newPass = crypt($newPass);
    }

    if($newName != "")
    { 
        $cond1 = "`username` = '$newName', ";
        if($newPass != "") {
          $cond2 = "`password` = '$newPass', ";
        } else $cond2 = "";
      $cond3 = "`email` = '$newEmail'";

    $totalCondition = $cond1 . $cond2 . $cond3;

    $query = "UPDATE Users SET $totalCondition WHERE user_id = '$user_id'";

      if($this->dbo->query($query)){
        return ACTION_OK;
      }
      else{
        return ACTION_FAILED;
      }
    }
  }

  function addCategory($category_type, $category_name){

    $tableName = $this->getTableName($category_type);

    $tableShort = substr($tableName, 0, 3);

    $user_id =  $_SESSION['user_id'];

    $query = "SELECT * FROM ".$tableName." WHERE user_id = '$user_id'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows <> 0){

      $highestId = "SELECT MAX(".$tableShort.".id) FROM ".$tableName." AS ".$tableShort." WHERE ".$tableShort.".user_id = '$user_id'";
      $highestOrder = "SELECT MAX(".$tableShort.".order) FROM ".$tableName." AS ".$tableShort." WHERE ".$tableShort.".user_id = '$user_id'";

      $query = "INSERT INTO ".$tableName." VALUES ((".$highestId.")+1, '$user_id', (".$highestOrder.")+1, '$category_name')";

        if(!$result = $this->dbo->query($query)){
          //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
          return SERVER_ERROR;
        }
      return ACTION_OK;
    }
  }

  function deleteUser()
  {
    $user_id =  $_SESSION['user_id'];

    $query = "SELECT * FROM users WHERE user_id = '$user_id'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows == 1){

      $this->deleteDataFromAllTablesOfCurrentUser($user_id);
      
      $query = "DELETE FROM Users WHERE user_id = '$user_id'";

      if(!$result = $this->dbo->query($query)){
        //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
        return SERVER_ERROR;
      }
      return ACTION_OK; 
    } else return ACTION_FAILED;
  }

  function deleteDataFromAllTablesOfCurrentUser($user_id)
  {

    $query = "SELECT * FROM users WHERE user_id = '$user_id'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows == 1){
      
      $this->deleteDataFromTable("expenses", $user_id);
      $this->deleteDataFromTable("incomes", $user_id);
      $this->deleteDataFromTable("payment_methods", $user_id);
      $this->deleteDataFromTable("incomes_category", $user_id);
      $this->deleteDataFromTable("expenses_category", $user_id);
     
      return ACTION_OK; 
    } else return ACTION_FAILED;
  }

  function deleteDataFromTable($table_name, $user_id)
  {    
    $query = "DELETE FROM ".$table_name." WHERE user_id = '$user_id'";

      if(!$result = $this->dbo->query($query)){
        //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
        return SERVER_ERROR;
      }
      return ACTION_OK; 
  }

  function setDefaultCategories()
  {
    $this->setDefaultCategoriesFor("income");
    $this->setDefaultCategoriesFor("expense");
    $this->setDefaultCategoriesFor("payment");
  }

  function setDefaultCategoriesFor($category_type)
  {
    $tableName = $this->getTableName($category_type);

    $user_id =  $_SESSION['user_id'];

    $query = "SELECT * FROM ".$tableName." WHERE user_id = '$user_id'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows == 0){  
      
      $query = "INSERT INTO ".$tableName." SELECT def.id, '$user_id', def.order, def.name FROM default_".$tableName." AS def";

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

  function showCategoriesAsRadioButtons($category_type){

    $tableName = $this->getTableName($category_type);

    $user_id =  $_SESSION['user_id'];

    switch($category_type){
      case 'income':
        $name = "inccategory";
        break;
      case 'expense':
        $name = "expcategory";
        break;
      case 'payment':
        $name = "payment";
        break;
    }

    try{
      if(!$this->dbo) {
        return SERVER_ERROR;
      }  
      else {
        $this->dbo->query("SET CHARSET utf8");
        $this->dbo->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`"); 

        $query = "SELECT * FROM ".$tableName." WHERE user_id = '$user_id' ORDER BY `order` ASC";

        if(!$result = $this->dbo->query($query)){
          //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
          return SERVER_ERROR;
        }
        if($result->num_rows > 0){
          while ($row = $result->fetch_row()){
            echo '<label  class="radio">';
            echo '<input  type="radio" name="'.$name.'" value="'.$row[0].'"/>';
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

  function showCategoriesAsList($action_type, $category_type){
    
    $tableName = $this->getTableName($category_type);

    $user_id =  $_SESSION['user_id'];

    try{
      if(!$this->dbo) {
        return SERVER_ERROR;
      }  
      else {
        $this->dbo->query("SET CHARSET utf8");
        $this->dbo->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`"); 

        $query = "SELECT * FROM ".$tableName." WHERE user_id = '$user_id' ORDER BY `order` ASC";

        if(!$result = $this->dbo->query($query)){
          //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
          return SERVER_ERROR;
        }
        if($result->num_rows > 0){
          while ($row = $result->fetch_row()){
            echo '<li>';
            echo '<a href="index.php?action=' . $action_type . '&type='.$category_type.'&category_id=' . $row[0] . '">';
            echo $row[3];
            echo '</a></li>';
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

  function deleteCategory($category_type)
  {
    $user_id =  $_SESSION['user_id'];
    $category_id = $_SESSION['category_id'];
    return $this->deleteCategoryFromTable($category_type, $category_id, $user_id);
  }

  function deleteCategoryFromTable($category_type, $category_id, $user_id)
  {
    $tableName = $this->getTableName($category_type);

     $query = "SELECT * FROM ".$tableName." WHERE user_id = '$user_id'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows >= 1){
      
      $query = "DELETE FROM ".$tableName." WHERE user_id = '$user_id' AND id = '$category_id'";

      if(!$result = $this->dbo->query($query)){
        //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
        return SERVER_ERROR;
      }
      return ACTION_OK; 
    } else return ACTION_FAILED;
  }

  function editCategoryName($category_type, $category_name, $category_id){

    $tableName = $this->getTableName($category_type);

    $tableShort = substr($tableName, 0, 3);

    $user_id =  $_SESSION['user_id'];

    $query = "SELECT * FROM ".$tableName." WHERE user_id = '$user_id' AND `id` = '$category_id'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows == 1){
      if($this->isSuchNameExist($category_name, $category_type)){
        $_SESSION['category_exists'] = true;
          return CATEGORY_NAME_ALREADY_EXISTS;
      } else {
          $query = "UPDATE ".$tableName." SET `name` = '$category_name' WHERE user_id = '$user_id' AND `id` = '$category_id'";

            if(!$result = $this->dbo->query($query)){
              //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
              return SERVER_ERROR;
            }
          return ACTION_OK;
      }
    }
  }

  function generateSmallModal($id, $action_param, $param1 = "")
  {
    $action_param .= $param1;

    echo <<< EOT
    <div class="modal fade" id="$id" role="dialog">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Podaj nazwę nowej kategorii</h4>
              </div>
              <div class="modal-body">
                <form role="form" action="index.php?action=$action_param" method="POST">
                  <div class="form-group">
                    <input type="text" name="categoryName" id="categoryName" class="form-control" required = "true" />
                  </div>            
              </div>
              <div class="modal-footer">
                <div class="form-group">
              <input type="submit" name="submit" class="btn btn-success" value="Zatwierdź" >
            </div>
              </div>
              </form>
            </div>
          </div>
        </div>
EOT;
  }

function generateLargeModal($id, $action_param, $param1 = "")
  {
    $action_param .= $param1;

    echo <<< EOT
    <div class="modal fade" id="$id" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Wybierz kategorię do edycji i podaj nową nazwę</h4>
          </div>
          <div class="modal-body">
            <form role="form" action="index.php?action=$action_param" method="POST">
              <div class="form-group">
                <input type="text" name="categoryName" id="categoryName" class="form-control" required = "true"/>
              </div>
              <div class="form-group">
                  <div class="col-md-8" style="text-align: left; margin-left: 20px;">
EOT;
    $radioType = substr($param1, 6);

    $this->showCategoriesAsRadioButtons($radioType);

    echo <<< EOT
                  </div>
              </div>            
          </div>
          <div class="modal-footer">
            <div class="form-group">
              <input type="submit" name="submit" class="btn btn-success" value="Zatwierdź" >
            </div>
          </div>
            </form>
        </div>
      </div>
    </div>
EOT;
  }

  function isSuchNameExist($category_name, $category_type)
  {
    $user_id = $_SESSION['user_id'];

    $tableName = $this->getTableName($category_type);

    $query = "SELECT * FROM ".$tableName." WHERE user_id = '$user_id' AND name = '$category_name'";

    if(!$result = $this->dbo->query($query)){
      //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
      return SERVER_ERROR;
    }

    if($result->num_rows >= 1){
        return true;
      } else {
        return false;
      }
  }

  function getTableName($category_type)
  {
    if($category_type == 'income') return 'incomes_category';
    if($category_type == 'expense') return 'expenses_category';
    if($category_type == 'payment') return 'payment_methods';
  }

}

?>