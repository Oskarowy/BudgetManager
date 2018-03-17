<?php if(!$this) die();?>

<div id="signupbox" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title"><strong>Rejestracja</strong></div>
        </div>  
                
		<div class="panel-body" style="padding-top: 25px" >
            <form 	id ="signupform" 
            		class ="form-horizontal" 
            		action = "index.php?action=registerUser" 
            		method ="post">
            <?php foreach($formData as $input): ?>		
                <div class="form-group">
                    <label class="col-md-3 control-label">
                    	<?=$input->description?>
                    </label>
                    <div class="col-md-9">
                        <?=$input->getInputHTML()?>
                    </div>		
                </div>
            <?php endforeach;?>                             
			
			<div class="form-group">                                                            
                <div class="col-md-offset-3 col-md-9">
					<input 	type="submit" 
							class="btn btn-info" 
							value="Zarejestruj się!">
					</input>
                </div>
            </div>
			</form>
			<hr>
            <div class="col-md-12">
                <div id="gotologin" class="well">
                    <h4>Masz już konto? </h4>
					<a 	class="btn btn-success" 
						id="signinlink" 
						href="index.php?action=showLoginForm">
						Zaloguj się!
					</a>
				</div>
            </div>
        </div> 
    </div>
</div>  