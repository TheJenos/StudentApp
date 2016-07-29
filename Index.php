<?php
include "utils.php";	
if(isset($_POST['usernam'])){
	$_SESSION['uname'] = $_POST['usernam'];
	$_SESSION['upass'] = $_POST['userpass'];
	setcookie("uname", $_POST['usernam'], time() + (86400 * 14), "/");
	setcookie("upass", $_POST['userpass'], time() + (86400 * 14), "/");
	header('Location: Index.php');
}
if(!isset($_SESSION['uname'])){
	if(isset($_COOKIE['uname'])){

		if(isset($_COOKIE['upass'])){
			$sql = "SELECT `Email`,`Password` FROM user";
			$result = $conn->query($sql);
			$have = 0;
			while($row = $result->fetch_assoc()) {
				
				if($row["Email"] == $_COOKIE['uname']){
					$have = 1;
					if($row["Password"] == $_COOKIE['upass']){
						$_SESSION['uname'] = $_COOKIE['uname'];
						$_SESSION['upass'] = $_COOKIE['upass'];
					}else{
						setcookie("uname", null, time() - 3600);
						setcookie("upass", null, time() - 3600);
					}
				}
				
			}
			if($have==0){
				setcookie("uname", "", time() - 3600,"/");
				setcookie("upass", "", time() - 3600,"/");
				session_destroy();
					//header('Location: Login.php');
			}
		}
	}
}
if(!isset($_SESSION['uname']) && !isset($_COOKIE['uname'])){
	header('Location: Login.php');
	exit;
}
if(isset($_GET['logout'])){
	setcookie("uname", "", time() - 3600,"/");
	setcookie("upass", "", time() - 3600,"/");
	session_destroy();
	header('Location: Login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
		<!--Import Google Icon Font>
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"-->
		<!--Import materialize.css-->
		<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
		
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<link rel="mask-icon" sizes="any" href="/icon.svg" color="#3b5998">
		<meta name="theme-color" content="#212121">
		<style type="text/css">
			#right_msg {
				padding-left: auto !important;
				padding-right: 72px !important;
				text-align: right !important;
			}
			#right_msg > a > img {
				left: auto !important; 
				right: 15px !important;
			}
			.collection-item{
				border-bottom: 1px solid #e0e0e0 !important;
			}
			body { 
				background-image: url('login.png');
				background-repeat: no-repeat;
				background-attachment: fixed;
			}
			.side-nav a {
				float: none;
				line-height: 64px !important; 
			}
			.active a{
				color:white;
			}
			.cyan a{
				color:white;
			}
			.card-panel {
				padding-bottom:20px !important;
			}
			.side-nav li a{
				padding-bottom: 60px !important;
				border-bottom: 1px solid #e0e0e0 !important;
			}
			progress {
				width: 400px;
				height: 14px;
				margin: 20px auto;
				display: block;
				/* Important Thing */
				-webkit-appearance: none;
				border: none;
			}

			/* All good till now. Now we'll style the background */
			progress::-webkit-progress-bar {
				background: black;
				border-radius: 50px;
				padding: 2px;
				box-shadow: 0 1px 0px 0 rgba(255, 255, 255, 0.2);
			}

			/* Now the value part */
			progress::-webkit-progress-value {
				border-radius: 50px;
				box-shadow: inset 0 1px 1px 0 rgba(255, 255, 255, 0.4);
				background:
				-webkit-linear-gradient(45deg, transparent, transparent 33%, rgba(0, 0, 0, 0.1) 33%, rgba(0, 0, 0, 0.1) 66%, transparent 66%),
				-webkit-linear-gradient(top, rgba(255, 255, 255, 0.25), rgba(0, 0, 0, 0.2)),
				-webkit-linear-gradient(left, #ba7448, #c4672d);

				/* Looks great, now animating it */
				background-size: 25px 14px, 100% 100%, 100% 100%;
				-webkit-animation: move 5s linear 0 infinite;
			}

			/* That's it! Now let's try creating a new stripe pattern and animate it using animation and keyframes properties  */

			@-webkit-keyframes move {
				0% {background-position: 0px 0px, 0 0, 0 0}
				100% {background-position: -100px 0px, 0 0, 0 0}
			}
			textarea.materialize-textarea {
				overflow-y: hidden;
				padding: .8rem 0 0.6rem 0;
				resize: none;
				min-height: 3rem;
			}
			/* Prefix-free was creating issues with the animation */
		</style>
		<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="js/jquery.timeago.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>
		<script src="emojify.js"></script>
		<link href="emojify.css" rel="stylesheet" type="text/css">
		<script type="text/javascript">
			var msglim = 15;
			function baktxt($String) {
				$txt = $String;
				for(count = 0; count < $String.length; count++){
					$txt = $txt.replace("[pm]","%");
					$txt = $txt.replace("[br]","<br>");
					$txt = $txt.replace("[at]","@");
					$txt = $txt.replace("[em]","!");
					$txt = $txt.replace("[sc]","'");
					$txt = $txt.replace("[eq]","=");
					$txt = $txt.replace("[qm]","?");
					$txt = $txt.replace("[dc]",'"');
				}
				return $txt;
			}
			function newlines($String) {
				for(count = 0; count < $String.length; count++){
					$String = $String.replace("\n","[br]");
					$String = $String.replace("?","[qm]");
					$String = $String.replace("!","[em]");
					$String = $String.replace("%","[pm]");
					$String = $String.replace("@","[at]");
					$String = $String.replace("'","[sc]");
					$String = $String.replace("=","[eq]");
					$String = $String.replace("?","[qm]");
					$String = $String.replace('"',"[dc]");
				}
				return $String;
			}
			function moremsg() {
				msglim+=15;
			}
		</script>
		<style>
			.emoji { width: 1.5em; height: 1.5em; display: inline-block; margin-bottom: -.25em; background-size: 1.5em; }
		</style>
	</head>
	<nav>
		<div class="nav-wrapper grey darken-4">
			<a href="index.php" class="brand-logo center" style="font-size: 1.7rem;" >BookLess</a>
			<ul id="slide-out" class="side-nav collection" style="width: 270px;">
				<?php $myinfo = new User_Info($conn,$_SESSION['uname']) ?>
				<li class="collection-item avatar">
					<img src="<?php echo $myinfo->user_profilepic; ?>" alt="" class="circle">
					<span class="title" style="color:black;" >Name : <?php echo baktxt($myinfo->user_name); ?></span>
					<p style="color:black;"> Age : <?php echo $myinfo->user_age; ?><br>
						Gender : <?php echo $myinfo->user_gender; ?>
					</p>
				</li>
				<li><a href="Index.php">Home</a></li>
				<li><span class="new badge msg" style="margin-top: 16px;line-height: 30px;display:none;" ></span><a href="?chat">Messages</a></li>
				<li><span class="new badge noti" style="margin-top: 16px;line-height: 30px;display:none;" ></span><a href="?notifi">Notifications</a></li>
				<li><a href="?setting">Settings</a></li>
				<li><a class="modal-trigger" href="#modal1">Start New Chat</a></li>
			</ul>
			<ul class=" side-nav fixed collection" style="width: 300px;" >
				<li class="collection-item avatar">
					<img src="<?php echo $myinfo->user_profilepic; ?>" alt="" class="circle">
					<span class="title" style="color:black;" >Name : <?php echo baktxt($myinfo->user_name); ?></span>
					<p style="color:black;"> Age : <?php echo $myinfo->user_age; ?><br>
						Gender : <?php echo $myinfo->user_gender; ?>
					</p>
				</li>
				<li><a href="Index.php">Home</a></li>
				<li><span class="new badge msg" style="margin-top: 16px;line-height: 30px;display:none;" ></span><a href="?chat">Messages</a></li>
				<li><span class="new badge noti" style="margin-top: 16px;line-height: 30px;display:none;" ></span><a href="?notifi">Notifications</a></li>
				<li><a href="?setting">Settings</a></li>
				<li><a class="modal-trigger" href="#modal1">Start New Chat</a></li>
				<li><a href="?logout">Logout</a></li>
				<!-- Modal Trigger -->


			</ul>
			<a href="#" data-activates="slide-out" id="sidebar" class="button-collapse left" style=" margin-left: 10px; " ><i class="material-icons">more_vert</i></a>
			<a href="?logout" class="button-collapse right" style=" margin-right: 10px;"  ><i class="material-icons">settings_power</i></a>
			<script type="text/javascript">
				function userinfo() {
					$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&userinfo=", function(result){
						if(typeof result.error !=='undefined'){
							Materialize.toast(result.error, 4000);
							window.location.href = "Index.php?logout";
						}else{
							if(result.unreadmsg>0){
								$('.msg').show();
								$('.msg').html(result.unreadmsg);
							}else{
								$('.msg').hide();
							}
							if(result.unreadnoti>0){
								$('.noti').show();
								$('.noti').html(result.unreadnoti);
							}else{
								$('.noti').hide();
							}
						}
					}).fail(function(d) {
						Materialize.toast("Connection Problem", 4000);
					});
				}
				userinfo();
				var msg_interval = setInterval(userinfo, 3000);
			</script>
		</div>
	</nav>
	<body>
		<div id="modal1" class="modal modal-fixed-footer">
			<div class="modal-content">
				<h4>Start New Chat</h4>
				<div class="row">
					<div class="input-field col s8">
						<i class="material-icons prefix" id="icon">account_circle</i>
						<input id="last_name" type="text" class="validate " onkeyup="search(this)" >
						<label for="last_name">Name</label>
						<input id="email" type="hidden">
						<div id="result">
						</div>
					</div>
					<div class="input-field col s4">
						<select onchange="groupon(this)" >
							<option value="Normal">  Normal</option>
							<option value="Group">  Group</option>
						</select>
						<label>Chat Type</label>
					</div>
				</div>
				<div class="row" id="group">
					<div class="row">
						<div class="input-field col s12">
							<input id="chat_name" type="text" class="validate">
							<label for="chat_name">Chat Name</label>
						</div>
					</div>	
					<div class="row">
						<div class=" col s12">
							<label>Chat Group List</label>
							<div id="addlist" > 
							</div>

						</div>
					</div>
				</div>
				<script>
					$chattype="Normal";
					$('#group').hide();
					$("#modal1").animate({height:'260px'},"slow");
					function userlist(div) {
						var result = [];
						var chips = div.children();
						for (var i=0, iLen=chips.length; i<iLen; i++) {
							result.push($(chips[i]).attr('id'));
						}
						return result
					}
					function groupon(d){
						if(d.value == "Group"){
							$('#group').show();
							$("#icon").html('account_circle');
							$chattype="Group";
							$("#last_name").val("");
							$("#result").html("");
							$("#modal1").animate({height:'500px'},"slow");
						}else{
							$('#group').hide();
							$chattype="Normal";
							$("#addlist").html("");
							$("#modal1").animate({height:'260px'},"slow");
						}
						$("#last_name").val("");
					}
					function add(p,e,n){
						if($chattype == "Normal"){
							$("#result").html("");
							$("#last_name").val(n);
							$("#email").val(e);
							$("#icon").html('<img src="'+p+'" alt="" width="32px" class="circle"/>');
						}else{
							$("#result").html("");
							$("#last_name").val("");
							$("#addlist").append('<div class="chip" id="'+e+'" ><img src="'+p+'" alt="Contact Person">'+n+'<i class="close material-icons">close</i></div>');
						}
					}
					function search(d){
						if (d.value != "") {
							var exprct = userlist($("#addlist")); 
							$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&getuser="+$("#last_name").val()+"&exprct="+exprct, function(result){
								if(typeof result.error !=='undefined'){
									Materialize.toast(result.error, 4000);
								}else{
									if(result.user.length >0){
										$("#result").html("");
										$html="";
										for (var i =  0; i < result.user.length  ; i++) {
											$html += "<img src=\""+result.user[i].Profile_pic+"\" class=\"circle\" width=\"32px\"  ><a onclick=\"add('"+result.user[i].Profile_pic+"','"+result.user[i].Email+"','"+baktxt(result.user[i].Name)+"')\" style='margin-top: 7px;margin-left: 5px;position: absolute;'>"+baktxt(result.user[i].Name)+"</a></br>";
										}
										$("#result").append($html);
									}
								}
							}).fail(function(d) {
								Materialize.toast("Connection Problem", 4000);
							});
						}else{
							$("#result").html("");
							$("#icon").html('account_circle');
						}
					}
					function StartChat(){
						var chatlist = "";
						var chatname = "";
						if($chattype == "Normal"){
							chatlist = $("#email").val();
						}else{
							chatname = $("#chat_name").val();
							chatlist = userlist($("#addlist")); 
						}
						$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&makechat="+chatlist+"&chattype="+$chattype+"&chatname="+chatname, function(result){
							if(typeof result.error !=='undefined'){
								Materialize.toast(result.error, 4000);
							}else if(typeof result.url !=='undefined'){
								window.location.href = result.url;
							}else{

							}
						}).fail(function(d) {
							Materialize.toast("Connection Problem", 4000);
						});
					}
				</script>
			</div>
			<div class="modal-footer">
				<a href="#!" class="waves-effect waves-green btn-flat " onclick="StartChat()">Start Chat</a>
				<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat" >Cancel</a>
			</div>
		</div>
		<div id="modal2" class="modal modal-fixed-footer">
			<div class="modal-content">
				<div id="contents">
				</div>
			</div>
			<div class="modal-footer">
				<div id="chatbutton">

				</div>
				<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat" >Close</a>
			</div>
		</div>
		<div id="md1" class="modal">
			<div class="modal-content">
				<h4>Please Confirm</h4>
				<p>Are you sure to proceed?</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="waves-effect modal-close waves-red btn-flat" >Cancel</a>
				<a href="#" class="waves-effect waves-green btn-flat" id="md1_YesBtn">Yes</a>
			</div>
		</div>
		<div id="e1" class="modal">
			<div class="modal-content">
				<h4>Edit Your Text</h4>
				<div class="row">
					<div class="input-field col s12">
						<textarea id="e1_txt"  class="materialize-textarea" ></textarea>
						<label for="chat_name">New Text</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="waves-effect modal-close waves-red btn-flat" >Cancel</a>
				<a href="#" class="waves-effect waves-green btn-flat" id="e1_YesBtn">Edit</a>
			</div>
		</div>
		<div style="width:50%;margin-bottom:0px;"  id="login-page" class="row" >
			<div id="backcard" class="col s12 z-depth-6 card-panel " style="margin-top:0px;" >
				<?php
				if(isset($_GET['chatwith'])){
					?>
					<ul class="collection" id="msg_list" >

					</ul>
					<div clss="row">
						<div class="col s9">
							<label id="cmtlab" for="chattxt">Message</label>
							<input  id="chattxt" style="" onkeypress="return runScript(event)">
							<div id="resultg"  style="">
							</div>
							<div id="resultgadd" style="">
							</div>
							<input Type="hidden" id="ulist">
						</div>
						<div class="col s3">
							<button class="btn waves-effect waves-light" id="send" name="action">Send
								<i class="material-icons right">send</i>
							</button>
						</div>
					</div>
					<script>
						$('#chattxt').keyup(function(){
							var t = $("#chattxt").val();
							var code = t.split("=");
							if(code[0]=="@name"){
								$('#cmtlab').html("Group Title Updating");
							}else if(code[0]=="@pic") {
								$('#cmtlab').html("Group Picture Updating");
							}else if(code[0]=="@add") {
								$('#cmtlab').html("Group Add User");
								if(code[1]!=""){
									var list = userlist($("#resultgadd")); 
									$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&getuser="+code[1]+"&exprct="+$("#ulist").val()+","+list, function(result){
										if(typeof result.error !=='undefined'){
											Materialize.toast(result.error, 4000);
										}else{
											if(result.user.length >0){
												$("#resultg").html("");
												$html="";
												for (var i =  0; i < result.user.length  ; i++) {
													$html += "<img src=\""+result.user[i].Profile_pic+"\" class=\"circle\" width=\"32px\"  ><a onclick=\"gaddremo('"+result.user[i].Profile_pic+"','"+result.user[i].Email+"','"+baktxt(result.user[i].Name)+"')\" style='margin-top: 7px;margin-left: 5px;position: absolute;'>"+baktxt(result.user[i].Name)+"</a></br>";
												}
												$("#resultg").append($html);
											}
											
										}
									}).fail(function(d) {
										Materialize.toast("Connection Problem", 4000);
									});
								}else{
									$("#resultg").html("");
								}
								
							}else if(code[0]=="@kick") {
								$('#cmtlab').html("Group Kick User");
								if(code[1]!=""){
									var list = userlist($("#resultgadd")); 
									$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&getuser="+code[1]+"&in="+$("#ulist").val()+"&exprct="+list, function(result){
										if(typeof result.error !=='undefined'){
											Materialize.toast(result.error, 4000);
										}else{
											if(result.user.length >0){
												$("#resultg").html("");
												$html="";
												for (var i =  0; i < result.user.length  ; i++) {
													$html += "<img src=\""+result.user[i].Profile_pic+"\" class=\"circle\" width=\"32px\"  ><a onclick=\"gremo('"+result.user[i].Profile_pic+"','"+result.user[i].Email+"','"+baktxt(result.user[i].Name)+"')\" style='margin-top: 7px;margin-left: 5px;position: absolute;'>"+baktxt(result.user[i].Name)+"</a></br>";
												}
												$("#resultg").append($html);
											}
											
										}
									}).fail(function(d) {
										Materialize.toast("Connection Problem", 4000);
									});
								}else{
									$("#resultg").html("");
								}
							}else{
								$('#cmtlab').html("Message");
								$('#resultg').html("");
								$('#resultgadd').html("");

							}
						});
						function gaddremo(p,e,n){
							$("#resultg").html("");
							$('#chattxt').val("@add=");
							$html = '<div class="chip" id="'+e+'" ><img src="'+p+'" alt="Contact Person">'+n+'<i class="close material-icons">close</i></div>';
							$("#resultgadd").append($html);
						}
						function gremo(p,e,n){
							$("#resultg").html("");
							$('#chattxt').val("@kick=");
							$html = '<div class="chip" id="'+e+'" ><img src="'+p+'" alt="Contact Person">'+n+'<i class="close material-icons">close</i></div>';
							$("#resultgadd").append($html);
						}
					</script>					
					<?php
				}else if(isset($_GET['chat'])){
					?>
					<ul class="collection" id="msg_list" >

					</ul>

					<?php
				}else if(isset($_GET['setting'])){
					?>
					<p></p>
					<form class="col s12" id="sinup" onsubmit="return validateForm();" action="" method="post">
						<div class="row">
							<label for="icon_prefix">Name</label>
							<div class="input-field col s12 l12">
								<input id="settingname"  name="sign_name" value="<?php echo baktxt($myinfo->user_name); ?>" type="text" class="validate">
							</div>
						</div>
						<div class="row margin">
							<label for="password">Password</label>
							<div class="input-field col s12 l12">
								<input id="old_pass" name="userpass" type="password">
								<label for="password">Old Password</label>
							</div>
							
							<div class="input-field col s12 l12">
								<input id="new_pass" name="userpass" type="password">
								<label for="password">Password</label>
							</div>
						</div>
						<div class="row">
							<label for="last_name">Subjects</label>
							<div class="input-field col s12 l12">
								<input id="last_name1" type="text" class="validate " >
								<input id="email" type="hidden">
								<div id="results"  style="">
								</div>
								<div id="resultadd" style="">
									<?php
									$myemail = $_SESSION['uname'];
									$sql = "SELECT * FROM `subjects_list` Natural JOIN `subjects` JOIN  `user` ON `User_name`=`Email` WHERE `Email`='$myemail' GROUP BY `SID` ";
									$result = $conn->query($sql);
									if($conn->error){
										SendError($conn->error);
									}
									while($user_data = $result->fetch_assoc()) {
										echo "<div class='chip' id='".$user_data['SID']."'>".$user_data['Sname']."<i class='close material-icons'>close</i></div>";
									}
									?>
								</div>
							</div>
						</div>
					</form>
					<div class="row ">
						<a href="#!" class="btn waves-effect waves-light right" id="save" >Save</a>
					</div>
					<div class="row ">
						<form enctype="multipart/form-data">
							<div class="input-field col s9 l9">

								<div class="file-field input-field">
									<div class="btn">
										<span>File</span>
										<input name="fileToUpload" id="fileToUpload" type="file" />

									</div>
									<div class="file-path-wrapper">
										<input class="file-path validate" style="margin-bottom:0px" type="text">
										<progress style="width:100%" ></progress>
									</div>
								</div>
							</div>
							<div class="input-field col s3 l3">
								<div class="file-field input-field">
									<input class="btn waves-effect waves-light right" id="uploadfile" type="button" value="Upload" />
								</div>
							</div>
						</form>
					</div>
					<script>
						$('#fileToUpload').change(function(){
							var file = this.files[0];
							var name = file.name;
							var size = file.size;
							var type = file.type;
    //Your validation
						});
						$('#uploadfile').click(function(){
							var formData = new FormData($('form')[1]);
							$.ajax({
								url: 'getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&profilepic=',  //Server script to process data
								type: 'POST',
								xhr: function() {  // Custom XMLHttpRequest
									var myXhr = $.ajaxSettings.xhr();
									if(myXhr.upload){ // Check if upload property exists
										myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
									}
									return myXhr;
								},
								success: completeHandler,
							        // Form data
								data: formData,
							        //Options to tell jQuery not to process data or worry about content-type.
								cache: false,
								contentType: false,
								processData: false
							});
						});
						function completeHandler(result){
								//$('body').append(result);
							result = jQuery.parseJSON(result);
							if(typeof result.error !=='undefined'){
								Materialize.toast(result.error, 4000);
							}else{
								window.location.href = "Index.php?setting";
							}
						}
						function progressHandlingFunction(e){
							if(e.lengthComputable){
								$('progress').attr({value:e.loaded,max:e.total});
							}
						}
						function sublist(div) {
							var result = [];
							var chips = div.children();
							for (var i=0, iLen=chips.length; i<iLen; i++) {
								result.push($(chips[i]).attr('id'));
							}
							return result
						}
						function adds(id,name) {
							$("#results").html("");
							$('#last_name1').val("");
							$html = "<div class='chip' id='"+id+"'>"+name+"<i class='close material-icons'>close</i></div>";
							$("#resultadd").append($html);
						}
						$("#save").click(function(){
							var list = sublist($("#resultadd"));
							$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&save="+$('#settingname').val()+"&old="+$('#old_pass').val()+"&new="+$('#new_pass').val()+"&newsubs="+list, function(result){
								if(typeof result.error !=='undefined'){
									Materialize.toast(result.error, 4000);
								}else{
									window.location.href = "Index.php?setting";
								}
							}).fail(function(d) {
								Materialize.toast("Connection Problem", 4000);
							});
						});
					</script>
					<?php
				}else if(isset($_GET['notifi'])){
					?>
					<ul class="collection" id="msg_list" >

					</ul>
					<script>
						function load_msg() {
							$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&notification=", function(result){
								if(typeof result.error !=='undefined'){
									Materialize.toast(result.error, 4000);
								}else{
									$(".brand-logo").html("Notifications");
									if(result.noti.length >0){
										$("#msg_list").html("");
										for (var i = 0;i < result.noti.length;i++) {
											$ac = (result.noti[i].Nstatus=="Unreaded")?'active':'';
											$html = '<li class="collection-item avatar '+$ac+'">';
											$html += "<a onclick=\"";
											$html += (result.noti[i].Email=="<?php echo $_SESSION['uname']; ?>")?"":"who('"+result.noti[i].Link+"')";
											$html += "\">";
											$html += '<img src="'+result.noti[i].Profile_pic+'" alt="" class="circle"/></a>';
											$html += "<a href=\"";
											$html += (result.noti[i].Ntype == "Commented")?"?thread="+result.noti[i].Nthread+"&comments="+result.noti[i].Ndata:"?thread="+result.noti[i].Ndata;
											$html += "\">";
											$html += '<span class="title">';
											$html += baktxt(result.noti[i].Ntxt);
											$html += '</span></a>';
											$html += '<p>';
											$html += jQuery.timeago(result.noti[i].Ntime);
											$html += '</li>';
											$("#msg_list").append($html);
										}

									}
								}
							}).fail(function(d) {
								Materialize.toast("Connection Problem", 4000);
							});

						}
						load_msg();
						var msg_interval = setInterval(load_msg, 1000);
					</script>
					<?php
				}else if(isset($_GET['thread'])){
					if(isset($_GET['comments'])){
						?>
						<div clss="row">
							<div class="col s12">
								<div class="card-panel cyan darken-4" style="display: block;padding: 7px; padding-bottom:20px !important;color:white;">
									<h6 id="TText"></h6>
								</div>
							</div>
						</div>
						<div class="row" id="dashborad" style="padding-top: 20px;">

						</div>
						<div clss="row">
							<div class="col s8">
								<label id="cmtlab" for="chattxt">Comemnt</label>
								<textarea type="textarea" class="materialize-textarea" id="chattxt" style="" ></textarea>
							</div>
							<div class="col s4">
								<button class="btn waves-effect waves-light" id="send" name="action">Comemnt
									<i class="material-icons right">send</i>
								</button>
							</div>
						</div>
						<script>
							$("#send").click(function(){
								$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&putcomment=<?php echo $_GET['comments']; ?>&txt="+newlines($("#chattxt").val()), function(result){
									if(typeof result.error !=='undefined'){
										Materialize.toast(result.error, 4000);
										$("#chattxt").val("");
									}else{
										$("#chattxt").val("");
										$("#chattxt").css({height:0});
									}
								}).fail(function(d) {
									Materialize.toast("Connection Problem", 4000);
								});
							});
							$('#chattxt').keyup(function(){
								var t = $("#chattxt").val();
								var code = t.split("=");
								if(code[0]=="@ano"){
									$('#cmtlab').html("Unknown Comemnt");
								}else if(code[0]=="@anopic"){
									$('#cmtlab').html("Unknown Picture Comemnt");
								}else if(code[0]=="@pic"){
									$('#cmtlab').html("Picture Comemnt");
								}else if(code[0]=="@unfollow"){
									$('#cmtlab').html("Unfollow This Threads");
								}else if(code[0]=="@follow"){
									$('#cmtlab').html("Follow This Threads");
								}else{
									$('#cmtlab').html("Comemnt");
								}
							});
							function load_msg() {
								$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&comments=<?php echo $_GET['comments']; ?>", function(result){
									if(typeof result.error !=='undefined'){
										Materialize.toast(result.error, 4000);
									}else{
										$("#TText").html(baktxt(result.Title));
										if(result.Comments.length >0){
											$("#dashborad").html("");
											for (var i = (result.Comments.length-1); i > -1 ;i--) {
												$html = '<div class="col s12 m12"  ><div class="card-panel cyan darken-4" style="display: block;padding: 7px; padding-bottom:20px !important;"><div class="row" style="margin-bottom:0px;"><div class="col s3 m2" style="    width: 80px;"><img width="64px" src="';
												$html += result.Comments[i].Profile_pic;
												$html += '" alt="" class="circle"></div><div class="col s9 m10"><div class="white-text">';
												$html += "<b>"+baktxt(result.Comments[i].Name)+"</b><br>";
												$html += baktxt(result.Comments[i].Comment)+"<br>";
												$html += jQuery.timeago(result.Comments[i].Ctime);
												$html += (result.Comments[i].Email =="<?php echo $_SESSION['uname']; ?>")?'<a class="waves-effect waves-light " href="getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass'];?>&deletec='+result.Comments[i].CID+'&dc=" onclick="showModal(this, \'md1\'); return false;">(Delete)</a>'+'<a class="waves-effect waves-light " href="getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass'];?>&editc='+result.Comments[i].CID+'&txt=" onclick="showEDITModal(this, \'e1\'); return false;">(Edit)</a>':'';
												$html += "<br>";
												$html += '</div></div></div></div></div>';
												$("#dashborad").append($html);
											}

										}
									}
								}).fail(function(d) {
									Materialize.toast("Connection Problem", 4000);
								});
							}
							load_msg();
							var msg_interval = setInterval(load_msg, 1000);

						</script>
						<?php
					}elseif (isset($_GET['files'])) {
						?>
						
						<div class="row" id="dashborad" style="padding-top: 20px;">

						</div>
						<div class="row ">
							<form enctype="multipart/form-data">
								<div class="input-field col s9 l9">

									<div class="file-field input-field">
										<div class="btn">
											<span>File</span>
											<input name="fileToUpload" id="fileToUpload" type="file" />

										</div>
										<div class="file-path-wrapper">
											<input class="file-path validate" style="margin-bottom:0px" type="text">
											<progress style="width:100%" ></progress>
										</div>
									</div>
								</div>
								<div class="input-field col s3 l3">
									<div class="file-field input-field">
										<input class="btn waves-effect waves-light right" id="uploadfile" type="button" value="Upload" />
									</div>
								</div>
							</form>
						</div>
						<script>
							$('#uploadfile').click(function(){
								var formData = new FormData($('form')[0]);
								$.ajax({
									url: 'getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&Tupload=<?php echo $_GET['thread']; ?>',  //Server script to process data
									type: 'POST',
									xhr: function() {  // Custom XMLHttpRequest
										var myXhr = $.ajaxSettings.xhr();
										if(myXhr.upload){ // Check if upload property exists
											myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
										}
										return myXhr;
									},
									success: completeHandler,
							        // Form data
									data: formData,
							        //Options to tell jQuery not to process data or worry about content-type.
									cache: false,
									contentType: false,
									processData: false
								});
							});
							function completeHandler(result){
								//$('body').append(result);
								result = jQuery.parseJSON(result);
								if(typeof result.error !=='undefined'){
									Materialize.toast(result.error, 4000);
								}else{
									window.location.href = "Index.php?thread=<?php echo $_GET['thread']; ?>&files=";
								}
							}
							function progressHandlingFunction(e){
								if(e.lengthComputable){
									$('progress').attr({value:e.loaded,max:e.total});
								}
							}
							function load_msg() {
								$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&Tfile=<?php echo $_GET['thread']; ?>", function(result){
									if(typeof result.error !=='undefined'){
										Materialize.toast(result.error, 4000);
									}else{
										if(result.files.length >0){
											$("#dashborad").html("");
											$(".brand-logo").html(result.Title);
											for (var i = 0; i < result.files.length;i++) {
												$html = '<a href="'+result.files[i].path+'" >';
												$html += '<div class="card-panel waves-effect waves-light cyan darken-4" style="margin: 0.5rem 0 0.6rem 0;display: block;padding: 7px; padding-bottom:7px !important;color:white;"><h6 id="TText">'+result.files[i].name+'</h6></div>';
												$html += '</a>';
												$("#dashborad").append($html);
											}

										}
									}
								}).fail(function(d) {
									Materialize.toast("Connection Problem", 4000);
								});
							}
							load_msg();
							var msg_interval = setInterval(load_msg, 1000);

						</script>
						<?php
					}else{
						?>
						<div clss="row">
							<div class="col s12">
								<a href="Index.php?thread=<?php echo $_GET['thread']; ?>&files="><div class="card-panel waves-effect waves-light cyan darken-4" style="display: block;padding: 7px; padding-bottom:7px !important;color:white;">
									<h6 id="TText">Files</h6>
								</div></a>
							</div>
						</div>
						<div class="row" id="dashborad" style="padding-top: 20px;">

						</div>
						<div id="addthread" class="modal modal-fixed-footer">
							<div class="modal-content">
								<div class="row">
									<div class="col s12">
										<div class="row">
											<h5 class="header">Creat Threads</h5>
											<div class="input-field col s12">
												<textarea placeholder="Is There Any Problem?" class="materialize-textarea" id="textarea1" type="" ></textarea>
												<label for="textarea1">Thread Text</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<a href="#!" onclick="createthread()" class=" modal-action waves-effect waves-green btn-flat">Creat Thread</a>
								<a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
							</div>
						</div>
						<div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
							<a class="btn-floating btn-large red modal-trigger" href="#addthread">
								<i class="large material-icons">mode_edit</i>
							</a>
						</div>
						<script>
							$("#addthread").animate({height:'300px'},"slow");
							function createthread(){
								$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&maketheread="+newlines($("#textarea1").val())+"&sub=<?php echo $_GET['thread']; ?>", function(result){
									if(typeof result.error !=='undefined'){
										Materialize.toast(result.error, 4000);
									}else{
										$("#textarea1").val("");
										$("#textarea1").css({height:0});
										$('#addthread').closeModal();
									}
								}).fail(function(d) {
									Materialize.toast("Connection Problem", 4000);
								});
							}
							function load_msg() {
								$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&threads=<?php echo $_GET['thread']; ?>", function(result){
									if(typeof result.error !=='undefined'){
										Materialize.toast(result.error, 4000);
									}else{
										$(".brand-logo").html(result.Title);
										if(result.Threads.length >0){
											$("#dashborad").html("");
											for (var i = 0; i < result.Threads.length;i++) {
												$html = '<a href="Index.php?thread=<?php echo $_GET['thread']; ?>&comments='+result.Threads[i].TID+'"><div class="col s12 m12"><div class="card-panel waves-effect waves-light cyan darken-1" style="display: block;padding: 7px;"><div class="row" style="margin-bottom:0px;"><div class="col s3 m2" style="    width: 80px;"><img width="64px" src="';
												$html += result.Threads[i].Profile_pic;
												$html += '" alt="" class="circle"></div><div class="col s9 m10"><div class="white-text">';
												$html += "<b>"+baktxt(result.Threads[i].Name)+"</b><br>";
												$html += baktxt(result.Threads[i].TText)+"<br>";
												$html += jQuery.timeago(result.Threads[i].Posted_Date);
												$html += (result.Threads[i].Email =="<?php echo $_SESSION['uname']; ?>")?'<a class="waves-effect waves-light " href="getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass'];?>&deletet='+result.Threads[i].TID+'&dc=" onclick="showModal(this, \'md1\'); return false;">(Delete)</a>'+'<a class="waves-effect waves-light " href="getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass'];?>&editt='+result.Threads[i].TID+'&txt=" onclick="showEDITModal(this, \'e1\'); return false;">(Edit)</a>':'';
												$html += "<br>";
												$html += '</div></div></div></div></div></a>';
												$("#dashborad").append($html);
											}

										}
									}
								}).fail(function(d) {
									Materialize.toast("Connection Problem", 4000);
								});
							}
							load_msg();
							var msg_interval = setInterval(load_msg, 1000);

						</script>
						<?php
					}
				}else{
					?>
					<div class="row" id="dashborad" style="padding-top: 20px;">
					</div>
					<script>
						function load_msg() {
							$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&dashborad=", function(result){
								if(typeof result.error !=='undefined'){
									Materialize.toast(result.error, 4000);
								}else{
									if(result.Subjects.length >0){
										$("#dashborad").html("");
										for (var i = 0; i < result.Subjects.length;i++) {
											$html = '<a href="Index.php?thread='+result.Subjects[i].SID+'"><div class="col s12 m3">';
											$html += ' 		<div class="card-panel waves-effect waves-light teal" style="display: block;">';
											$html += ' 			<h6 class="white-text center">';
											$html += ' 					<b>'+baktxt(result.Subjects[i].Sname)+'</b>';
											$html += (result.Subjects[i].Teacher =="<?php echo $_SESSION['uname']; ?>")?"</br>(Teacher)":"</br>(Student)";
											$html += '			</h6>';
											$html += '		</div>';
											$html += '</div></a>';
											$("#dashborad").append($html);
										}

									}
								}
							}).fail(function(d) {
								Materialize.toast("Connection Problem", 4000);
							});
						}
						load_msg();
						var msg_interval = setInterval(load_msg, 1000);

					</script>
					<?php
				}
				?>
			</div>

		</div>
		<script>
			$('select').material_select();
			$('.datepicker').pickadate({
				    selectMonths: true, // Creates a dropdown to control month
				    selectYears: 100 // Creates a dropdown of 15 years to control year
				});
			$('.modal-trigger').leanModal({
			      dismissible: false, // Modal can be dismissed by clicking outside of the modal
			      opacity: 1, // Opacity of modal background
			      in_duration: 300, // Transition in duration
			      out_duration: 200, // Transition out duration
			      ready: function() { }, // Callback for Modal open
			      complete: function() { } // Callback for Modal close
			  }
			  );
			$('#sidebar').sideNav({
				      menuWidth: 300, // Default is 240
				      edge: 'left', // Choose the horizontal origin
				      closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
				  }
				  );
			function showModal(but, modal){  
				$('#' + modal).openModal(); 
				$('#' + modal + '_YesBtn').click(function(){
					$('#' + modal).closeModal();
					$.getJSON(but.href,function(result){
						if(typeof result.error !=='undefined'){
							Materialize.toast(result.error, 4000);
						}else if(typeof result.url !=='undefined'){
									//window.location.href = result.url;
								}else{

								}
							}).fail(function(d) {
								Materialize.toast("Connection Problem", 4000);
							});
						}); 
			}
			function showEDITModal(but, modal){  
				$('#' + modal).openModal(); 
				$('#' + modal + '_YesBtn').click(function(){
					$('#' + modal).closeModal();
					$.getJSON(but.href+newlines($('#' + modal + '_txt').val()),function(result){
						if(typeof result.error !=='undefined'){
							Materialize.toast(result.error, 4000);
						}else if(typeof result.url !=='undefined'){
									//window.location.href = result.url;
								}else{
									$('#' + modal + '_txt').val("");
									$('#' + modal + '_txt').css({height:0});

								}
							}).fail(function(d) {
								Materialize.toast("Connection Problem", 4000);
							});
						}); 
			}
			function who(user){
				$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&user="+user, function(result){
					if(typeof result.name !=='undefined'){
						$html = '<div class="">';
						$html += '	<div class="col s3" style="float: left;margin-right: 20px;" >';
						$html += '		<img src="'+result.pp+'" alt="" width="200;" class="circle materialboxed"/>';
						$html += '	</div>';
						$html += '	<div class="col s8" style="float: left;" >';
						$html += '		<h5> Name : '+baktxt(result.name)+'</h5>';
						$html += '		<h6> Age : '+result.age+'</h6>';
						$html += '		<h6> Gender : '+result.gen+'</h6>';
						$html += '	</div>';
						$html += '</div>';
						$('#contents').html($html);
						if(typeof result.link !=='undefined'){
							$html = '<a href="Index.php?chatwith='+result.link+'" class="modal-action modal-close waves-effect waves-green btn-flat" >Got to Chat</a>';
							$('#chatbutton').html($html);
						}
						$('.materialboxed').materialbox();
						$('#modal2').openModal();
						$('.lean-overlay').fadeTo( "slow" , 1);
					}else{
						$("#chattxt").val("");
					}
				}).fail(function(d) {
					Materialize.toast("Connection Problem", 4000);
				});
			}
			<?php
			if(isset($_GET['chatwith'])){
				?>
				function runScript(e) {
					if (e.keyCode == 13) {
						$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&chat=<?php echo enc::decrypt($_GET['chatwith'],hex2bin(enc::$key), true); ?>&send=&msg="+newlines($("#chattxt").val()), function(result){
							if(typeof result.error !=='undefined'){
								Materialize.toast(result.error, 4000);
								var res = result.error.split(" ");
								if(res[0]=="Group"){
									$("#chattxt").val("");
								}
							}else{
								$("#chattxt").val("");
							}
						}).fail(function(d) {
							Materialize.toast("Connection Problem", 4000);
						});
						return false;
					}
				}
				<?php
			}
			?>
			$(document).ready(function(){
				var $lasttime ="";
				$('#last_name1').keyup(function(){
					if (this.value != "") {
						var list = sublist($("#resultadd")); 
						$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&getsubjects="+this.value+"&exprct="+list, function(result){
							if(typeof result.error !=='undefined'){
								Materialize.toast(result.error, 4000);
							}else{
								if(result.subs.length >0){
									$("#results").html("");
									$html="";
									for (var i =  0; i < result.subs.length  ; i++) {
										$html += "<div class='chip'><a onclick=\"adds('"+result.subs[i].SID+"','"+result.subs[i].Sname+"')\">"+result.subs[i].Sname+"</a></div>";
									}
									$("#results").append($html);
								}
							}
						}).fail(function(d) {
							Materialize.toast("Connection Problem", 4000);
						});
					}else{
						$("#results").html("");
					}
				});
				<?php
				if(isset($_GET['chatwith'])){
					?>
					$("#send").click(function(){
						var list = userlist($("#resultgadd")); 

						$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&chat=<?php echo enc::decrypt($_GET['chatwith'],hex2bin(enc::$key), true); ?>&send=&msg="+newlines($("#chattxt").val())+"&list="+list, function(result){
							if(typeof result.error !=='undefined'){
								Materialize.toast(result.error, 4000);
								var res = result.error.split(" ");
								if(res[0]=="Group"){
									$("#chattxt").val("");
									$("#resultgadd").html("");
										//$("#chattxt").css({height:0});

								}
							}else{
								$("#chattxt").val("");
								$("#resultgadd").html("");
									//$("#chattxt").css({height:0});
							}
						}).fail(function(d) {
							Materialize.toast("Connection Problem", 4000);
						});
					});
					function load_msg() {
						$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&chat=<?php echo enc::decrypt($_GET['chatwith'],hex2bin(enc::$key), true); ?>&links=&get=&lim="+msglim, function(result){
							if(typeof result.error !=='undefined'){
								Materialize.toast(result.error, 4000);
							}else{
								$(".brand-logo").html(result.Title);
								$("#ulist").val(result.users);
								if(result.chat.length >0){
									$("#msg_list").html("");
									if(result.chat.length == msglim){
										$("#msg_list").append('<button style="width:96%;margin:2%" class="btn waves-effect waves-light" onclick="moremsg()" type="button" name="action">Load More</button>');
									} 
									for (var i = result.chat.length-1; i >= 0  ; i--) {
										$Heread = "";
										$html = '<li class="collection-item avatar"';
										$html += (result.chat[i].Email=="<?php echo $_SESSION['uname']; ?>")?'id="right_msg" >':'>';
										$html += "<a onclick=\"";
										$html += (result.chat[i].Email=="<?php echo $_SESSION['uname']; ?>")?"":"who('"+result.chat[i].Link+"')";
										$html += "\">";
										$html += '<img src="'+result.chat[i].Profile_pic+'" alt="" class="circle"/>';
										$html += '<span class="title">';
										$html += baktxt(result.chat[i].Name);
										$html += '</span>';
										$html += '<p>';
										$html += baktxt(result.chat[i].Message);
										$html += '</br>';
										$html += (result.chat[i].Email=="<?php echo $_SESSION['uname']; ?>")? (result.chat[i].Deleverd == "true")?'(Delivered) ':'':'';
										$html += jQuery.timeago(result.chat[i].Time)+$Heread;
										$html += '  <a class="waves-effect waves-light " href="getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass'];?>&delete='+result.chat[i].Mid+'&dc=<?php echo enc::decrypt($_GET['chatwith'],hex2bin(enc::$key), true); ?>" onclick="showModal(this, \'md1\'); return false;">(Delete)</a></p></a>';
										//$html += (result.chat[i].Read>0)?'':'<a href="#" class="secondary-content"><i class="material-icons">grade</i></a>';

										$html += '</li>';
										$("#msg_list").append($html);
										$('.dropdown-button').dropdown();
									}
									if (!($lasttime == result.chat[0].Time)) {

										$lasttime = result.chat[0].Time
										window.scrollTo(0,document.body.scrollHeight);
									}

								}
							}
						}).fail(function(d) {
							Materialize.toast("Connection Problem", 4000);
						});

					}
					load_msg();
					var msg_interval = setInterval(load_msg, 1000);	
					<?php
				}else if(isset($_GET['chat'])){
					?>
					function load_msg() {
						$.getJSON("getter.php?uname=<?php echo $_SESSION['uname']; ?>&upass=<?php echo $_SESSION['upass']; ?>&getmsg=&links=", function(result){
							if(typeof result.error !=='undefined'){
								Materialize.toast(result.error, 4000);
							}else{
								$(".brand-logo").html("Messages");
								if(result.msg.length >0){
									$("#msg_list").html("");
									for (var i = 0; i < result.msg.length ; i++) {
										$html = '<a href="Index.php?chatwith='+result.msg[i].Link+'">';
										$html += '<li class="collection-item avatar">';
										$html += '<img src="'+result.msg[i].Profile_pic+'" alt="" class="circle"/>';
										$html += '<span class="title">';
										$html += baktxt(result.msg[i].Name);
										if(result.msg[i].lastonline != ""){
											$now = (jQuery.timeago(result.msg[i].lastonline) == "Few seconds ago")?"Online":jQuery.timeago(result.msg[i].lastonline);
											$html += " ("+$now+")";
										}
										$html += '</span>';
										$html += '<p>';
										$html += baktxt(result.msg[i].Message);
										$html += '</br>';
										$html += jQuery.timeago(result.msg[i].Time);
										$html += '</p>';
										$html += (result.msg[i].Unreaded<1)?'':'<a href="#" class="secondary-content"><i class="material-icons">grade</i></a>';
										$html += '</li></a>';
										$("#msg_list").append($html);
									}
								}
							}
						}).fail(function(d) {
							Materialize.toast("Connection Problem", 4000);
						});
					}
					load_msg();
					var msg_interval = setInterval(load_msg, 4000);
					<?php
				}
				?>
				function nosti(){
					var h = window.innerHeight;
					if(($( window ).width())>620){
						$("#login-page").animate({width:'50%'},"fast");
					}else{
						$("#login-page").animate({width:'100%'},"fast");
						$("#login-page").animate({height:'100%'},"fast");
					}
				}

				nosti();
				//var page_size = setInterval(nosti, 100);
			});
		</script>

	</body>
	</html>
