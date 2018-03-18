<div id="addinc" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title"><strong>Dodawanie nowego przychodu</strong></div>
                </div>  
                
				<div class="panel-body" style="padding-top: 25px" >
                    <form 	id     = "incform" 
                    		class  = "form-horizontal" 
                    		action = "index.php?action=addInc" 
                    		method = "post">

                        <div class="form-group">
                            <label 	for="incamount" 
                            		class="col-md-3 control-label">
                            	Kwota
                            </label>
                            <div class="col-md-9">
                                <input 	type="text" 
                                		class="form-control" 
                                		name="incamount" 
                                		placeholder="podaj kwotę w PLN">
                            </div>
                        </div>
                        
						<div class="form-group">
                            <label 	for="incdate" 
                            		class="col-md-3 control-label">
                            	Data
                            </label>
                            <div class="col-md-9">
                                <input 	type="date" 
                                		class="form-control" 
                                		name="incdate" 
                                		value="<?php echo($today); ?>">
                            </div>
                        </div>
						
                        <div class="form-group">
                            <label 	for="inccategory" 
                            		class="col-md-3 control-label">
                            	Kategoria
                            </label>
                            <div class="col-md-8" style="text-align: left; margin-left: 20px;">
                                <?php 
                                try{
                                    if(!$manager->dbo) {
                                        return SERVER_ERROR;
                                    }
                                    else {
                                        $manager->dbo->query("SET CHARSET utf8");
                                        $manager->dbo->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`"); 
                                            
                                        $user_id = $_SESSION['user_id'];

                                        $query = "SELECT * FROM incomes_category WHERE user_id = '$user_id' ORDER BY `order` ASC";

                                        if(!$result = $manager->dbo->query($query)){
                                            //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
                                            return SERVER_ERROR;
                                        }

                                        if($result->num_rows > 0){
                                                
                                            while ($row = $result->fetch_row()){
                                                echo '<label  class="radio">';
                                                echo '<input  type="radio" name="inccategory" value="'.$row[0].'"/>';
                                                echo $row[3];
                                                echo '</label>';
                                            }
                                        } 
                                        else {
                                            //echo 'Wystąpił błąd: brak kategorii dla danego Użytkownika';
                                            return SERVER_ERROR;
                                        }   
                                    }
                                } catch (Exception $e){
                                    //echo 'Błąd: ' . $e->getMessage();
                                    exit('Aplikacja chwilowo niedostępna'); 
                                }
                                ?>
                            </div>
                        </div>
						
						<div class="form-group">
                            <label 	for="inccomment" 
                            		class="col-md-3 control-label">
                            	Komentarz (opcjonalnie)
                            </label>
                            <div class="col-md-9">
                                <textarea 	class="form-control" 
                                			name="inccomment" 
                                			rows="3" 
                                			placeholder="warto dodać, w przypadku wybrania kategorii Inne">
                       			</textarea>
                            </div>
                        </div>
                                                             
						<div class="form-group">                                                                    
                            <div class="col-md-offset-3 col-md-9">
								<button id="btn-add" 
										type="submit" 
										class="btn btn-primary">
									<i 	class="glyphicon glyphicon-plus"></i> 
									Dodaj
								</button>
								<button onClick="location.href='index.php?action=showMenu'" 
										id="btn-back" 
										type="button" 
										class="btn btn-danger">
									<i 	class="glyphicon glyphicon-remove"></i> 
									Wróć
								</button>             
                            </div>
                        </div> 
                    </form>
                </div>
    </div>   
</div> 