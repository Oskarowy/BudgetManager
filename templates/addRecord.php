<?php if(!isset($manager)) die(); 

    $date = getdate();
    $d = $date['mday'];
    $m = $date['mon'];
    $y = $date['year'];
    if($d<10) $d = '0'.$d;
    if($m<10) $m = '0'.$m;
    $today = $y.'-'.$m.'-'.$d;

?>

<div id="addexp" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="panel-title"><strong>Dodawanie nowego wydatku</strong></div>
                </div>  
                
				<div class="panel-body" style="padding-top: 25px" >
                    <form 	id     = "expform" 
                    		class  = "form-horizontal" 
                    		action = "index.php?action=addExp" 
                    		method = "post">

                        <div class="form-group">
                            <label 	for="expamount" 
                            		class="col-md-3 control-label">
                            	Kwota
                            </label>
                            <div class="col-md-9">
                                <input 	type="text" 
                                		class="form-control" 
                                		name="expamount" 
                                		placeholder="podaj kwotę w PLN">
                            </div>
                        </div>
                        
						<div class="form-group">
                            <label 	for="expdate" 
                            		class="col-md-3 control-label">
                            	Data
                            </label>
                            <div class="col-md-9">
                                <input 	type="date" 
                                		class="form-control" 
                                		name="expdate" 
                                		value="<?php echo($today); ?>">
                            </div>
                        </div>
						
                        <div class="form-group">
                            <label 	for="payment" 
                            		class="col-md-3 control-label">
                            	Sposób płatności
                            </label>
                            <div class="col-md-8" style="text-align: left; margin-left: 20px;">
                                <label 	class="radio">
                                <input 	type="radio" 
                                		name="payment" 
                                		value="1">
                                	Gotówka
                                </label>
								<label 	class="radio">
								<input 	type="radio" 
										name="payment" 
										value="2">
									Karta debetowa
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="payment" 
										value="3">
									Karta kredytowa
								</label>
                            </div>
                        </div>
						
						<div class="form-group">
                            <label 	for="expcategory" 
                            		class="col-md-3 control-label">Kategoria</label>
                            <div class="col-md-8" style="text-align: left; margin-left: 20px;">
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="1"/>
									Transport
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="2"/>
									Książki
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="3"/>
									Jedzenie
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="4" />
									Mieszkanie
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="5"/>
									Telekomunikacja
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="6"/>
									Opieka zdrowotna
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="7"/>
									Ubranie
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="8"/>
									Higiena
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="9"/>
									Dzieci
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="10"/>
									Rozrywka
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="11"/>
									Wycieczka
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="12"/>
									Oszczędności
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="13"/>
									Na złotą jesień, czyli emeryturę
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="14"/>
									Spłata długów
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="15"/>
									Darowizna
								</label>
								<label 	class="radio">
								<input 	type="radio" 
										name="expcategory" 
										value="16"/>
									Inne wydatki
								</label>
                            </div>
                        </div>
						
						<div class="form-group">
                            <label 	for="expcomment" 
                            		class="col-md-3 control-label">
                            	Komentarz (opcjonalnie)
                            </label>
                            <div class="col-md-9">
                                <textarea 	class="form-control" 
                                			name="expcomment" 
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
