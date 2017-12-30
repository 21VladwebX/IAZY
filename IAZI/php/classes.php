<?php
 class DataBase {
    protected static $_instance; 
	private function __clone() {}
    private function __wakeup() {} 
    private function __construct() {}

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;   
        }
        return self::$_instance;
    }
	
	public function connect ($host,$db) {		
		mysql_select_db("$db", mysql_connect("$host"));// Открывает соединение с сервером MySQL db - BD which we will use
		mysql_query("SET NAMES 'utf-8'");
		return true;
	}
	
	function insert($table, $values, $columns = null){
		$sql = "INSERT INTO ".$table; 
		if($columns != null)  
			$sql .= " (".$columns.")"; 
		$numValues = count($values);			//count - Подсчитывает количество элементов массива или что-то в объекте
		for($i = 0; $i < $numValues; $i++){ 
			if(is_string($values[$i])) 
				$values[$i] = "\"".$values[$i]."\"";
			if(is_null($values[$i]))
				$values[$i] = "null";
		} 
		$values = implode(",",$values); 		//implode - Объединяет элементы массива в строку
		$sql .= " VALUES (".$values.")";
		if (mysql_query($sql))
			return true;
		return false;
	}
	
	function select($what, $from, $where = null, $order = null){
		$sql = "SELECT $what FROM $from";
		if($where != null)
			$sql .= " WHERE ".$where;
		if($order != null)
			$sql .= " ORDER BY ".$order;		//ORDER BY сортирует по возростание какой то столбец
		mysql_query($sql) or die("Invalid query: " . mysql_error());
		$query = mysql_query($sql);
		if($query){
			$rows = mysql_num_rows($query); 	//mysql_num_rows -- Возвращает количество рядов результата запроса
			for($i = 0; $i < $rows; $i++){
				$result = mysql_fetch_assoc($query);  //mysql_fetch_assoc --  Обрабатывает ряд результата запроса и возвращает ассоциативный массив.
				$key = array_keys($result);		//array_keys — Возвращает все или некоторое подмножество ключей массива
				$numKeys = count ($key);
				for($x = 0; $x < $numKeys; $x++){
					$fetched[$i][$key[$x]] = $result[$key[$x]];
				}
			}
			return $fetched;
		} 
		return false;
	}
	
	function __destruct (){
		mysql_close();
		return true;
	}
}    


class LogFile{
	protected static $_instance; 
	private $dir = "";
	private $status = false;
	private $arr = array();
	private $type_prot = array();
    private function __construct() {}
	private function __clone() {}
    private function __wakeup() {}  


    public static function getInstance(){
        if (self::$_instance === null){
            self::$_instance = new self;   
        }
        return self::$_instance;
    }
		
	 function getTypeProt(){
		 return $this->type_prot;
	}
	
	function check(){
		if(file_exists($this->dir))		//file_exists — Проверяет наличие указанного файла или каталога
			return true;
		return false;
	}
	
	// tmp_name - string, dir - string, name - string
	function download($tmp_name, $dir, $name){
		move_uploaded_file($tmp_name, $dir.$name);
		$this->dir = $dir.$name;
		return $this->check();
	}
	
	function open(){
		if($this->check()){
			$this->status = fopen($this->dir, "r");
			return true;
		}
		return false;
	}
	
	function streamStatus(){
		if(!feof($this->status))
			return false;
		return true;
	}
	//$whatWeСompare - array
	function getStr($whatWeСompare){
		$str = fgets($this->status);
		if($str){
			$arr_tmp = explode('","', $str);
			if (in_array($arr_tmp[6], $whatWeСompare) ){	
				$str = implode(",",$arr_tmp); 
				$a = explode("\"",$arr_tmp[7]);		
				$arr_tmp[7] = $a[0];
				$whatWeСompare_count = count($whatWeСompare); 
				for($i = 0; $i < $whatWeСompare_count; $i++){
				 $this->counter($arr_tmp[6],$whatWeСompare[$i],$whatWeСompare_count);
				}
			}
			$array = [null,$arr_tmp[1],$arr_tmp[2],$arr_tmp[4],$arr_tmp[5],$arr_tmp[6],$arr_tmp[7]];						
			return $array;
		}
		return false;
	}
	
	function counter($str,$whatWeСompare,$whatWeСompare_count){				
		$compare = '';	
		if(stristr($str,$whatWeСompare)){
			$compare = (string)($whatWeСompare);
			$this->type_prot[$compare] +=1;
		}	
	}
		

	//name - array, value - array
	function session($name,$value){ 				
		$i = 0; 
		if(count($name) == count($value)){
			foreach( $value as  $b){
				sort ($value);
				$this->arr[$i] = $b;
				$i +=1;
			}
			session_start();
			for($x = 0; $x < count($name); $x++){
				$a = (string) $name[$x];
				$_SESSION["$a"] = $this->arr[$x];
			}
			return true;
		}
	}
}



interface Product{
	public function sample($data,$protocols,$whatWeCompare);
	public function build_table($countType,$protocols,$whatWeCompare,$howMany);
}


class Prototype{
    private $product;

    public function __construct(Product $product){
        $this->product = $product;
    }

    public function getProduct(){
        return clone $this->product;
    }
}


class Tables implements Product{
	private $val = array();
	private $prot = array();
	private $compare = array();

	
	function sample($data,$protocols,$whatWeCompare){
		
		for($i = 0; $i < count($whatWeCompare); $i++) {
			foreach($whatWeCompare as $value){				
				$this->compare[$i] = $whatWeCompare[$i];				
			}
		}

		for($i = 0; $i < count($data); $i += 1 ){
			for($x = 0; $x < count($protocols); $x += 1 ){
				for($z = 0; $z < count($this->compare); $z+=2){
					 if(in_array($protocols[$x],$data[$i])){
						if($protocols[$x] == (string)$data[$i]["protocol"]){
							 $this->prot[$i][$protocols[$x]][$this->compare[$z]] = $data[$i][$this->compare[$z]];
							 $this->prot[$i][$protocols[$x]][$this->compare[$z+1]] = $data[$i][$this->compare[$z+1]];
						}							
					}
				}
			}
		}
	}
	//whatWeCompare - array
	function build_table($countType,$protocols,$whatWeCompare,$howMany){ 
		$a = 0;
		$numProt = array();
		echo '<table class = "inside" border = "1" style = "border : 1px solid black;">';
		for($x = 0; $x < count ($protocols); $x ++){
			for($z = 0; $z < count ($whatWeCompare); $z += 2){
				for ($i = 0; $i < count($this->prot); $i ++){
					if($this->prot[$i][$protocols[$x]][$whatWeCompare[$z]]){
						if($numProt[$protocols[$x]] < $howMany){
							$a = $this->prot[$i][$protocols[$x]][$whatWeCompare[$z]];
							$b = $this->prot[$i][$protocols[$x]][$whatWeCompare[$z+1]];
							$c = $countType[$protocols[$x]];
							echo <<<END
								<tr><th  colspan = "2" align = "center">$protocols[$x]</th></tr>
								<tr><th> Source</th><th>Destination</th></tr>
								<tr><td>$a</td>
								<td>$b</td></tr>
								<tr><td>$b</td>
								<td>$a</td></tr>
								<tr><td colspan = "2" >Количество протоколов</td></tr>
								<td colspan = "2">$c</td></tr>
END;
							// echo '
								// <tr><th  colspan = "2" align = "center">'.$protocols[$x].'</th></tr>
								// <tr><th> Source</th><th>Destination</th></tr>
								// <tr><td>'.$this->prot[$i][$protocols[$x]][$whatWeCompare[$z]].'</td>
								// <td>'.$this->prot[$i][$protocols[$x]][$whatWeCompare[$z+1]].'</td></tr>
								// <tr><td>'.$this->prot[$i][$protocols[$x]][$whatWeCompare[$z+1]].'</td>
								// <td>'.$this->prot[$i][$protocols[$x]][$whatWeCompare[$z]].'</td></tr>
								// <tr><td colspan = "2" >Количество протоколов</td></tr>
								// <td colspan = "2">'.$countType[$protocols[$x]].'</td></tr>';
								$numProt[$protocols[$x]] +=1;	
						}			
					}
				}
			}
		}
	echo'</table>';
	}	
}

?>