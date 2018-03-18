<?php if(!isset($manager)) die();

$date = getdate();
	$d = $date['mday'];
	$m = $date['mon'];
	$y = $date['year'];
	if($d<10) $d = '0'.$d;
	if($m<10) $m = '0'.$m;
	$today = $y.'-'.$m.'-'.$d;

	if((isset($_POST['custom_min']))&&(isset($_POST['custom_max']))){
		$_SESSION['mindate'] = $_POST['custom_min'];
		$_SESSION['maxdate'] = $_POST['custom_max'];
		header("Refresh:0; url=index.php?action=checkout&period=customperiod");
	}

if(isset($_GET['period'])){
	if($_GET['period']=='previousmonth') {
		if($m==1) { $m = 12; $y--; } else $m--;
			$today_last_month = $y.'-'.$m.'-'.$d;
			$previous_month_last_day = date("t", strtotime($today_last_month));
			if($d<10) $d = '0'.$d;
			if($m<10) $m = '0'.$m;
			$min_date = $y.'-'.$m.'-01';
			$max_date = $y.'-'.$m.'-'.$previous_month_last_day;
		} else if($_GET['period']=='currentmonth'){
			$current_month_last_day = date("t", strtotime($today));
			$min_date = $y.'-'.$m.'-01';
			$max_date = $y.'-'.$m.'-'.$current_month_last_day;
		} else if($_GET['period']=='currentyear') {
			$min_date = $y.'-01-01';
			$max_date = $y.'-12-31'; 
		} else if($_GET['period']=='customperiod') {
			$min_date = $_SESSION['mindate'];
			$max_date = $_SESSION['maxdate'];
		}
	} else { // to be sure, set again current month as default while period is not set
		$current_month_last_day = date("t", strtotime($today));
		$min_date = $y.'-'.$m.'-01';
		$max_date = $y.'-'.$m.'-'.$current_month_last_day;
	}
			
	$_SESSION['balance'] = 0;
?>
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-xs-12">
			<div class="btn-group pull-right dropdown" style="margin-bottom: 10px;">
			  <button 	type="button" 
			  			class="btn btn-info dropdown-toggle"
			  			id="dropdownMenu1" 
			  			data-toggle="dropdown" 
			  			aria-haspopup="true" 
			  			aria-expanded="true">
				Wybierz okres bilansu 
				<span class="caret"></span>
			  </button>
			  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
				<li><a 	href="index.php?action=checkout&period=previousmonth">Poprzedni miesiąc</a></li>
				<li><a 	href="index.php?action=checkout&period=currentmonth">Bieżący miesiąc</a></li>
				<li><a 	href="index.php?action=checkout&period=currentyear">Bieżący rok</a></li>
				<li 	role="separator" 
						class="divider">
				</li>
				<li><a 	type="button" 
						data-toggle="modal" 
						data-target="#myModal">
					Niestandardowy okres</a>
				</li>
			  </ul>
			</div>
		</div>
	</div>
<!-- Modal -->
	<div id="myModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button 	type="button" 
							class="close" 
							data-dismiss="modal">
					&times;
				</button>
				<h4 class="modal-title">Wybierz zakres dat</h4>
			</div>
			<form 	role="form" 
					method="post" 
					action="">
				<div class="modal-body">
					<div class="form-group">
						<label for="custom_min" class="col-md-4">Data początkowa</label>
                            <div class="col-md-6">
								<input 	type="date" 
										class="form-control" 
										name="custom_min" 
										id="custom_min" 
										value="<?php echo($today); ?>">
							</div>
					</div>
					<div class="form-group">
						<label for="custom_max" class="col-md-4">Data końcowa</label>
                            <div class="col-md-6">
								<input 	type="date" 
										class="form-control" 
										name="custom_max" 
										id="custom_max" 
										value="<?php echo($today); ?>">
							</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="form-group">
						<input type="submit" name="submit" class="btn btn-success" value="Zatwierdź" >
					</div>
				</div>
			</form>
		</div>

	  </div>
	</div>
	<div class="row">
		<div class="col-lg-6 col-md-6">
			<div class="table-responsive">          
				<table class="table table-bordered table-hover">
					<thead>
						<tr class="success">
							<th colspan="5"><p class="table-heading">Przychody - zestawienie szczegółowe</p></th>
						</tr>
							<tr class="success">
							 <th class="text-center">#</th>
							 <th class="text-center">Data</th>
							 <th class="text-center">Kwota</th>
							 <th class="text-center">Kategoria</th>
							 <th class="text-center">Komentarz</th>
							</tr>
						</thead>
						<tbody>
						<?php 
							try{

								if(!$manager->dbo) {
									return SERVER_ERROR;
								}
								else {
									$manager->dbo->query("SET CHARSET utf8");
									$manager->dbo->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`"); 
										
									$user_id = $_SESSION['user_id'];

									$query = "SELECT * FROM Incomes i INNER JOIN incomes_category cat ON i.user_id = cat.user_id 
											  AND i.category_id = cat.id WHERE i.user_id = '$user_id' ORDER BY amount DESC";

										if(!$result = $manager->dbo->query($query)){
									    //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
									    return SERVER_ERROR;
										}

									if($result->num_rows > 0){
										$income_number = 1;
										$incomes_in_period = 0;
											
										while ($row = $result->fetch_row()){
											$current_inc_date = $row[4];
																								
											if(($current_inc_date>=$min_date)&&($current_inc_date<=$max_date)){	
																							
												echo '<tr class="success">';
												echo "<td>".$income_number."</td>";
												echo "<td>".$row[4]."</td>";
												echo "<td>".$row[3]." PLN"."</td>";
												echo "<td>".ucfirst($row[9])."</td>";
												echo "<td>".ucfirst($row[5])."</td>";
												echo "</tr>";
												$income_number++;
												$incomes_in_period++;
											}
										}
										if($incomes_in_period==0) {
											echo '<tr class="success">';
											echo '<td colspan="5">W wybranym okresie Użytkownik nie ma żadnych przychodów!</td>';
											echo "</tr>";
										}
									} 
									else {
										echo '<tr class="success">';
										echo '<td colspan="5">W wybranym okresie Użytkownik nie ma żadnych przychodów!</td>';
										echo "</tr>";
									}	
								}
							} catch (Exception $e){
								//echo 'Błąd: ' . $e->getMessage();
								exit('Aplikacja chwilowo niedostępna');	
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
			
		</div>
</div>

