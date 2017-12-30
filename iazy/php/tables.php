<?php 
$req = false;
ob_start(); //! Включение буферизации вывода

error_reporting(-1);
header('Content-Type: text/html; charset=utf-8');
$connection = new mysqli("localhost","root","mysql","my_bd");
$connection->query("SET NAMES 'utf-8'");;
$query = "SELECT source,destination,protocol FROM packet ";
$result = $connection->query($query);
$val = array();
$prot = array();
$id = array(0,0,0,0);
$id_dr = array(0,0,0,0);
session_start();
// while($row = mysqli_fetch_array($result)) 
$tcp = $_SESSION['d'];
$arp = $_SESSION['s'];
$dns = $_SESSION['f'];
$http = $_SESSION['n'];


while ($row = mysqli_fetch_array($result))
{
	//var_dump($row);
// echo "source:".$row['source']."<br>";
// echo "destination:".$row ['destination']."<br>";
	switch ($row['protocol'])	{
	case 'TCP':
		if($id[0]!= 1){
			$prot[0] = 'TCP';
			$val[0] = (string)$row['source'];
			$val[1] = (string)$row['destination'];
			$id[0]++;
		
		}
		break;
	case 'ARP':
		if($id[1]!= 1){
			$prot[1] = 'ARP';
			$val[2] = $row['source'];
			$val[3] = $row['destination'];
			$id[1]++;
		}
		break;
	case 'DNS':
		if($id[2]!= 1){
			$prot[2] = 'DNS';
			$val[4] = $row['source'];
			$val[5] = $row['destination'];
			$id[2]++;
		}
		break;
	case 'HTTP':
		if($id[3]!= 1){
			$prot[3] = 'HTTP';
			$val[6] = $row['source'];
			$val[7] = $row['destination'];
			$id[3]++;
		}
		break;
	}
}
sort($prot);

// print_r($prot);
// var_dump($prot);
// echo "<br />";
// var_dump($val);
// echo count($prot);
echo'<table border = 2 align = "right " >';

for ($i = 0; $i < count($prot); $i++){
	switch ($prot[$i]){
	case 'TCP':	
		echo '<tr><th colspan = "2" align="center">TCP</th></tr>';
		echo '<tr> <th> Source</th>';
		echo '<th> Destination</th> </tr>';
		echo '<tr> <td>'.$val[0].'</td>';
		echo '<td>'.$val[1].'</td> </tr>';
		echo '<tr> <td>'.$val[1].'</td>';
		echo '<td>'.$val[0].'</td> </tr>';	
		echo '<tr> <td colspan = "2" > Количество протоколов </td> </tr>';
		echo '<td colspan = "2" >'.$tcp.'</td> </tr>';
		break;		
		// $val[0] = $row['source'];
		// $val[1] = $row['destination'];

	
	case 'ARP':	
		echo '<tr> <th colspan = "2" > ARP </th> </tr>';
		echo '<tr> <th> Source</th>';
		echo '<th> Destination</th> </tr>';
		echo '<tr> <td>'.$val[2].'</td>';
		echo '<td>'.$val[3].'</td> </tr>';
		echo '<tr> <td>'.$val[3].'</td>';
		echo '<td>'.$val[2].'</td> </tr>';	
		echo '<tr> <td colspan = "2" > Количество протоколов </td> </tr>';
		echo '<td colspan = "2" >'.$arp.'</td> </tr>';
		break;		
		// $val[2] = $row['source'];
		// $val[3] = $row['destination'];
	
		
	case 'DNS':	
		echo '<tr> <th colspan = "2" > DNS </th> </tr>';
		echo '<tr> <th> Source</th>';
		echo '<th> Destination</th> </tr>';
		echo '<tr> <td>'.$val[4].'</td>';
		echo '<td>'.$val[5].'</td> </tr>';
		echo '<tr> <td>'.$val[5].'</td>';
		echo '<td>'.$val[4].'</td> </tr>';
		echo '<tr> <td colspan = "2" > Количество протоколов </td> </tr>';
		echo '<td colspan = "2" >'.$dns.'</td> </tr>';
		break;
		// $val[4] = $row['source'];
		// $val[5] = $row['destination'];

	
	case 'HTTP':
		echo '<tr> <th colspan = "2" > HTTP </th> </tr>';
		echo '<tr> <th> Source</th>';
		echo '<th> Destination</th> </tr>';
		echo '<tr> <td>'.$val[6].'</td>';
		echo '<td>'.$val[7].'</td> </tr>';
		echo '<tr> <td>'.$val[7].'</td>';
		echo '<td>'.$val[6].'</td> </tr>';
		echo '<tr> <td colspan = "2" > Количество протоколов </td> </tr>';
		echo '<td colspan = "2" >'.$http.'</td> </tr>';
		break;
		// $val[6] = $row['source'];
		// $val[7] = $row['destination'];
	}
}
echo'</table>';
$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);
?>