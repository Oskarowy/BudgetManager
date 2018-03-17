<?php if(!isset($manager)) die(); ?>

<div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
    <div class="panel panel-success" >
        <div class="panel-heading">
            <div class="panel-title"><strong>Logowanie</strong></div>             
		</div>     

        <div class="panel-body" style="padding-top:25px">   
            <form 	id="loginform" 
                    class="form-horizontal" 
                    role="form" 
                    action="index.php?action=login" 
                    method="post">
                                   
                <div class="input-group input-margin">
                    <span 	class="input-group-addon">
                        <i 	class="glyphicon glyphicon-user"></i>
                    </span>
                    <input 	type="text" 
                        	class="form-control" 
                            name="username" 
                        	value="" 
                        	placeholder="login lub email">                                        
                </div>
                        
                <div class="input-group">
                    <span 	class="input-group-addon">
                        <i 	class="glyphicon glyphicon-lock"></i>
                    </span>
                    <input 	type="password" 
                        	class="form-control" 
                            name="password" 
                        	placeholder="hasło">
                </div>
                                    
				<div class="input-group">
                    <div class="checkbox">
                        <label>
                            <input 	id="login-remember" 
                             		type="checkbox" 
                            		name="remember" 
                            		value="1"> 
                            		Zapamiętaj mnie
                        </label>
                    </div>
                </div>

                <div style="margin-top:30px" class="form-group">
                    <div class="col-sm-12">
						<input 	type="submit" 
								class="btn btn-success" 
								value="Zaloguj się!">
						</input>
                    </div>
                </div>
			</form>	
			<hr>
            <div class="col-md-12">
                <div id="gotoregistration" class="well">
                    <h4>Nowy Użytkownik?</h4>
					<a 	class="btn btn-info" 
						href="index.php?action=showRegistrationForm">
						Zarejestruj się!
					</a>
				</div>
            </div>       
        </div>                     
    </div>  
</div>