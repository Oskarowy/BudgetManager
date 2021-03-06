<?php if(!isset($manager)) die(); 
require_once (__DIR__ . '/../basics/pageHead.php');
require_once (__DIR__ . '/../basics/pageNav.php');
?>

<div class="container">
	<div id="mainContent">

		<?php if($message): ?>
        <div class="message" style="color:white; font-size: 20px; margin: 20px;"><?=$message;?></div>
        <?php endif; ?>	

        <?php
          switch($action):
          	case 'showBalance':
          	  include 'templates/balanceTemplate.php';
          	  break;
          	case 'showMenu':
          	  include 'templates/mainMenu.php';
          	  break;
            case 'showLoginForm' :
              include 'templates/loginForm.php';
              break;
            case 'showEditOptions' :
              include 'templates/editOptions.php';
              break;
            case 'showEditForm':
              include 'templates/editForm.php';
              break;
            case 'showRegistrationForm' :
              $manager->showRegistrationForm();
              break;
            case 'addRecord':
            	include 'templates/addRecord.php';
            	break;
            case 'showMain':
            default:
              include 'templates/innerContentDiv.php';
          endswitch;
        ?>

	</div>
</div>

<?php require_once (__DIR__ . '/../basics/pageFoot.php'); ?>