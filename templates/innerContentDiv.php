<?php if($manager->logged){
	header("Location:index.php?action=showMenu");
} ?>
<article>
	<div class="jumbotron coffeetron">
		<h1 style="letter-spacing: 5px;">
			<i class="icon-help"></i>
			Czy wiesz, że... 
			<i class="icon-help"></i> <br /> 
		</h1>
		<h2>Wystarczą trzy proste kroki, aby zapanować nad swoim domowym budżetem!</h2>
			<div id="steps">
				<strong>Krok 1:</strong> 
				Załóż konto i dołącz do grona Użytkowników <br /> 
				<strong>Krok 2:</strong> 
				Dodaj swoje przychody oraz wydatki do aplikacji, a następnie sprawdź ich bilans <br />
				<strong>Krok 3:</strong> 
				Obserwuj gromadzone oszczędności lub dowiedz się, czy znajdujesz się &bdquo;pod kreską&rdquo;!
			</div>
	</div>
</article>
		
	<div class="well gotoforms">
		<div class="row">
			<div class="col-lg-6">
				<a 	class="form-link" 
					href="index.php?action=showLoginForm"><h2>Masz już konto?</h2>
				<button class="btn btn-success btn-block btn-lg">Zaloguj się!</button></a>
			</div>
			<div class="col-lg-6">
				<a 	class="form-link" 
					href="index.php?action=showRegistrationForm"><h2>Nowy Użytkownik?</h2>
				<button class="btn btn-info btn-block btn-lg">Zarejestruj się!</button></a>
			</div>
		</div>
	</div>