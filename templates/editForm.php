<div id="edit" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title"><strong>Formularz edycji</strong></div>
        </div>  
		<div class="panel-body" style="padding-top: 25px" >
			<form 	id = "editform" 
                    class  = "form-horizontal" 
                    action = "index.php?action=editCategory&type=incomes" 
                    method = "post">

                
                
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
		