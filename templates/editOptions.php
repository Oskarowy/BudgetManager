<?php
	if(isset($_SESSION['type'])) unset($_SESSION['type']);
?>

<div class="well main-menu">
	<div class="row">
		<div class="col-xs-12 ">
			<h1>Dostępne opcje edycji</h1>
			<hr>
			<label style="font-size: 15px;">Edycja nazwy kategorii</label>
			<?php $action_type = 'editCategory'; ?>
				<div class="btn-group btn-group-justified" role="group" aria-label="...">
					<div class="btn-group btn-group-lg" role="group">
					    <button type="button" 
					    		class="btn btn-primary dropdown-toggle" 
					    		data-toggle="dropdown" 
					    		aria-haspopup="true" 
					    		aria-expanded="true">
					      	Przychody
					    		<span class="caret"></span>
					    </button>
					    <ul class="dropdown-menu">
					    	<?=$manager->showIncomesCategoriesAsList($action_type);?>
					    </ul>
					</div>
					<div class="btn-group btn-group-lg" role="group">
					    <button type="button" 
					    		class="btn btn-primary dropdown-toggle" 
					    		data-toggle="dropdown" 
					    		aria-haspopup="true" 
					    		aria-expanded="true">
					      	Wydatki
					    		<span class="caret"></span>
					    </button>
					    <ul class="dropdown-menu">
					    	<?=$manager->showExpensesCategoriesAsList($action_type);?>	
					    </ul>
					</div>
					<div class="btn-group btn-group-lg" role="group">
					    <button type="button" 
					    		class="btn btn-primary dropdown-toggle" 
					    		data-toggle="dropdown" 
					    		aria-haspopup="true" 
					    		aria-expanded="true">
					      	Sposoby płatności
					    		<span class="caret"></span>
					    </button>
					    <ul class="dropdown-menu">
					    	<?=$manager->showPaymentsAsList($action_type);?>
					    </ul>
					</div>
				</div>
			<hr>
			<label style="font-size: 15px;">Dodanie nowej kategorii</label>
				<div class="btn-group btn-group-justified" role="group" aria-label="...">
					<div class="btn-group btn-group-lg" role="group">
						<a type="button" 
								class="btn btn-success" 
								data-toggle="modal" 
								data-target="#setIncomeName">Przychody</a>
					</div>
					<div class="btn-group btn-group-lg" role="group">
						<a type="button" 
								class="btn btn-success"
								data-toggle="modal" 
								data-target="#setExpenseName">Wydatki</a>
					</div>
					<div class="btn-group btn-group-lg" role="group">
						<a type="button" 
								class="btn btn-success" 
								data-toggle="modal" 
								data-target="#setPaymentName">Sposoby płatności</a>
					</div>
				</div>		  
			<hr>
			<label style="font-size: 15px;">Usuwanie kategorii</label>
			<?php $action_type = 'deleteCategory'; ?>
				<div class="btn-group btn-group-justified" role="group" aria-label="...">
					<div class="btn-group btn-group-lg" role="group">
					    <button type="button" 
					    		class="btn btn-danger dropdown-toggle" 
					    		data-toggle="dropdown" 
					    		aria-haspopup="true" 
					    		aria-expanded="true">
					      	Przychody
					    		<span class="caret"></span>
					    </button>
					    <ul class="dropdown-menu">
					    	<?=$manager->showIncomesCategoriesAsList($action_type);?>
					    </ul>
					</div>
					<div class="btn-group btn-group-lg" role="group">
					    <button type="button" 
					    		class="btn btn-danger dropdown-toggle" 
					    		data-toggle="dropdown" 
					    		aria-haspopup="true" 
					    		aria-expanded="true">
					      	Wydatki
					    		<span class="caret"></span>
					    </button>
					    <ul class="dropdown-menu">
					    	<?=$manager->showExpensesCategoriesAsList($action_type);?>
					    </ul>
					</div>
					<div class="btn-group btn-group-lg" role="group">
					    <button type="button" 
					    		class="btn btn-danger dropdown-toggle" 
					    		data-toggle="dropdown" 
					    		aria-haspopup="true" 
					    		aria-expanded="true">
					      	Sposoby płatności
					    		<span class="caret"></span>
					    </button>
					    <ul class="dropdown-menu">
					    	<?=$manager->showPaymentsAsList($action_type);?>
					    </ul>
					</div>
				</div>
			<div class="btn-vertical ">
				<hr>
				<label style="font-size: 15px; margin-bottom: 15px;">Edycja konta Użytkownika</label>
				<button 	onClick="location.href='index.php?action=editUser'" 
							class="btn btn-lg btn-block btn-warning ">
						<i 	class="glyphicon glyphicon-wrench"></i> 
						Edycja danych Użytkownika
				</button>
				<button 	onClick='var result = confirm("Czy na pewno chcesz usunąć swoje konto?");
									if (result) {
									    window.location = "index.php?action=deleteUser";
									}' 
							class="btn btn-lg btn-block btn-danger ">
						<i 	class="glyphicon glyphicon-remove"></i> 
						Usuń swoje konto
				</button>
				<hr>
				<button 	onClick="location.href='index.php?action=showMain'" 
							class="btn btn-lg btn-block btn-info ">
						<i 	class="glyphicon glyphicon-log-out"></i> 
						Powrót do menu głównego
				</button>
			</div>
		</div>
	</div>
</div>
			  <!-- inc modal -->
			  <div class="modal fade" id="setIncomeName" role="dialog">
			    <div class="modal-dialog modal-sm">
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Podaj nazwę nowej kategorii</h4>
			        </div>
			        <div class="modal-body">
			        	<form role="form" action="index.php?action=addCategory&type=income" method="POST">
			        		<div class="form-group">
			        			<input type="text" name="categoryName" id="categoryName" class="form-control" />
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
			 <!-- exp modal -->
			  <div class="modal fade" id="setExpenseName" role="dialog">
			    <div class="modal-dialog modal-sm">
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Podaj nazwę nowej kategorii</h4>
			        </div>
			        <div class="modal-body">
			        	<form role="form" action="index.php?action=addCategory&type=expense" method="POST">
			        		<div class="form-group">
			        			<input type="text" name="categoryName" id="categoryName" class="form-control" />
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
			   <!-- pay modal -->
			  <div class="modal fade" id="setPaymentName" role="dialog">
			    <div class="modal-dialog modal-sm">
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Podaj nazwę nowej kategorii</h4>
			        </div>
			        <div class="modal-body">
			        	<form role="form" action="index.php?action=addCategory&type=payment" method="POST">
			        		<div class="form-group">
			        			<input type="text" name="categoryName" id="categoryName" class="form-control" />
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