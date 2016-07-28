<?php
include "utils.php";
echo "{";
if (isset($_GET['getsubjects'])){
	getsubjects($conn);
}elseif (isset($_GET['verifiemail'])) {
	if($_GET['verifiemail']==""){
		SendError("Please type your Email");
	}
	$sql = "SELECT `Email`,`Password` FROM user";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		if(strlen($_GET['verifiemail']) == similar_text($row["Email"], $_GET['verifiemail'])){
			SendError("Email Already In Use");
		}
	}
	if($_GET['name']==""){
		SendError("Please type your Name");
	}
	if($_GET['subs']==""){
		SendError("Please enter your Subjects");
	}
	if($_GET['dob']==""){
		SendError("Please enter your Birth Day");
	}
	if($_GET['pass']==""){
		SendError("Please your Password");
	}
	$sql = "INSERT INTO `user` (`Email`,`Password`,`Name`,`dob`,`IsMale`) VALUES('".$_GET['verifiemail']."','".$_GET['pass']."','".$_GET['name']."','".$_GET['dob']."','".$_GET['gen']."')";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$expect = explode(',',$_GET['subs']);
	for ($i=0; $i < COUNT($expect); $i++) { 
		$sql = "INSERT INTO `subjects_list` (`User_name`,`SID`) VALUES('".$_GET['verifiemail']."','".$expect[$i]."')";
		$result = $conn->query($sql);
		if($conn->error){
			SendError($conn->error);
		}
	}
}else{
	if(isset($_GET['uname'])){
		if(isset($_GET['upass'])){
			$sql = "SELECT `Email`,`Password` FROM user";
			$result = $conn->query($sql);
			$error = "ok";
			$haveaccount = 0;
			while($row = $result->fetch_assoc()) {
				if(strlen($_GET['uname']) == similar_text($row["Email"], $_GET['uname'])){
					$haveaccount = 1;
					//SendError(similar_text($row["Email"], $_GET['uname']));
					if(strlen($_GET['upass']) == similar_text($row["Password"], $_GET['upass']) && strlen($row['Password']) == similar_text($row["Password"], $_GET['upass']) ){
						setonline($conn);
						Loged($conn);
						break;
					}else{	
						SendError("Password is Incorrect");
					}
				}	
			}
			if($haveaccount==0){
				SendError("Username is Incorrect");
			}
		}else{
			SendError("Password is Missing");	
		}
	}else{
		SendError("E-mail is Missing");
	}
}
echo "}";
function setonline($conn){
	$sql = "UPDATE `user` SET `lastonline`= CURRENT_TIMESTAMP WHERE `Email`='".$_GET['uname']."'";
	//log::d("sql",$sql);
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
}
function Loged($conn){
	if(isset($_GET['getmsg'])){
		GetMeg($conn);
	}elseif (isset($_GET['chat'])) {
		Chat($conn);
	}elseif (isset($_GET['getuser'])){
		UserSeach($conn);
	}elseif (isset($_GET['makechat'])){
		Makechat($conn);
	}elseif (isset($_GET['user'])){
		GetUser($conn);
	}elseif (isset($_GET['delete'])){
		deletemeg($conn);
	}elseif (isset($_GET['deletec'])){
		deletecomment($conn);
	}elseif (isset($_GET['deletet'])){
		deletethread($conn);
	}elseif (isset($_GET['editc'])){
		editcomment($conn);
	}elseif (isset($_GET['editt'])){
		editthread($conn);
	}elseif (isset($_GET['dashborad'])){
		dashborad($conn);
	}elseif (isset($_GET['threads'])){
		threads($conn);
	}elseif (isset($_GET['comments'])){
		comments($conn);
	}elseif (isset($_GET['putcomment'])){
		putcomment($conn);
	}elseif (isset($_GET['userinfo'])){
		userinfo($conn);
	}elseif (isset($_GET['notification'])){
		notification($conn);
	}elseif (isset($_GET['save'])){
		settingsave($conn);
	}elseif (isset($_GET['Tfile'])){
		Tfile($conn);
	}elseif (isset($_GET['Tupload'])){
		Tupload($conn);
	}elseif (isset($_GET['profilepic'])){
		profilepic($conn);
	}elseif (isset($_GET['maketheread'])){
		maketheread($conn);
	}
}
function Tupload($conn){
	$sql= "SELECT * FROM `subjects` WHERE `SID`='".$_GET['Tupload']."'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$sub_data = $result->fetch_assoc();
	$target_dir = "docs/".$sub_data['Sname']."/";
	if(isset($_FILES["fileToUpload"])){
		$target_file = $target_dir . basename( $_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		if (file_exists($target_file)) {
			SendError("Sorry, File Already Existsed");
		}
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		} else {
			SendError("Sorry, there was an error uploading your file.");
		}
	}else{
		SendError("Sorry, there was an error uploading your file.");
	}
	
}
function Tfile($conn){
	$sql= "SELECT * FROM `subjects` WHERE `SID`='".$_GET['Tfile']."'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$sub_data = $result->fetch_assoc();
	$dir = "docs/".$sub_data['Sname'];
	if (is_dir($dir)){
		$dh  = scandir($dir);
		echo "\"Title\":\"".$sub_data['Sname']."\",";
		echo "\"files\":[";
		for ($i=0; $i < count($dh) ; $i++) { 
			if($dh[$i]!="." && $dh[$i]!=".."){
				echo "{";
				echo '"name":"'.$dh[$i].'",';
				echo '"path":"'.$dir.'/'.$dh[$i].'"';
				echo "}";
				echo ($i == (count($dh)-1))?"":",";
			}
		}
		echo "]";
	}else{
		mkdir($dir);
	}
	
}
function profilepic($conn){
	if(isset($_FILES["fileToUpload"])){
		$target_dir = "uploads/";
		$target_file = $target_dir . $_GET['uname'].".jpg";
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
			SendError("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
		}
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			updatepic($conn,$target_file);
			//SendError("The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
		} else {
			SendError("Sorry, there was an error uploading your file.");
		}
	}else{
		SendError("Sorry, there was an error uploading your file.");
	}
}
function updatepic($conn,$name){
	$me = $_GET['uname'];
	$sql= "UPDATE `user` SET `Profile_pic`='$name' WHERE `Email`='$me'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
}
function settingsave($conn){
	$name=$_GET['save'];
	$me = $_GET['uname']; 
	$old = $_GET['old']; 
	$new = $_GET['new']; 
	$sql= "UPDATE `user` SET `Name`='$name' WHERE `Email`='$me'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$sql= "UPDATE `user` SET `Password`='$new' WHERE `Email`='$me' AND `Password`='$old'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$oldsubs = "";
	$sql = "SELECT * FROM `subjects_list` Natural JOIN `subjects` JOIN  `USER` ON `User_name`=`Email` WHERE `Email`='$me' GROUP BY `SID` ";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$count = 0;
	while($user_data = $result->fetch_assoc()) {
		$count++;
		$oldsubs .= $user_data['SID'];
		if($count < $result->num_rows) $oldsubs .= ",";
	}
	if($oldsubs!=$_GET['newsubs']){
		$sql= "DELETE FROM `subjects_list` WHERE `User_name`='$me'";
		$result = $conn->query($sql);
		if($conn->error){
			SendError($conn->error);
		}
		$expect = explode(',',$_GET['newsubs']);
		for ($i=0; $i < COUNT($expect); $i++) { 
			$sql= "INSERT INTO `subjects_list` (`User_name`,`SID`) VALUES ('$me','".$expect[$i]."')";
			$result = $conn->query($sql);
			if($conn->error){
				SendError($conn->error);
			}
		}
	}
}
function getsubjects($conn){
	$notlike ="";
	$Teacher ="";
	if(isset($_GET['uname'])){
		$Teacher = "`Teacher` NOT LIKE '".$_GET['uname']."' AND";
	}
	$expect = explode(',',$_GET['exprct']);
	for ($i=0; $i < COUNT($expect); $i++) { 
		$notlike .=" AND `SID` NOT LIKE '".$expect[$i]."'";
	}
	$sql = "SELECT * FROM `subjects` WHERE ".$Teacher." `Sname` LIKE '".$_GET['getsubjects']."%'".$notlike;
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$JSON = "\"subs\":[";
	$Row_count = 0;
	while($user_data = $result->fetch_assoc()) {
		$JSON .="{";
		$Row_count++;
		$JSON .="\"SID\":\"".$user_data['SID']."\",";
		$JSON .="\"Sname\":\"".$user_data['Sname']."\",";
		$JSON .="\"Teacher\":\"".$user_data['Teacher']."\"";
		$JSON .="}";
		if($Row_count < $result->num_rows) $JSON .= ",";
	}
	$JSON .= "]";
	echo $JSON;
}
function editthread($conn){
	$tid = $_GET['editt'];
	$me = $_GET['uname'];
	$txt = $_GET['txt'];	
	$sql= "UPDATE `subjects_threads` SET `TText`='$txt' WHERE `TID`='$tid' AND `Started_User`='$me'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
}
function editcomment($conn){
	$cid = $_GET['editc'];
	$me = $_GET['uname'];
	$txt = $_GET['txt'];
	$sql= "UPDATE `subjects_threads_comments` SET `Comment`='$txt' WHERE `CID`='$cid' AND `Cuser`='$me'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
}
function deletethread($conn){
	$tid = $_GET['deletet'];
	$me = $_GET['uname'];
	$sql= "DELETE FROM `subjects_threads_comments` WHERE `TID`='$tid' ";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$sql= "DELETE FROM `subjects_threads` WHERE `TID`='$tid' AND `Started_User`='$me'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
}
function deletecomment($conn){
	$cid = $_GET['deletec'];
	$me = $_GET['uname'];
	$sql= "DELETE FROM `subjects_threads_comments` WHERE `CID`='$cid' AND `Cuser`='$me'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
}
function maketheread($conn){
	$sid = $_GET['sub'];
	$me = $_GET['uname'];
	$txt = $_GET['maketheread'];
	$my = new User_Info($conn,$me);
	$myname = $my->user_name;
	$sql= "SELECT * FROM `subjects_list` Natural JOIN `subjects` WHERE `SID`='$sid'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$subjects_list = $result->fetch_assoc();
	$useremaiil = $subjects_list['Teacher'];
	$subname = $subjects_list['Sname'];
	if($useremaiil != $me){
		$sql = "INSERT INTO `notification` (`Nuser`,`Ntype`,`Ndata`,`Ntxt`,`Nposted`) VALUES('$useremaiil','Tread Created','$sid','$myname Created new thread in $subname Subject','$me')";
		$result1 = $conn->query($sql);
		if($conn->error){
			SendError($conn->error);
		}
	}
	$sql= "SELECT * FROM `subjects_list` Natural JOIN `subjects` WHERE `SID`='$sid'";
	$result = $conn->query($sql);
	if($result->num_rows>0){
		while($subjects_list = $result->fetch_assoc()) {
			$useremaiil = $subjects_list['User_name'];
			$subname = $subjects_list['Sname'];
			if($useremaiil != $me){
				$sql = "INSERT INTO `notification` (`Nuser`,`Ntype`,`Ndata`,`Ntxt`,`Nposted`) VALUES('$useremaiil','Tread Created','$sid','$myname Created new thread in $subname Subject','$me')";
				$result1 = $conn->query($sql);
				if($conn->error){
					SendError($conn->error);
				}
			}
		}
	}
	$sql = "INSERT INTO `subjects_threads` (`SID`,`Started_User`,`TText`) VALUES('$sid','$me','$txt')";
	$result1 = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
}
function notification($conn){
	$myemail = $_GET['uname'];
	$sql = "SELECT * FROM `notification` JOIN `user` ON `Nposted`=`Email` WHERE `Nuser`='$myemail' ORDER BY `notification`.`Ntime` DESC ";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$JSON = "\"noti\":[";
	$Row_count = 0;
	while($user_data = $result->fetch_assoc()) {
		$JSON .="{";
		$Row_count++;
		$JSON .="\"NID\":\"".$user_data['NID']."\",";
		$JSON .="\"Ntxt\":\"".$user_data['Ntxt']."\",";
		$JSON .="\"Name\":\"".$user_data['Name']."\",";
		$JSON .="\"Email\":\"".$user_data['Email']."\",";
		$JSON .="\"Ntype\":\"".$user_data['Ntype']."\",";
		if($user_data['Ntype'] == "Commented"){
			$sql= "SELECT * FROM `subjects_threads_comments` WHERE `CID`='".$user_data['Ndata']."'";
			$re = $conn->query($sql);
			if($conn->error){
				SendError($conn->error);
			}
			$tid = $re->fetch_assoc();
			$JSON .="\"Nthread\":\"".$tid['TID']."\",";
		}
		$JSON .="\"Ndata\":\"".$user_data['Ndata']."\",";
		$JSON .="\"Profile_pic\":\"".$user_data['Profile_pic']."\",";
		$JSON .="\"Nstatus\":\"".$user_data['Nstatus']."\",";
		$JSON .="\"Link\":\"".urlencode(enc::encrypt($user_data['Email'],hex2bin(enc::$key),true))."\",";
		$JSON .="\"Ntime\":\"".$user_data['Ntime']."\"";
		$JSON .="}";
		if($Row_count < $result->num_rows) $JSON .= ",";
	}
	$JSON .= "]";
	echo $JSON;
}
function userinfo($conn){
	$myinfo = new User_Info($conn,$_GET['uname']);
	$format = '"name":"%1$s",
	"id":"%2$s",
	"pp":"%3$s",
	"gen":"%4$s",
	"age":"%5$s",
	"unreadmsg":"%6$s",
	"unreadnoti":"%7$s"
	';
	printf($format,$myinfo->user_name,$myinfo->user_id,$myinfo->user_profilepic,$myinfo->user_gender,$myinfo->user_age,$myinfo->user_unreadmsg,$myinfo->user_unreadnoti);
}
function putcomment($conn){

	$sql = "UPDATE `subjects_threads`
	SET `Nusers`= CASE `Nusers`
	WHEN '' THEN '".$_GET['uname']."'
	ELSE CONCAT(`Nusers`,',".$_GET['uname']."')
	END
	WHERE `TID`='".$_GET['putcomment']."'
	AND `Nusers` NOT LIKE '%".$_GET['uname']."%' 
	AND `Started_User` NOT LIKE '%".$_GET['uname']."%'
	";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$thread_id = $_GET['putcomment'];
	$me = $_GET['uname'];
	$sql = "SELECT * FROM `subjects_threads` WHERE `TID`='$thread_id'";
	$Nlist = $conn->query($sql);
	if($conn->error){

		SendError($conn->error);
	}
	$Ndata = $Nlist->fetch_assoc();
	$threadowner = $Ndata['Started_User'];
	$user = new User_Info($conn,$threadowner);
	$my = new User_Info($conn,$me);
	$username = $user->user_name;
	$myname = $my->user_name;
	$ctype = "Normal";
	$code = explode('[eq]',$_GET['txt']);
	if($code[0]=="[at]ano"){
		$ctype = "Unknown";
		$_GET['txt'] = $code[1];
		$myname = "Unknown";
		$me = "Unknown";
	}elseif ($code[0]=="[at]anopic"){
		$ctype = "Unknown";
		$_GET['txt'] = "<img src='".$code[1]."' alt='' width='200;' class='materialboxed'/>";

		$myname = "Unknown";
		$me = "Unknown";
	}elseif ($code[0]=="[at]pic"){
		$_GET['txt'] = "<img src='".$code[1]."' alt='' width='200;' class='materialboxed'/>";
	}
	$_GET['txt'] = txtchange2($_GET['txt']);
	if($threadowner != $me){
		$sql = "SELECT * FROM `notification` WHERE `Ntype`='Commented' AND `Ndata`='$thread_id' AND `Nuser`='$threadowner'";
		$lastnot = $conn->query($sql);
		if($conn->error){

			SendError($conn->error);
		}
		if(is_object($lastnot) && $lastnot->num_rows>0){
			$notification = $lastnot->fetch_assoc();
			$nid = $notification['NID'];
			$sql = "UPDATE `notification` SET `Ntime`=CURRENT_TIMESTAMP,`Ntxt`='$myname Commented on your thread',`Nstatus`='Unreaded',`Nposted`='$me' WHERE `NID`='$nid'";
			$result1 = $conn->query($sql);
			if($conn->error){

				SendError($conn->error);
			}
		}else{
			$sql = "INSERT INTO `notification` (`Nuser`,`Ntype`,`Ndata`,`Ntxt`,`Nposted`) VALUES('$threadowner','Commented','$thread_id','$myname Commented on your thread','$me')";
			$result1 = $conn->query($sql);
			if($conn->error){

				SendError($conn->error);
			}
		}
	}
	if($Ndata['Nusers']!=""){
		$list = explode(',',$Ndata['Nusers']);
		for ($i=0; $i < count($list) ; $i++) {
			if($list[$i] != $me ){
				$sql = "SELECT * FROM `notification` WHERE `Ntype`='Commented' AND `Ndata`='$thread_id' AND `Nuser`='$list[$i]'";
				$lastnot = $conn->query($sql);
				$hisher = ($my->user_gender == "Male")?"his":"her";
				$that  = ($me == $threadowner)?$hisher:$username." s";
				if(is_object($lastnot) && $lastnot->num_rows>0){
					$notification = $lastnot->fetch_assoc();
					$nid = $notification['NID'];
					$sql = "UPDATE `notification` SET `Ntime`=CURRENT_TIMESTAMP,`Ntxt`='$myname Commented on $that thread',`Nstatus`='Unreaded',`Nposted`='$me' WHERE `NID`='$nid'";
					$result1 = $conn->query($sql);
					if($conn->error){
						SendError($conn->error);
					}
				}else{
					$sql = "INSERT INTO `notification` (`Nuser`,`Ntype`,`Ndata`,`Ntxt`,`Nposted`) VALUES('$list[$i]','Commented','$thread_id','$myname Commented on $that thread','$me')";
					$result1 = $conn->query($sql);
					if($conn->error){

						SendError($conn->error);
					}
				}
			} 
		}
	}
	$sql = " INSERT INTO `subjects_threads_comments` (`TID`,`Cuser`,`Comment`,`Ctype`) VALUES('".$_GET['putcomment']."','".$_GET['uname']."','".$_GET['txt']."','".$ctype."')";
	$result1 = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
}
function comments($conn){
	$tid = $_GET['comments'];
	$sql = "UPDATE `notification` SET `Nstatus`='Readed' WHERE `Nuser`='".$_GET['uname']."' AND `Ndata`='$tid' AND `Ntype`='Commented'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$sql = "SELECT * FROM `subjects_threads_comments` JOIN `USER` ON `Cuser`=`Email` Natural JOIN `subjects_threads` WHERE `TID`='$tid' ORDER BY `subjects_threads_comments`.`CID` DESC LIMIT 20 ";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$sql = "SELECT * FROM `subjects_threads` WHERE `TID`='$tid'";
	$r = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$user_data = $r->fetch_assoc();
	$JSON = "\"Title\":\"".$user_data['TText']."\",";
	$JSON .= "\"Comments\":[";
	$Row_count = 0;
	while($user_data = $result->fetch_assoc()) {
		$JSON .="{";
		$Row_count++;
		$name = ($user_data['Ctype']=="Unknown")?"Unknown":$user_data['Name'];
		//$email = ($user_data['Ctype']=="Unknown")?"Unknown":$user_data['Cuser'];
		$email = $user_data['Cuser'];
		$user_data['Profile_pic'] = ($user_data['Ctype']=="Unknown")?"profilepic.png":$user_data['Profile_pic'];
		$JSON .="\"CID\":\"".$user_data['CID']."\",";
		$JSON .="\"Name\":\"".$name."\",";
		$JSON .="\"Email\":\"".$email."\",";
		$JSON .="\"Ctime\":\"".$user_data['Ctime']."\",";
		$JSON .="\"Profile_pic\":\"".$user_data['Profile_pic']."\",";
		$JSON .="\"Comment\":\"".$user_data['Comment']."\"";
		$JSON .="}";
		if($Row_count < $result->num_rows) $JSON .= ",";
	}
	$JSON .= "]";
	echo $JSON;
}
function threads($conn){
	$sid = $_GET['threads'];
	$sql = "UPDATE `notification` SET `Nstatus`='Readed' WHERE `Nuser`='".$_GET['uname']."' AND `Ndata`='$sid' AND `Ntype`='Tread Created'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$sql = "SELECT * FROM `subjects_threads` Natural JOIN `subjects` JOIN `USER` ON `Started_User`=`Email` WHERE `SID`='$sid' ORDER BY `TID` DESC LIMIT 20";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$user_data = $result->fetch_assoc();
	$JSON = "\"Title\":\"".$user_data['Sname']."\",";
	$result = $conn->query($sql);
	$JSON .= "\"Threads\":[";
	$Row_count = 0;
	while($user_data = $result->fetch_assoc()) {
		$JSON .="{";
		$Row_count++;
		$JSON .="\"TID\":\"".$user_data['TID']."\",";
		$JSON .="\"Name\":\"".$user_data['Name']."\",";
		$JSON .="\"Email\":\"".$user_data['Email']."\",";
		$JSON .="\"Posted_Date\":\"".$user_data['Posted_Date']."\",";
		$JSON .="\"Profile_pic\":\"".$user_data['Profile_pic']."\",";
		$JSON .="\"TText\":\"".$user_data['TText']."\"";
		$JSON .="}";
		if($Row_count < $result->num_rows) $JSON .= ",";
	}
	$JSON .= "]";
	echo $JSON;
}
function dashborad($conn){
	$myemail = $_GET['uname'];
	$sql = "SELECT * FROM `subjects_list` Natural JOIN `subjects` JOIN  `USER` ON `User_name`=`Email` WHERE `Teacher`='$myemail' or `Email`='$myemail' GROUP BY `SID` ";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$JSON = "\"Subjects\":[";
	$Row_count = 0;
	while($user_data = $result->fetch_assoc()) {
		$JSON .="{";
		$Row_count++;
		$JSON .="\"SID\":\"".$user_data['SID']."\",";
		$JSON .="\"Sname\":\"".$user_data['Sname']."\",";
		$JSON .="\"Teacher\":\"".$user_data['Teacher']."\"";
		$JSON .="}";
		if($Row_count < $result->num_rows) $JSON .= ",";
	}
	$JSON .= "]";
	echo $JSON;
}
function deletemeg($conn){
	$sql = "UPDATE `messagers`
	SET `deleted`= CASE `deleted`
	WHEN '' THEN '".$_GET['uname']."'
	ELSE CONCAT(`deleted`,',".$_GET['uname']."')
	END
	WHERE `mid`='".$_GET['delete']."' AND `deleted` NOT LIKE '%".$_GET['uname']."%'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	Sendurl("Index.php?chatwith=".urlencode(enc::encrypt($_GET['dc'],hex2bin(enc::$key),true))."");
}
function GetUser($conn){
	$myinfo = new User_Info($conn,enc::decrypt($_GET['user'],hex2bin(enc::$key), true));
	$count = 0;
	$chatid = 0;
	$name = enc::decrypt($_GET['user'],hex2bin(enc::$key), true);
	$sql = "SELECT * FROM `chats` WHERE `Gtype`='Normal' AND User_names='".$_GET['uname'].",".$name."'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$count += $result->num_rows;
	if($result->num_rows > 0){
		$chat_data = $result->fetch_assoc();
		$chatid = $chat_data['cid'];
	}
	$sql = "SELECT * FROM `chats` WHERE `Gtype`='Normal' AND User_names='".$name.",".$_GET['uname']."'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$count += $result->num_rows;
	if($result->num_rows > 0){
		$chat_data = $result->fetch_assoc();
		$chatid = $chat_data['cid'];
	}
	$link = ($count > 0)? ",\"link\":\"".myUrlEncode(enc::encrypt($chatid,hex2bin(enc::$key),true))."\"":"";
	$format = '"name":"%1$s",
	"id":"%2$s",
	"pp":"%3$s",
	"gen":"%4$s",
	"age":"%5$s"
	'.$link;
	printf($format,$myinfo->user_name,$myinfo->user_id,$myinfo->user_profilepic,$myinfo->user_gender,$myinfo->user_age);
}
function Makechat($conn){
	if ($_GET['chattype'] == "Normal"){
		$count = 0;
		$chatid = "";
		$name = str_replace($_GET['uname'],"",str_replace(",","",$_GET['makechat']));
		$sql = "SELECT * FROM `chats` WHERE `Gtype`='Normal' AND User_names='".$_GET['uname'].",".$name."'";
		$result = $conn->query($sql);
		if($conn->error){
			SendError($conn->error);
		}
		$count += $result->num_rows;
		if($result->num_rows > 0){
			$chat_data = $result->fetch_assoc();
			$chatid = $chat_data['cid'];
		}
		$sql = "SELECT * FROM `chats` WHERE `Gtype`='Normal' AND User_names='".$name.",".$_GET['uname']."'";
		$result = $conn->query($sql);
		if($conn->error){
			SendError($conn->error);
		}
		$count += $result->num_rows;
		if($result->num_rows > 0){
			$chat_data = $result->fetch_assoc();
			$chatid = $chat_data['cid'];
		}
		if($count > 0){
			Sendurl("Index.php?chatwith=".urlencode(enc::encrypt($chatid,hex2bin(enc::$key),true))."");
		}else{
			$id = gettableai($conn,"chats");
			$sql = "INSERT INTO `chats` (`Chat_name`,`User_names`,`Gtype`) VALUES ('NormalChat','".$_GET['uname'].",".$name."','Normal')";
			$result = $conn->query($sql);
			if($conn->error){
				SendError($conn->error);
			}
			$sql = " INSERT INTO `messagers` (`chatid`,`From_user`,`msg`) VALUES('".$id."','".$_GET['uname']."','Chat Created')";
			$result1 = $conn->query($sql);
			if($conn->error){
				SendError($conn->error);
			}
			Sendurl("Index.php?chatwith=".urlencode(enc::encrypt($id,hex2bin(enc::$key),true))."");
		}
	}else{
		$id = gettableai($conn,"chats");
		$sql = "INSERT INTO `chats` (`Chat_name`,`User_names`,`Gtype`) VALUES ('".$_GET['chatname']."','".$_GET['uname'].",".$_GET['makechat']."','Group')";
		$result = $conn->query($sql);
		if($conn->error){
			SendError($conn->error);
		}
		$sql = " INSERT INTO `messagers` (`chatid`,`From_user`,`msg`) VALUES('".$id."','".$_GET['uname']."','Chat Created')";
		$result1 = $conn->query($sql);
		if($conn->error){
			SendError($conn->error);
		}
		Sendurl("Index.php?chatwith=".urlencode(enc::encrypt($id,hex2bin(enc::$key),true))."");
	}
	
}
function gettableai($conn,$table){
	$sql = "SHOW TABLE STATUS WHERE name='".$table."'";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$chat_data = $result->fetch_assoc();
	return $chat_data['Auto_increment'];
}
function UserSeach($conn){
	$notlike ="";
	$expect = explode(',',$_GET['exprct']);
	for ($i=0; $i < COUNT($expect); $i++) { 
		$notlike .=" AND `Email` NOT LIKE '".$expect[$i]."'";
	}
	$sql = "SELECT * FROM `user` WHERE `Name` LIKE '".$_GET['getuser']."%' AND `Email` NOT LIKE '".$_GET['uname']."'".$notlike." LIMIT 10";
	$result = $conn->query($sql);
	if($conn->error){
		SendError($conn->error);
	}
	$JSON = "\"user\":[";
	$Row_count = 0;
	while($user_data = $result->fetch_assoc()) {
		$JSON .="{";
		$Row_count++;
		$JSON .="\"Name\":\"".$user_data['Name']."\",";
		$JSON .="\"Profile_pic\":\"".$user_data['Profile_pic']."\",";
		$JSON .="\"Email\":\"".$user_data['Email']."\",";
		$JSON .="\"lastonline\":\"".$user_data['lastonline']."\",";
		$JSON .="\"uid\":\"".$user_data['uid']."\"";
		$JSON .="}";
		if($Row_count < $result->num_rows) $JSON .= ",";
	}
	$JSON .= "]";
	echo $JSON;
}
function myUrlEncode($string) {
	$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
	$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
	return str_replace($entities, $replacements, urlencode($string));
}
function Chat($conn){
	if(isset($_GET['get'])){
		$chatid = $_GET['chat'];
		$sql = "SELECT * FROM `messagers` JOIN `user` ON `messagers`.`From_user`=`user`.`Email` WHERE `ChatID`='$chatid' AND `deleted` NOT LIKE '%".$_GET['uname']."%' ORDER BY `Time` DESC LIMIT ".$_GET['lim'];
		$result = $conn->query($sql);
		if($conn->error){
			SendError($conn->error);
		}
		$sql = "SELECT * FROM `chats` WHERE `cid`='$chatid'";
		$chatresult = $conn->query($sql);
		if($conn->error){
			SendError($conn->error);
		}
		$chats = $chatresult->fetch_assoc();
		$JSON = "\"chat\":[";
		$Row_count = 0;
		$normalchatuser = ""; 
		while($chat_data = $result->fetch_assoc()) {
			$Row_count++;
			$Deleverd = "false";
			if (strlen($chats['User_names']) <= strlen($chat_data['msg_delivered'])) {
				$Deleverd = "true";
			}
			$JSON .="{";
			$JSON .="\"Name\":\"".$chat_data['Name']."\",";
			if($chat_data['Email'] != $_GET['uname'] ){
				$normalchatuser = $chat_data['Name'];
			}
			$JSON .="\"Mid\":\"".$chat_data['mid']."\",";
			$JSON .="\"Profile_pic\":\"".$chat_data['Profile_pic']."\",";
			$JSON .="\"Message\":\"".$chat_data['Msg']."\",";
			$JSON .="\"Time\":\"".$chat_data['Time']."\",";
			$JSON .="\"Email\":\"".$chat_data['Email']."\",";
			$JSON .="\"Deleverd\":\"".$Deleverd."\"";
			$JSON .=",\"Link\":\"".urlencode(enc::encrypt($chat_data['Email'],hex2bin(enc::$key),true))."\"";
			$JSON .="}";
			if($Row_count < $result->num_rows) $JSON .= ",";
			if ($chat_data['msg_delivered'] == str_replace($_GET['uname'],"",$chat_data['msg_delivered']) ) {
				if($chat_data['msg_delivered']==""){
					$fill = $_GET['uname'];
				}else{
					$fill = $chat_data['msg_delivered'].",".$_GET['uname'];
				}

				$sql = " UPDATE `messagers` SET `msg_delivered`='$fill' WHERE `mid`='".$chat_data['mid']."' ";
				$result1 = $conn->query($sql);
				if($conn->error){
					SendError($conn->error);
				}
			}
			
		}
		$JSON .= "],";
		$Title = ($chats['Gtype']=='Group')?$chats['Chat_name']:$normalchatuser; 
		$JSON .= "\"Title\":\"$Title\"";
		echo $JSON;
	}else if(isset($_GET['send'])){
		$code = explode('[eq]',$_GET['msg']);
		if($code[0]=="[at]pic"){
			$sql = "UPDATE `chats` SET `Chat_image` = '".$code[1]."' WHERE `chats`.`cid`='".$_GET['chat']."' AND `User_names` LIKE '".$_GET['uname']."%' AND `Gtype`='Group'";
			$result1 = $conn->query($sql);
			if($conn->error){
				SendError($conn->error);
			}
			$sql = "SELECT * FROM `chats` WHERE `chats`.`cid`='".$_GET['chat']."' AND `User_names` LIKE '".$_GET['uname']."%' AND `Gtype`='Group'";
			$result1 = $conn->query($sql);
			if (is_object($result1) && $result1->num_rows > 0) {
				$sql = " INSERT INTO `messagers` (`chatid`,`From_user`,`msg`,`msg_delivered`) VALUES('".$_GET['chat']."','".$_GET['uname']."','Group Picture Updated','".$_GET['uname']."')";
				$result1 = $conn->query($sql);
				if($conn->error){
					SendError($conn->error);
				}
				SendError("Group Picture Updated");
			}else{
				SendError("Error Picture Updating");
			}
		}elseif ($code[0]=="[at]name") {
			$sql = "UPDATE `chats` SET `Chat_name` = '".$code[1]."' WHERE `chats`.`cid`='".$_GET['chat']."' AND `User_names` LIKE '".$_GET['uname']."%' AND `Gtype`='Group'";
			$result1 = $conn->query($sql);
			if($conn->error){
				SendError($conn->error);
			}
			$sql = "SELECT * FROM `chats` WHERE `chats`.`cid`='".$_GET['chat']."' AND `User_names` LIKE '".$_GET['uname']."%' AND `Gtype`='Group'";
			$result1 = $conn->query($sql);
			if (is_object($result1) && $result1->num_rows > 0) {
				$sql = " INSERT INTO `messagers` (`chatid`,`From_user`,`msg`,`msg_delivered`) VALUES('".$_GET['chat']."','".$_GET['uname']."','Group Title Updated','".$_GET['uname']."')";
				$result1 = $conn->query($sql);
				if($conn->error){
					SendError($conn->error);
				}
				SendError("Group Title Updated");
			}else{
				SendError("Error Title Updating");
			}
		}	
		$sql = " INSERT INTO `messagers` (`chatid`,`From_user`,`msg`,`msg_delivered`) VALUES('".$_GET['chat']."','".$_GET['uname']."','".$_GET['msg']."','".$_GET['uname']."')";
		$result1 = $conn->query($sql);
		if($conn->error){
			SendError($conn->error);
		}
	}
}
function GetMeg($conn){
	$sql = "SELECT * FROM (SELECT * FROM `messagers` JOIN `user` ON `messagers`.`From_user`=`user`.`Email` JOIN `chats` ON `chats`.`cid`=`messagers`.`ChatID` ORDER BY `messagers`.`Time` DESC ) t1 WHERE `User_names` LIKE '%".$_GET['uname']."%' AND `deleted` NOT LIKE '%".$_GET['uname']."%' GROUP BY `t1`.`ChatID` ORDER BY `t1`.`Time` DESC";
	//log::d("sql",$sql);
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		echo '"msg":[';
		$count = 0;
		while($row = $result->fetch_assoc()) {
			$count++;
			$format = '{
				"Id":"%1$s",
				"Charid":"%2$s",
				"Email":"%3$s",
				"Name":"%4$s",
				"Time":"%5$s",
				"Message":"%6$s",
				"Profile_pic":"%7$s",
				"Unreaded":"%8$s",
				"Link":"%9$s",
				"lastonline":"%10$s"
			}';
			$last = ($count == $result->num_rows)?"":",";
			if($row['Gtype']=="Normal"){
				$name = str_replace($_GET['uname'],"",str_replace(",","",$row['User_names']));
				$sql = "SELECT * FROM `user` WHERE `Email`='".$name."'";
				$result2 = $conn->query($sql);
				$back = $result2->fetch_assoc();
				$name = $back["Name"];
				$profile_pic = $back['Profile_pic'];
				$howsend = ($row['Email'] == $_GET['uname'])?"(Me):":"";
				$lastonline = $back['lastonline'];
			}else{
				$name = $row['Chat_name'];
				$profile_pic = $row['Chat_image'];
				$sql = "SELECT Name FROM `user` WHERE `Email`='".$row['Email']."'";
				$result2 = $conn->query($sql);
				$back = $result2->fetch_assoc();
				$Sname = explode(" ",$back["Name"]);
				$howsend = ($row['Email'] == $_GET['uname'])?"(Me):":"(".$Sname[0]."):";
				$lastonline="";
			}
			$sql = "SELECT COUNT(mid) AS `Unreaded` FROM `messagers` WHERE `ChatID`='".$row['cid']."' AND `msg_delivered` NOT LIKE '%".$_GET['uname']."%'";
			$result2 = $conn->query($sql);
			$back = $result2->fetch_assoc(); 
			$Unreaded = $back["Unreaded"];
			printf($format.$last, $row['mid'], $row['cid'],$row['Email'],$name,$row['Time'],$howsend.$row['Msg'],$profile_pic,$Unreaded,urlencode(enc::encrypt($row['cid'],hex2bin(enc::$key),true)),$lastonline); 
		}
		echo "]";
	} else {

	}
}
function SendError($error) {
	die("\"error\":\"".$error."\"}");
}
function Sendurl($error) {
	die("\"url\":\"".$error."\"}");
}
?>
