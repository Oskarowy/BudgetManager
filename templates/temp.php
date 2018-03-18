<div class="container">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="table-responsive">          
						<table class="table table-bordered table-hover">
							<thead>
								<tr class="info">
									<th colspan="3"><p class="table-heading">Przychody w podziale na kategorie</p></th>
								</tr>
								<tr class="info">
								 <th class="text-center">#</th>
								 <th class="text-center">Kategoria</th>
								 <th class="text-center">Kwota</th>
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
										
										$query = "SELECT i.date, i.category_id, SUM(i.amount), cat.name FROM Incomes i INNER JOIN incomes_category cat ON i.user_id = cat.user_id AND i.category_id = cat.id WHERE i.user_id = '$user_id' AND i.date BETWEEN '$min_date' AND '$max_date' GROUP BY i.category_id ORDER BY SUM(i.amount) DESC";
											
										if(!$result = $manager->dbo->query($query)){
									    	//echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
									    	return SERVER_ERROR;
										}
											
										if($result->num_rows > 0){
											$income_number = 1;
											$incomes_in_period = 0;
											$incomes_sum = 0;
												
											while ($row = $result->fetch_row()){													
												echo '<tr class="info">';
												echo "<td>".$income_number."</td>";
												echo "<td>".ucfirst($row[3])."</td>";
												echo "<td>".$row['SUM(i.amount)']." PLN"."</td>";
												echo "</tr>";
												$income_number++;
												$incomes_in_period++;
												$_SESSION['balance']+=$row['SUM(i.amount)'];
												$incomes_sum +=$row['SUM(i.amount)'];
											}
											if($incomes_in_period==0) {
												echo '<tr class="info">';
												echo '<td colspan="3">W wybranym okresie Użytkownik nie ma żadnych przychodów!</td>';
												echo "</tr>";
											} else {
												echo '<tr class="info">';
												echo "<td colspan=\"2\"> <strong> SUMA PRZYCHODÓW </strong></td>";
												echo "<td> <strong>".$incomes_sum." PLN"."</strong></td>";
												echo "</tr>";
											}
										} else {
											echo '<tr class="info">';
											echo '<td colspan="3">W wybranym okresie Użytkownik nie ma żadnych przychodów!</td>';
											echo "</tr>";
										}
										
									}
										
									} catch (Exception $e){
								//echo 'Błąd: ' . $e->getMessage();
								exit('Aplikacja chwilowo niedostępna');	
							}
							?>
							</tbody>
							<thead>
								<tr class="warning">
									<th colspan="3"></th>
								</tr>
								<tr class="info">
									<th colspan="3"><p class="table-heading">Wydatki w podziale na kategorie</p></th>
								</tr>
								<tr class="info">
								 <th class="text-center">#</th>
								 <th class="text-center">Kategoria</th>
								 <th class="text-center">Kwota</th>
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
											
											$result = $connection->query("SELECT e.date, e.category_id, SUM(e.amount), cat.name FROM expenses AS e INNER JOIN expenses_category AS cat ON e.category_id = cat.id AND e.user_id = cat.user_id WHERE e.user_id = '$user_id' AND date BETWEEN '$min_date' AND '$max_date' GROUP BY e.category_id ORDER BY SUM(e.amount) DESC");
											
											if(!$result) throw new Exception($connection->error);
											
											$matched_expenses = $result->num_rows;
											if($matched_expenses>0) {
												$expense_number = 1;
												$expenses_in_period = 0;
												$expenses_sum = 0;
												$_SESSION['chart_data'] = array();
												
												while ($row = $result->fetch_assoc()){				
																													
														echo '<tr class="info">';
														echo "<td>".$expense_number."</td>";
														echo "<td>".ucfirst($row['name'])."</td>";
														echo "<td>".$row['SUM(e.amount)']." PLN"."</td>";
														echo "</tr>";
														$expense_number++;
														$expenses_in_period++;
														$_SESSION['balance']-=$row['SUM(e.amount)'];
														$expenses_sum+=$row['SUM(e.amount)'];
														array_push($_SESSION['chart_data'], ucfirst($row['name']), $row['SUM(e.amount)']);
												} $_SESSION['chart_size'] = (sizeof($_SESSION['chart_data']))/2;
												if($expenses_in_period==0) {
													echo '<tr class="info">';
													echo '<td colspan="3">W wybranym okresie Użytkownik nie ma żadnych przychodów!</td>';
													echo "</tr>";
												} else {
													echo '<tr class="info">';
														echo "<td colspan=\"2\"> <strong> SUMA WYDATKÓW </strong></td>";
														echo "<td> <strong>".$expenses_sum." PLN"."</strong></td>";
														echo "</tr>";
												}
											} else {
												echo '<tr class="info">';
												echo '<td colspan="3">W wybranym okresie Użytkownik nie ma żadnych przychodów!</td>';
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
			</div>
		</div>