<div class="col-lg-6 col-md-6">
	<div class="table-responsive">          
		<table class="table table-bordered table-hover">
			<thead>
				<tr class="danger">
					<th colspan="6"><p class="table-heading">Wydatki - zestawienie szczegółowe</p></th>
				</tr>
					<tr class="danger">
					 <th class="text-center">#</th>
					 <th class="text-center">Data</th>
					 <th class="text-center">Kwota</th>
					 <th class="text-center">Sposób płatności</th>
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

									$query = "SELECT * FROM Expenses e INNER JOIN expenses_category cat INNER JOIN payment_methods pay ON e.category_id = cat.id AND e.user_id = cat.user_id AND e.user_id = pay.user_id AND e.payment_id = pay.id WHERE e.user_id = '$user_id' ORDER BY amount DESC";
										
									if(!$result = $manager->dbo->query($query)){
									    //echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
									    return SERVER_ERROR;
										}
										
									if($result->num_rows > 0){
										$expense_number = 1;
										$expenses_in_period = 0;
																						
										while ($row = $result->fetch_row()){
											$current_exp_date = $row[5];
																								
											if(($current_exp_date>=$min_date)&&($current_exp_date<=$max_date)){	
																										
												echo '<tr class="danger">';
												echo "<td>".$expense_number."</td>";
												echo "<td>".$row[5]."</td>";
												echo "<td>".$row[4]." PLN"."</td>";
												echo "<td>".ucfirst($row[14])."</td>";
												echo "<td>".ucfirst($row[10])."</td>";
												echo "<td>".ucfirst($row[6])."</td>";
												echo "</tr>";
												$expense_number++;
												$expenses_in_period++;
											}
										} 
											if($expenses_in_period==0) {
												echo '<tr class="danger">';
												echo '<td colspan="6">W wybranym okresie Użytkownik nie ma żadnych wydatków!</td>';
												echo "</tr>";
											}
										} else {
											echo '<tr class="danger">';
											echo '<td colspan="6">W wybranym okresie Użytkownik nie ma żadnych wydatków!</td>';
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