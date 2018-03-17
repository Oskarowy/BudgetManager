<div class="container">  		
	<nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="row">
				<div class="col">
					<div class="col-xs-3 col-sm-2 col-md-3 col-lg-3">
						<div class="navbar-header">
							<a 	class="navbar-brand navbar-text" 
								style="margin-top: 0px;" 
								href="index.php?action=showMain" >
									<span 	class="visible-xs visible-sm" 
											style="font-size: 20px; margin-left: -10px;">
										Budget</br>manager
									</span>
									<span 	class="hidden-xs hidden-sm" >
										Budget<br/> manager
									</span>
							</a>
						</div>
					</div>
					<div class="container">
						<ul style="margin-top: 10px;" 
							class="nav navbar-nav navbar-right">
							<?php if($manager->logged): ?>
	         					<li>
	         						<div>
	         							<h4 style="color: white;" >
	         								Zalogowany: <strong><?=$manager->logged->username?></strong>
	         							</h4>
	         						</div>
	          					</li>
	          					<li>
	          						<button onClick="location.href='index.php?action=logout'" 
											type="button" 
											class="btn btn-info navbar-btn btn-sm menubutton">
											Wyloguj się
									</button>
								</li>
        					<?php else: ?>
								<li>
									<button onClick="location.href='index.php?action=showLoginForm'" 
											type="button" 
											class="btn btn-success navbar-btn btn-sm menubutton">
										Zaloguj się
									</button>
								</li>
								<li>
									<button onClick="location.href='index.php?action=showRegistrationForm'" 
											type="button" 
											class="btn btn-info navbar-btn btn-sm hidden-xs menubutton">
										Zarejestruj się
									</button>
								</li>
							<?php endif ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</nav>
