<?php if(!isset($manager)) die(); 

    $date = getdate();
    $d = $date['mday'];
    $m = $date['mon'];
    $y = $date['year'];
    if($d<10) $d = '0'.$d;
    if($m<10) $m = '0'.$m;
    $today = $y.'-'.$m.'-'.$d;

if(isset($_GET['type'])) {
	$type = $_GET['type'];

	if($type == 'expense') require_once (__DIR__ . '/../templates/expenseForm.php');
	if($type == 'income') require_once (__DIR__ . '/../templates/incomeForm.php');
}
?>


