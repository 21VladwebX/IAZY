<?php // content="text/plain; charset=utf-8"
date_default_timezone_set('Europe/Kiev');
require_once ('jpgraph-4.0.1/src/jpgraph.php');  
require_once ('jpgraph-4.0.1/src/jpgraph_pie.php');  
require_once ('jpgraph-4.0.1/src/jpgraph_pie3d.php'); 
session_start(); 
$tcp = $_SESSION['tc'];  
$arp = $_SESSION['ar']; 
$dns = $_SESSION['dn'];  
$http = $_SESSION['ht']; 

// Статистика количества протоколов
$data = array($arp, $tcp, $dns , $http);  
$legends = array(  
	'ARP',   
	'TCP',  
    'DNS',   
    'HTTP'     
	);  
  
    // Создаём график  
$graph = new PieGraph(600, 450);  
$graph->SetShadow();  
    // Заголовок графика  
$graph->title->Set('Статистика протоколов ');  
$graph->title->SetFont(FF_VERDANA, FS_BOLD, 14);   
  
// Расположение "Легенды" (в процентах/100)  
$graph->legend->Pos(0.1, 0.2);  
  
// Создаём круговую диаграмму 3D  
$p1 = new PiePlot3d($data);  
  
// Центр круга (в процентах/100)  
$p1->SetCenter(0.45, 0.5);  
  
// Угол наклона диаграммы  
$p1->SetAngle(30);  
  
// Шрифт для подписей  
$p1->value->SetFont(FF_ARIAL, FS_NORMAL, 12);  
  
// Подписи для сегментов диаграммы  
$p1->SetLegends($legends);  
  
// Присоединяем диаграмму к графику  
  
$graph->Add($p1);  
// Выводим график  
  
$graph->Stroke(); 
 // session_destroy();
?>


