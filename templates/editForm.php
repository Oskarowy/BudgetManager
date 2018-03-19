<div id="edit" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title"><strong>Formularz edycji danych Użytkownika</strong></div>
        </div>  
		<div class="panel-body" style="padding-top: 25px" >
			<form 	id = "editform" 
                    class  = "form-horizontal" 
                    action = "index.php?action=editUser" 
                    method = "post">
            	<div class="form-group">
                            <label for="editemail" class="col-md-3 control-label">E-mail</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="editemail" 
									value="<?=$manager->logged->mail;?>" >
                                </div>
                        </div>                  
                        <div class="form-group">
                            <label for="editname" class="col-md-3 control-label">Imię</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="editname" 
									value="<?=$manager->logged->username;?>">
                                </div>
                        </div>
                        <div class="form-group">
                            <label for="editpass" class="col-md-3 control-label">Nowe hasło</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" name="editpass" 
									value=""
									placeholder="Podaj nowe hasło">
                                </div>
                        </div>   
						<div class="form-group">
                            <label for="editpass2" class="col-md-3 control-label">Potwierdź hasło</label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" name="editpass2" 
									value=""
									placeholder="Ponownie podaj hasło">
                                </div>
                        </div> 		  
                <div class="form-group">                                                                    
                    <div class="col-md-offset-3 col-md-9">
						<button id="btn-add" 
								type="submit" 
								class="btn btn-success">
							<i 	class="glyphicon glyphicon-ok"></i> 
							Potwierdź
						</button>
						<button onClick="location.href='index.php?action=showMenu'" 
								id="btn-back" 
								type="button" 
								class="btn btn-danger">
							<i 	class="glyphicon glyphicon-remove"></i> 
							Anuluj
						</button>             
                    </div>
                </div>
            </form>
		</div>
	</div>
</div>
		