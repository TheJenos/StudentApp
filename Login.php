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
		<!--Import Google Icon Font-->
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Import materialize.css-->
		<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
		
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<style type="text/css">
			body { 
			    background-image: url('login.png');
			    background-repeat: no-repeat;
			    background-attachment: fixed;
			}
		</style>
	</head>
	<!-- Modal Structure -->
	<div id="modal1" class="modal ">
	    <div class="modal-content">
    		<h4>Register Now!</h4>
		    <form class="col s12">
			    <div class="row">
			        <div class="input-field col s11">
			          <i class="material-icons prefix">account_circle</i>
			          <input id="icon_prefix" type="text" class="validate">
			          <label for="icon_prefix">First Name</label>
			        </div>
			    </div>
			    <div class="row">
			        <div class="input-field col s11">
			          <i class="material-icons prefix">account_circle</i>
			          <input id="icon_telephone" type="tel" class="validate">
			          <label for="icon_telephone">Second Name</label>
			        </div>
			    </div>
			    <div class="row">
		        	<div class="input-field col s11">
			          	<i class="material-icons prefix" id="icon">library_books</i>
			          	<input id="last_name" type="text" class="validate " onkeyup="search(this)" >
			          	<label for="last_name">Subjects</label>
			          	<input id="email" type="hidden">
			          	<div id="result"  style="margin-left: 3rem;">
			    		</div>
			    		<div id="resultadd" style="margin-left: 3rem;">
			          	</div>
		          	</div>
		        </div>
		    </form>
	    </div>
	    <div class="modal-footer">
	    	<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Agree</a>
	    </div>
	</div>
    <div style="width:350px;"  id="login-page" class="row">
		<div class="col s12 z-depth-6 card-panel">
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
						<p class="margin medium-small"> <a class="modal-trigger " href="#modal1">Register Now!</a></p>
					</div>        
				</div>
			</form>
		</div>
	</div>
    <body>
		<!--Import jQuery before materialize.js-->
		<script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>
		<script>
			function sublist(div) {
				var result = [];
				var chips = div.children();
				for (var i=0, iLen=chips.length; i<iLen; i++) {
					result.push($(chips[i]).attr('id'));
				}
				return result
 			}
			function add(id,name) {
					$("#result").html("");
					$('#last_name').val("");
					$html = "<div class='chip' id='"+id+"'>"+name+"<i class='close material-icons'>close</i></div>";
					$("#resultadd").append($html);
			}
			$(document).ready(function(){
				//$('select').material_select();
				//$('.chips').material_chip();
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
		</script>
	</script>
</body>
</html>
