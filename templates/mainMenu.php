<div class="well main-menu">
	<div class="row">
		<div class="col-xs-12 ">
			<h1>Menu główne</h1>
			<hr>
			<div class="btn-vertical ">
				<button 	onClick="location.href='index.php?action=addRecord&type=income'" 
							class="btn btn-lg btn-block btn-info ">
						<i 	class="glyphicon glyphicon-plus"></i>  
						Dodaj przychód 
				</button>
				<button 	onClick="location.href='index.php?action=addRecord&type=expense'" 
							class="btn btn-lg btn-block btn-primary ">
						<i 	class="glyphicon glyphicon-minus"></i> 
						Dodaj wydatek
				</button>
				<button 	onClick="location.href='przeglad-bilansu?role=currentmonth'" 
							class="btn btn-lg btn-block btn-success ">
						<i 	class="glyphicon glyphicon-stats"></i> 
						Przeglądaj bilans
				</button>
				<button 	onClick="location.href='zarzadzaj-swoim-budzetem'" 
							class="btn btn-lg btn-block btn-warning ">
						<i 	class="glyphicon glyphicon-wrench"></i> 
						Ustawienia
				</button>
				<hr>
				<button 	onClick="location.href='index.php?action=logout'" 
							class="btn btn-lg btn-block btn-danger ">
						<i 	class="glyphicon glyphicon-log-out"></i> 
						Wyloguj się
				</button>
			</div>
		</div>
	</div>
</div>