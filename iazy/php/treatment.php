<?php
//$req = false;
//ob_start(); //! Включение буферизации вывода
$uploaddir = '../uploads/';
$uploadfile = basename($_FILES['LogFile']['name']);
//echo '<pre>'; /*Текст в элементе pre обычно отображается шрифтом с фиксированной шириной, а также он сохраняет пробелы и переносы строк.*/

if (move_uploaded_file($_FILES['LogFile']['tmp_name'], $uploaddir.$uploadfile))
{
	echo "\n";
} 
else
{
	echo $_FILES['LogFile']['error'];
	echo "-\n";
}

$arr = array();
$arr_tmp = array();
$s = 0;
$d = 0;
$f = 0;
$n = 0;

$fp = fopen($uploaddir.$uploadfile,'r');

if ($fp)	
{
	$mysqli = new mysqli("localhost","root","mysql","my_bd");
	$mysqli->query("SET NAMES 'utf-8'");
	
	while (!feof($fp)) /*feof - Проверяет, достигнут ли конец файла*/
	{
		$mytext = fgets($fp);
		$arr = explode("/n", $mytext);
		$q = count($arr);
		
		for($i = 0; $i < $q; $i++)
		{
			$arr_tmp = explode('","', $arr[$i]);
			
			//var_dump($arr1);
			/*
			$prot_arr = array("ARP", "DNS");
			
			if (in_array($arr_tmp[6], $prot_arr) )
				$mysqli->query("INSERT INTO `packet`(`start_time`, `current_time`, `source`, `destination`, `protocol`, `info`) VALUES
				('".$arr_tmp[1]."','".$arr_tmp[2]."','".$arr_tmp[4]."','".$arr_tmp[5]."','".$arr_tmp[6]."','".$arr_tmp[7]."');");*/
			
			switch ($arr_tmp[6])
			{
			case "ARP":
				$mysqli->query("INSERT INTO `packet`(`start_time`, `current_time`, `source`, `destination`, `protocol`, `info`) VALUES
				('".$arr_tmp[1]."','".$arr_tmp[2]."','".$arr_tmp[4]."','".$arr_tmp[5]."','".$arr_tmp[6]."','".$arr_tmp[7]."');");
				$s++;
				break;
		
			case "TCP":
				$mysqli->query("INSERT INTO `packet`(`start_time`, `current_time`, `source`, `destination`, `protocol`, `info`) VALUES
				('".$arr_tmp[1]."','".$arr_tmp[2]."','".$arr_tmp[4]."','".$arr_tmp[5]."','".$arr_tmp[6]."','".$arr_tmp[7]."');");
				$d++;
				break;

			case "DNS":
				$mysqli->query("INSERT INTO `packet`(`start_time`, `current_time`, `source`, `destination`, `protocol`, `info`) VALUES
				('".$arr_tmp[1]."','".$arr_tmp[2]."','".$arr_tmp[4]."','".$arr_tmp[5]."','".$arr_tmp[6]."','".$arr_tmp[7]."');");
				$f++;
				break;
				
			case "HTTP":
				$mysqli->query("INSERT INTO `packet`(`start_time`, `current_time`, `source`, `destination`, `protocol`, `info`) VALUES
				('".$arr_tmp[1]."','".$arr_tmp[2]."','".$arr_tmp[4]."','".$arr_tmp[5]."','".$arr_tmp[6]."','".$arr_tmp[7]."');");
				$n++;
				break;
			}			
		}
	}
	$mysqli->close();
}	
$summa = $d + $s + $f + $n;		
$t = ($d/$summa)*100;
$a = ($s/$summa)*100;
$ds = ($f/$summa)*100;
$h = 100 - ($t + $a + $ds);

$tcp = floor($t);	
$arp = round($a,1);
//$dns = round($d,3);
$http = round($h,1);	
$dns = 100 - ($http + $tcp + $arp);
//Grpah работает tолько с целыми числами !!!!!!!!

session_start();
$_SESSION['tc'] = $tcp;
$_SESSION['ar'] = $arp;
$_SESSION['dn'] = $dns;
$_SESSION['ht'] = $http;

$_SESSION['d'] = $d;
$_SESSION['s'] = $s;
$_SESSION['f'] = $f;
$_SESSION['n'] = $n;

//$tr = true;

$tr = true;


// session_destroy();
fclose($fp);
 
//$req = ob_get_contents();
//ob_end_clean();
//echo json_encode($req);

echo json_encode($tr);
?>
