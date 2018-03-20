<?php
include 'constants.php';
spl_autoload_register('classLoader');
session_start();

try{
	$manager = new ManagerFront("localhost", "root", "", "budgetplanner");

	$action = 'showMain';
	if(isset($_GET['action'])) {
		$action = $_GET['action'];
	}

	$message = $manager->getMessage();
	if(!$message && $action == 'showLoginForm'){
		$message = 'Wprowadź nazwę lub e-mail i hasło użytkownika';
	}

	if(($action == 'showLoginForm' || $action == 'showRegistrationForm' || $action == 'registerUser') && $manager->logged){
		$manager->setMessage('Najpierw proszę się wylogować.');
		header('Location:index.php?action=showMain');
		return;
	}

	switch($action){
		case 'login' : 
			switch($manager->login()){
				case ACTION_OK : 
					$manager->setMessage('Zalogowanie prawidłowe');
					$manager->setDefaultCategories();
					header('Location:index.php?action=showMenu');
					return;
				case NO_LOGIN_REQUIRED : 
					$manager->setMessage('Najpierw proszę się wylogować.');
			          header('Location:index.php?action=showMain');
			          return;
        		case ACTION_FAILED :
        		case FORM_DATA_MISSING :
          			$manager->setMessage('Błędna nazwa lub hasło użytkownika');
          			break;
        		default:
          			$manager->setMessage('Błąd serwera. Zalogowanie nie jest obecnie możliwe.');
      		}
      		header('Location:index.php?action=showLoginForm');
      		break;
    	case 'logout': 
      		$manager->logout();
      		header('Location:index.php?action=showMain');
      		break;
      case 'checkout':
          $period = $_GET['period'];
          header("Location:index.php?action=showBalance&period=$period");
          break;
      case 'edit':
          header('Location:index.php?action=showEditOptions');
          break;
      case 'editUser':
          switch($manager->editUser()):
            case ACTION_OK:
                $manager->logout();
                $manager->setMessage('Edycja danych prawidłowa, nastąpiło wylogowanie. Proszę zalogować się ponownie.');
                header('Location:index.php?action=showLoginForm');
                return;
            case PASSWORDS_DO_NOT_MATCH:
                $manager->setMessage('Hasło musi być takie samo w obu polach!');
                break;
            case USER_NAME_ALREADY_EXISTS:
                $manager->setMessage('Podany adres e-email jest już zarejestrowany!');
                break;
            case ACTION_FAILED:
                $manager->setMessage('Obecnie edycja nie jest możliwa.');
               break;
            case SERVER_ERROR:
              $manager->setMessage('Błąd serwera!');
            default:   
          endswitch;
          header('Location:index.php?action=showEditForm');
          break;
      case 'deleteUser':
          $manager->deleteUser();
          $manager->logout();
          $manager->setMessage('Konto zalogowanego Użytkownika zostało usunięte.');
          header('Location:index.php?action=showMain');
          break;
    	case 'registerUser':
      		switch($manager->registerUser()):
        		case ACTION_OK:
          			$manager->setMessage('Rejestracja prawidłowa. Możesz się teraz zalogować.');
          			header('Location:index.php?action=showLoginForm');
          			return;
        		case FORM_DATA_MISSING:
          			$manager->setMessage('Proszę wypełnić wszystkie pola formularza!');
          			break;
        		case PASSWORDS_DO_NOT_MATCH:
          			$manager->setMessage('Hasło musi być takie samo w obu polach!');
          			break;
        		case USER_NAME_ALREADY_EXISTS:
          			$manager->setMessage('Podany adres e-email jest już zarejestrowany!');
          			break;
        		case ACTION_FAILED:
          			$manager->setMessage('Obecnie rejestracja nie jest możliwa.');
         			 break;
        		case SERVER_ERROR:
        		default:
        			$manager->setMessage('Błąd serwera!');
      		endswitch;
      		header('Location:index.php?action=showRegistrationForm');
     		break;
      case 'addExp':
        switch($manager->addExpense()):
            case ACTION_OK:
                $manager->setMessage('Pomyślnie dodano nowy wydatek.');
                header('Location:index.php?action=showMenu');
                return;
            case AMOUNT_NOT_NUMERIC;
                $manager->setMessage('Kwota może zawierać wyłącznie cyfry!');
                break;
            case FORM_DATA_MISSING:
                $manager->setMessage('Proszę wypełnić wszystkie pola formularza!');
                break;
            case TO_LOW_DATE:
                $manager->setMessage('Data nie może być wcześniejsza, niż 01-01-2000!');
                break;
            case TO_HIGH_DATE:
                $manager->setMessage('Data nie może być późniejsza, niż ostatni dzień bieżącego miesiąca!');
                break;
            case ACTION_FAILED:
                $manager->setMessage('Obecnie dodanie wydatku nie jest możliwe.');
               break;
            case SERVER_ERROR:
            default:
              $manager->setMessage('Błąd serwera!');
          endswitch;
          header('Location:index.php?action=addRecord&type=expense');
        break;
      case 'addInc':
        switch($manager->addIncome()):
            case ACTION_OK:
                $manager->setMessage('Pomyślnie dodano nowy przychód.');
                header('Location:index.php?action=showMenu');
                return;
            case AMOUNT_NOT_NUMERIC;
                $manager->setMessage('Kwota może zawierać wyłącznie cyfry!');
                break;
            case FORM_DATA_MISSING:
                $manager->setMessage('Proszę wypełnić wszystkie pola formularza!');
                break;
            case TO_LOW_DATE:
                $manager->setMessage('Data nie może być wcześniejsza, niż 01-01-2000!');
                break;
            case TO_HIGH_DATE:
                $manager->setMessage('Data nie może być późniejsza, niż ostatni dzień bieżącego miesiąca!');
                break;
            case ACTION_FAILED:
                $manager->setMessage('Obecnie dodanie przychodu nie jest możliwe.');
               break;
            case SERVER_ERROR:
            default:
              $manager->setMessage('Błąd serwera!');
          endswitch;
          header('Location:index.php?action=addRecord&type=income');
        break;
      case 'addCategory':
        $category_type = $_GET['type'];
        $category_name = $_POST['categoryName'];

        switch ($category_type) {
          case 'income':
            $sentence = "nową kategorię przychodów";
            break;
          case 'expense':
            $sentence = "nową kategorię wydatków";
            break;
          case 'payment':
            $sentence = "nowy sposób płatności";
            break;
          default:
            $sentence = "nowy rekord";
            break;
        }
        switch($manager->addCategory($category_type, $category_name)):
            case ACTION_OK:
                $manager->setMessage('Pomyślnie dodano '.$sentence.'');
                header('Location:index.php?action=showMenu');
                return;
            case ACTION_FAILED:
                $manager->setMessage('Obecnie dodanie nowego rekordu nie jest możliwe.');
               break;
            case SERVER_ERROR:          
            default:
                $manager->setMessage('Błąd serwera!');
          endswitch;
          header('Location:index.php?action=showMain');
        break;
      case 'editCategory':
      $category_type = $_GET['type'];
      $category_name = $_POST['categoryName'];
      if(isset($_POST['inccategory'])) $category_id = $_POST['inccategory'];
      if(isset($_POST['expcategory'])) $category_id = $_POST['expcategory'];
      if(isset($_POST['payment'])) $category_id = $_POST['payment'];
        switch($manager->editCategoryName($category_type, $category_name, $category_id)):
          case ACTION_OK:
            $manager->setMessage('Pomyślnie zmieniono nazwę kategorii.');
            header('Location:index.php?action=showMenu');
            return;
          case FORM_DATA_MISSING:
            $manager->setMessage('Proszę wypełnić wszystkie pola formularza!');
            break;
          case CATEGORY_NAME_ALREADY_EXISTS:
            $manager->setMessage('Kategoria o takiej nazwie już istnieje. Wybierz inną nazwę!');
            break;
          case ACTION_FAILED:
            $manager->setMessage('Obecnie edycja kategorii nie jest możliwa.');
            break;
          case SERVER_ERROR:
          default:
            $manager->setMessage('Błąd serwera!');
        endswitch;
        header('Location:index.php?action=showMain');
        break;
      case 'deleteCategory':
      $category_type = $_GET['type'];
      $_SESSION['category_id'] = $_GET['category_id'];
        switch($manager->deleteCategory($category_type)):
          case ACTION_OK:
            $manager->setMessage('Wybrana kategoria została usunięta.');
            header('Location:index.php?action=showMenu');
            return;
          case ACTION_FAILED:
            $manager->setMessage('Obecnie usunięcie kategorii nie jest możliwe.');
            break;
          case SERVER_ERROR:
          default:
            $manager->setMessage('Błąd serwera!');
        endswitch;
        header('Location:index.php?action=showMain');
        break;
   		default:
      		include 'templates/mainTemplate.php';
  }	
} catch (Exception $e){
	//echo 'Błąd: ' . $e->getMessage();
	exit('Aplikacja chwilowo niedostępna');	
}


function classLoader($className){
	if(file_exists("classes/$className.php")){
		require_once("classes/$className.php");
	} else{
		throw new Exception("Brak pliku z definicją klasy.");
	}
}
?>