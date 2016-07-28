<?php
include 'SQL.php';
IF(isset($_SESSION['uname'])){
	header('Location: Index.php');
	exit;
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
	<meta name="theme-color" content="#212121">
	<style type="text/css">
		body { 
			background-image: url('login.png');
			background-repeat: no-repeat;
			background-attachment: fixed;
		}
	</style>
</head>
<!-- Modal Structure -->
<div style="width:450px;"  id="login-page" class="row">
	<div class="z-depth-6 card-panel">
		<div id="log" class=" "  style="overflow: hidden;">
			<form id="logins" action="Index.php" method="post">
				<div class="row">
					<div class="input-field col s12 center">
						<img src="Logo.png" alt="" class="responsive-img valign profile-image-login">
					</div>
				</div>
				<div class="row margin">
					<div class="input-field col s12 l12">
						<input class="validate" id="user_email" name="usernam"  type="email">
						<label for="email">Email</label>
					</div>
				</div>
				<div class="row margin">
					<div class="input-field col s12 l12">
						<input id="user_password" name="userpass" type="password">
						<label for="password">Password</label>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<a id="login"  class="btn waves-effect waves-light col s12">Login</a>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s6 m6 l6">
						<p class="margin medium-small"> <a class=" " onclick="sign()">Register Now!</a></p>
					</div>        
				</div>
			</form>
		</div>
		<div id="sing" class=" " style="overflow: hidden;height:0px;">
			<div class="modal-content">
				<h4>Register Now!</h4>
				<form class="col s12" id="sinup" onsubmit="return validateForm();" action="Index.php" method="post">
					<div class="row">
						<label for="icon_prefix">Name</label>
						<div class="input-field col s12 l12">
							<input id="icon_prefix" name="sign_name" type="text" class="validate">
						</div>
					</div>
					<div class="row margin">
						<label for="email">Email</label>
						<div class="input-field col s12 l12">
							<input class="validate" id="sign_email" name="usernam"  type="email">
						</div>
					</div>
					<div class="row margin">
						<label for="password">Password</label>
						<div class="input-field col s12 l12">
							<input id="sign_pass" name="userpass" type="password">

						</div>
					</div>
					<div class="row">
						<label for="last_name">Subjects</label>
						<div class="input-field col s12 l12">
							<input id="last_name" type="text" class="validate " onkeyup="search(this)" >
							<input id="email" type="hidden">
							<div id="result"  style="">
							</div>
							<div id="resultadd" style="">
							</div>
						</div>
					</div>
					<div class="row ">
						<label for="last_name">Date Of Birth</label>
						<div class="input-field col s12 l12">
							<input id="dob" type="date" class="datepicker">
						</div>
					</div>
					<div class="row ">
						<p>
							<input name="gen" value="1" checked="true" type="radio" id="test1" />
							<label for="test1">Male</label>
							<input name="gen" value="0" type="radio" id="test2" />
							<label for="test2">Female</label>
						</p>
					</div>
				</form>
			</div>
			<div class="row ">
				<div class=" input-field col s12 l12">

					<a href="#!" class="btn waves-effect waves-light col s12" id="signup" >Sign up</a>
				</div>
				<div class=" input-field col s12 l12">
					<a href="#!" onclick="log()" class="btn waves-effect waves-light col s12" id=" ">Cancel</a>
				</div>
			</div>
		</div>
	</div>
</div>
<body>
	<!--Import jQuery before materialize.js-->
	<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="js/materialize.min.js"></script>
	<script>
		function dob() {
			var x = $('#dob').val();
			if(x!=""){
				var array = x.split(',');
				var daymonth = array[0].split(' ');
				var month = 0;
				switch (daymonth[1]){
					case "January": month = 1;
					break;
					case "February": month = 2;
					break;
					case "March": month = 3;
					break;
					case "April": month = 4;
					break;
					case "May": month = 5;
					break;
					case "June": month = 6;
					break;
					case "July": month = 7;
					break;
					case "August": month = 8;
					break;
					case "September": month = 9;
					break;
					case "October": month = 10;
					break;
					case "November": month = 11;
					break;
					case "December": month = 12;
					break;
				}
				return array[1].split(' ')[1]+"-"+month+"-"+daymonth[0];
			}else{
				return "";
			}
		}
		function validateForm() {
			var x = $("#sign_email").val();
			var atpos = x.indexOf("@");
			var dotpos = x.lastIndexOf(".");
			if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length) {
				Materialize.toast("Not a valid e-mail address",4000);
				return false;
			}
		}
		function sign() {
			$("#log").animate({height:'0'},"slow");
			$("#sing").animate({height: $("#sing").get(0).scrollHeight},"slow");
		}
		function log() {
			$("#sing").animate({height:'0'},"slow");
			$("#log").animate({height: $("#log").get(0).scrollHeight},"slow");
		}
		function sublist(div) {
			var result = [];
			var chips = div.children();
			for (var i=0, iLen=chips.length; i<iLen; i++) {
				result.push($(chips[i]).attr('id'));
			}
			return result
		}
		function gen() {
			if(document.getElementsByName("gen")[0].checked == true){
				return 1;
			}
			return 0;
		}
		function add(id,name) {
			$("#result").html("");
			$('#last_name').val("");
			$html = "<div class='chip' id='"+id+"'>"+name+"<i class='close material-icons'>close</i></div>";
			$("#resultadd").append($html);
		}
		$(document).ready(function(){
				//$('select').material_select();
				//$('.chips').material_chip();\
				$('.datepicker').pickadate({
				    selectMonths: true, // Creates a dropdown to control month
				    selectYears: 100 // Creates a dropdown of 15 years to control year
				});
				$("#sing").animate({height:'0'},"fast");
				$('.modal-trigger').leanModal();
				$('#last_name').keyup(function(){
					if (this.value != "") {
						var list = sublist($("#resultadd")); 
						$.getJSON("getter.php?getsubjects="+this.value+"&exprct="+list, function(result){
							if(typeof result.error !=='undefined'){
								Materialize.toast(result.error, 4000);
							}else{
								if(result.subs.length >0){
									$("#result").html("");
									$html="";
									for (var i =  0; i < result.subs.length  ; i++) {
										$html += "<div class='chip'><a onclick=\"add('"+result.subs[i].SID+"','"+result.subs[i].Sname+"')\">"+result.subs[i].Sname+"</a></div>";
									}
									$("#result").append($html);
								}
							}
						}).fail(function(d) {
							Materialize.toast("Connection Problem", 4000);
						});
					}else{
						$("#result").html("");
					}
				});
				$("#signup").click(function(){
					var list = sublist($("#resultadd")); 
					$.getJSON("getter.php?verifiemail="+$("#sign_email").val()+"&name="+$("#icon_prefix").val()+"&subs="+list+"&gen="+gen()+"&pass="+$("#sign_pass").val()+"&dob="+dob(), function(result){
						if(typeof result.error !=='undefined'){
							Materialize.toast(result.error, 4000);
						}else{
							$("#sinup").submit();
						}
					}).fail(function(d) {
						Materialize.toast("Connection Problem", 4000);
					});
				});
				$("#login").click(function(){
					$.getJSON("getter.php?uname="+$("#user_email").val()+"&upass="+$("#user_password").val(), function(result){
						if(typeof result.error !=='undefined'){
							Materialize.toast(result.error, 4000);
						}else{
							$("#logins").submit();
						}
					}).fail(function(d) {
						Materialize.toast("Connection Problem", 4000);
					});
				});
				function getSelectValues(select) {
					var result = [];
					var options = select && select.options;
					var opt;

					for (var i=0, iLen=options.length; i<iLen; i++) {
						opt = options[i];

						if (opt.selected) {
							result.push(opt.value || opt.text);
						}
					}
					return result;
				}
			});
		function nosti(){
			var h = window.innerHeight;
			if(($( window ).width())>620){
				$("#login-page").animate({width:'30%'},"fast");
			}else{
				$("#login-page").animate({width:'100%'},"fast");
				$("#login-page").animate({height:'100%'},"fast");
				$(".card-panel").css("margin-top",0);
			}
		}

		nosti();
	</script>
</script>
</body>
</html>
