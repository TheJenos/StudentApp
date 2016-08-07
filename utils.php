<?php
include 'SQL.php';

function txtchange($String){
        $txt = $String;
	//$txt = htmlspecialchars($String);
	//$txt = str_replace(".","[dt]",$txt);l
	$txt = str_replace("!","[em]",$txt);
	$txt = str_replace("\n","<br>",$txt);
	$txt = str_replace("%","[pm]",$txt);
	$txt = str_replace("<","&lt;",$txt);
	$txt = str_replace(">","&gt;",$txt);
	$txt = str_replace("@","[at]",$txt);
	$txt = str_replace("'","[sc]",$txt);
	$txt = str_replace("=","[eq]",$txt);
	$txt = str_replace("?","[qm]",$txt);
	$txt = str_replace("#","[ht]",$txt);
	$txt = str_replace('$',"[ds]",$txt);
	$txt = str_replace('"',"[dc]",$txt);
	return $txt;
}
function htmlcode($String){
    $txt = $String;
	$expect1 = explode(' ',$txt);
	for ($i=0; $i < COUNT($expect1); $i++) {
		$expect2 = explode(':&gt;',$expect1[$i]);
		if(isset($expect2[1])){
			$pretxt = "";
			$prehtml = "";
			$mid = "" ;
			$posthtml = "";
			for ($j=0; $j < COUNT($expect2); $j++) {
					if($j == COUNT($expect2)-1){
						$pretxt .= $expect2[$j];
						$mid = $expect2[$j];
					}else{
						$pretxt .= $expect2[$j].":&gt;";
						$prehtml .= "<".$expect2[$j].">";
						$posthtml .= "</".$expect2[(COUNT($expect2)-2)-$j].">";
					}
					
			}
			$txt = str_replace($pretxt,$prehtml.$mid.$posthtml,$txt);
		}
	}
	return $txt;
}
function txtchange2($String){
	$txt = $String;
	//$txt = str_replace(".","[dt]",$txt);
	$txt = str_replace("!","[em]",$txt);
	$txt = str_replace("\n","<br>",$txt);
	$txt = str_replace("%","[pm]",$txt);
	$txt = str_replace("@","[at]",$txt);
	$txt = str_replace("'","[sc]",$txt);
	$txt = str_replace("=","[eq]",$txt);
	$txt = str_replace("?","[qm]",$txt);
	$txt = str_replace("#","[ht]",$txt);
	$txt = str_replace('"',"[dc]",$txt);
	$txt = str_replace('$',"[ds]",$txt);
	return $txt;
}
function baktxt($String){
	$txt = $String;
	$txt = str_replace("[pm]","%",$txt);
	$txt = str_replace("[em]","!",$txt);
	$txt = str_replace("[sc]","'",$txt);
	$txt = str_replace("[ht]","#",$txt);
	$txt = str_replace("[at]","@",$txt);
	$txt = str_replace("[eq]","=",$txt);
	$txt = str_replace("[qm]","?",$txt);
	$txt = str_replace("[dc]",'"',$txt);
	$txt = str_replace("[ds]",'$',$txt);
	return $txt;
}
foreach($_GET as $key => $value){
	$_GET[$key] = txtchange($value);
}
foreach($_POST as $key => $value){
	$_POST[$key] = txtchange($value);
}	
class User_Info{

	public $user_name;
	public $user_id;
	public $user_password;
	public $user_profilepic;
	public $user_age;
	public $user_gender;
	public $user_unreadmsg;
	public $user_unreadnoti;

	function __construct($conn,$user_email){
		$sql_libs = new sql_libs($conn);
		$result = $sql_libs->get_data_extra("user","YEAR(CURRENT_TIMESTAMP) - YEAR(dob) - (RIGHT(CURRENT_TIMESTAMP, 5) < RIGHT(dob, 5)) as Age ","Email='$user_email'");
		$this->user_name = $result['Name'];
		$this->user_id = $result['uid'];
		$this->user_password = $result['Password'];
		$this->user_profilepic = $result['Profile_pic'];
		$this->user_age = $result['Age'];
		$this->user_gender = ($result['IsMale']=="1")?"Male":"Female";
		$sql = "SELECT * FROM `messagers` JOIN `chats` ON `messagers`.`chatid`=`chats`.`cid` WHERE `User_names` LIKE '%$user_email%' AND `msg_delivered` NOT LIKE '%$user_email%'";
		$result = $conn->query($sql);
		if($conn->error){
			echo "<h1>".$conn->error."</h1>";
		}
		$this->user_unreadmsg = $result->num_rows;
		$sql = "SELECT * FROM `notification` WHERE `Nuser`='$user_email' AND `Nstatus`='Unreaded'";
		$result = $conn->query($sql);
		if($conn->error){
			echo "<h1>".$conn->error."</h1>";
		}
		$this->user_unreadnoti = $result->num_rows;
	}
}
class log{
	public static function d($filename,$log){
		$file = "$filename.log";
		$f = fopen($file, 'a');
		fwrite($f, $log . "\n");
		fclose($f);
	}
}
class sql_libs{

	private $conn ;

	function __construct($conn){
		$this->conn = $conn;
	}
	function get_data($select,$table_name,$where){
		$sql = "SELECT $select FROM `".$table_name."` WHERE ".$where;
		log::d("sql",$sql);
		if($this->if_exit_data($table_name,$where)){
			$result = $this->conn->query($sql);
			if(is_object($result)){
				return $result->fetch_assoc();
			}
			return NULL;
		}

	}
	function get_data_extra($table_name,$extra,$where){
		if($this->if_exit_data($table_name,$where)){
			$sql = "SELECT *,$extra FROM `".$table_name."` WHERE ".$where;
			$result = $this->conn->query($sql);
			if(is_object($result)){
				return $result->fetch_assoc();
			}
			return NULL;
		}

	}
	function if_exit_data($table_name,$where){
		$sql = "SELECT * FROM `".$table_name."` WHERE ".$where;
		$result = $this->conn->query($sql);
		if(is_object($result) && count($result->fetch_assoc())>0){
			return true;
		}
		return false;
	}
	function If_exit_table($table_name){
		$sql = "SHOW TABLES LIKE '".$table_name."'";
		$result = $this->conn->query($sql);
		if(is_object($result) && count($result->fetch_assoc())>0){
			return true;
		}else{
			return false;
		}
	}
}
?>