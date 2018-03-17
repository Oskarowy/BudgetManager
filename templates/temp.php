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
							require_once "connect.php";
								
								mysqli_report(MYSQLI_REPORT_STRICT);
								
								try{
									$connection = new mysqli($host, $db_user, $db_password, $db_name);
									if($connection->connect_errno!=0){
										throw new Exception(mysqli_connect_errno());
									} else {
										$connection->query("SET CHARSET utf8");
										$connection->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`"); 
										$user_id = $_SESSION['user_id'];
										
										$result = $connection->query("SELECT * FROM expenses e INNER JOIN expenses_category cat INNER JOIN payment_methods pay ON e.category_id = cat.id AND e.user_id = cat.user_id AND e.user_id = pay.user_id AND e.payment_id = pay.id WHERE e.user_id = '$user_id' ORDER BY amount DESC");
										
										if(!$result) throw new Exception($connection->error);
										
										$matched_expenses = $result->num_rows;
										if($matched_expenses>0) {
											
											$expense_number = 1;
											$expenses_in_period = 0;
																						
											while ($row = $result->fetch_assoc()){
												$current_exp_date = $row['date'];
																								
												if(($current_exp_date>=$min_date)&&($current_exp_date<=$max_date)){	
																										
													echo '<tr class="danger">';
													echo "<td>".$expense_number."</td>";
													echo "<td>".$row['date']."</td>";
													echo "<td>".$row['amount']." PLN"."</td>";
													echo "<td>".ucfirst($row['payname'])."</td>";
													echo "<td>".ucfirst($row['name'])."</td>";
													echo "<td>".ucfirst($row['comment'])."</td>";
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
										$connection->close();
									}
									
								} catch(Exception $e){
									echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie! </span>';
									echo '<br/> Informacja developerska: '.$e;
								}
						?>
						</tbody>
					</table>
				</div>
			</div>