<div class="well main-menu">
	<div class="row">
		<div class="col-xs-12 ">
			<h1>Menu główne</h1>
			<?php if(isset($_SESSION['category_exists'])){
				echo '<h3><br />Kategoria o takiej nazwie już istnieje. Wybierz inną nazwę!</h3>';
				unset($_SESSION['category_exists']);
			}?>
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
				<button 	onClick="location.href='index.php?action=checkout&period=currentmonth'" 
							class="btn btn-lg btn-block btn-success ">
						<i 	class="glyphicon glyphicon-stats"></i> 
						Przeglądaj bilans
				</button>
				<button 	onClick="location.href='index.php?action=edit'" 
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