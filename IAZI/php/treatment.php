<?php 
include ("classes.php");
ob_start();
$uploaddir = "../uploads/";
$machining = LogFile::getInstance();
$db = DataBase::getInstance();
$prototypeFactory = new Prototype(new Tables());
$firstTable = $prototypeFactory->getProduct();

$db->connect("localhost", "my_bd");

if($machining->download($_FILES["LogFile"]["tmp_name"], $uploaddir, $_FILES["LogFile"]["name"])){
	$machining->open();
	while(!$machining->streamStatus()){		
		$db->insert("packet", $machining->getStr(["TCP", "ARP","DNS","HTTP"]));	
	}
}
else echo "Файл не загружен!";

$data = $db->select("protocol, source, destination ","packet");
unset($db);

$firstTable->sample($data,["TCP","ARP","DNS","HTTP"],["source","destination"]);
		
$buffer = $firstTable->build_table($machining->getTypeProt(),["TCP","ARP","DNS","HTTP"],["source","destination"],3);

$machining->session(["dn","tc","ht","ar"],$machining->getTypeProt());


$buffer = ob_get_contents();
ob_end_clean();
echo json_encode($buffer);
exit;
?>