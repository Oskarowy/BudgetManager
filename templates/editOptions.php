<div class="well main-menu">
	<div class="row">
		<div class="col-xs-12 ">
			<h1>Dostępne opcje edycji</h1>
			<hr>
			<label style="font-size: 15px;">Edycja nazwy kategorii</label>
				<div class="btn-group btn-group-justified" role="group" aria-label="...">
					<div class="btn-group btn-group-lg" role="group">
						<button type="button" 
								class="btn btn-primary" 
								onClick="location.href='index.php?action=editCategory&type=incomes'">Przychody</button>
					</div>
					<div class="btn-group btn-group-lg" role="group">
						<button type="button" 
								class="btn btn-primary"
								onClick="location.href='index.php?action=editCategory&type=expenses'">Wydatki</button>
					</div>
					<div class="btn-group btn-group-lg" role="group">
						<button type="button" 
								class="btn btn-primary" 
								onClick="location.href='index.php?action=editCategory&type=payment'">Sposoby płatności</button>
					</div>
				</div>
			<hr>
			<label style="font-size: 15px;">Dodanie nowej kategorii</label>
				<div class="btn-group btn-group-justified" role="group" aria-label="...">
					<div class="btn-group btn-group-lg" role="group">
						<button type="button" class="btn btn-success">Przychody</button>
					</div>
					<div class="btn-group btn-group-lg" role="group">
						<button type="button" class="btn btn-success">Wydatki</button>
					</div>
					<div class="btn-group btn-group-lg" role="group">
						<button type="button" class="btn btn-success">Sposoby płatności</button>
					</div>
				</div>
			<hr>
			<label style="font-size: 15px;">Usuwanie kategorii</label>
				<div class="btn-group btn-group-justified" role="group" aria-label="...">
					<div class="btn-group btn-group-lg" role="group">
						<button type="button" class="btn btn-danger">Przychody</button>
					</div>
					<div class="btn-group btn-group-lg" role="group">
						<button type="button" class="btn btn-danger">Wydatki</button>
					</div>
					<div class="btn-group btn-group-lg" role="group">
						<button type="button" class="btn btn-danger">Sposoby płatności</button>
					</div>
				</div>
			<div class="btn-vertical ">
				<hr>
				<label style="font-size: 15px; margin-bottom: 15px;">Edycja konta Użytkownika</label>
				<button 	onClick="location.href='index.php?action=showMain'" 
							class="btn btn-lg btn-block btn-warning ">
						<i 	class="glyphicon glyphicon-wrench"></i> 
						Edycja danych Użytkownika
				</button>
				<button 	onClick='var result = confirm("Want to delete?");
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