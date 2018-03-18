
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
							try{
								if(!$manager->dbo) {
									return SERVER_ERROR;
								}
								else {
									$manager->dbo->query("SET CHARSET utf8");
									$manager->dbo->query("SET NAMES `utf8` COLLATE `utf8_polish_ci`"); 
									$user_id = $_SESSION['user_id'];

									$query = "SELECT e.date, e.category_id, SUM(e.amount), cat.name FROM expenses AS e INNER JOIN expenses_category AS cat ON e.category_id = cat.id AND e.user_id = cat.user_id WHERE e.user_id = '$user_id' AND date BETWEEN '$min_date' AND '$max_date' GROUP BY e.category_id ORDER BY SUM(e.amount) DESC";
											
									if(!$result = $manager->dbo->query($query)){
										//echo 'Wystąpił błąd: nieprawidłowe zapytanie...';
										return SERVER_ERROR;
									}	
											
									if($result->num_rows > 0){
										$expense_number = 1;
										$expenses_in_period = 0;
										$expenses_sum = 0;
										$_SESSION['chart_data'] = array();
												
										while ($row = $result->fetch_row()){				
																													
											echo '<tr class="info">';
											echo "<td>".$expense_number."</td>";
											echo "<td>".ucfirst($row[3])."</td>";
											echo "<td>".$row[2]." PLN"."</td>";
											echo "</tr>";
											$expense_number++;
											$expenses_in_period++;
											$_SESSION['balance']-=$row[2];
											$expenses_sum+=$row[2];
											array_push($_SESSION['chart_data'], ucfirst($row[3]), $row[2]);
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
								}
							}  catch (Exception $e){
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