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
            case ACTION_FAILED:
                $manager->setMessage('Obecnie dodanie wydatku nie jest możliwe.');
               break;
            case SERVER_ERROR:
            default:
              $manager->setMessage('Błąd serwera!');
          endswitch;
          header('Location:index.php?action=showMenu');
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