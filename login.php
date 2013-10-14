<?php
include 'common.php';
?>

<html>
	<head>
		<link rel="stylesheet" href="css/style.css" type="text/css" /> 
	</head>
	<body class="login" onLoad="updateLoginForm()">
		<div id="login_panel">
			<form id="login_form" class="login_form" action="/index" method="post">
				<label>Username:</label><input id="userName" name="userName" type="text"/>
				</br>
				<label>Password:</label><input id="password" name="password" type="password"/>
				</br>
				</br>
				<input name="login_submit" Value="Sign In" type="Submit"/>
			</form>
			<form id="logout_form" class="login_form" action="/index" method="post">
				<input name="logout_submit" Value="Sign Out" type="Submit"/>
			</form>
		</div>
		
		
		<script type="text/javascript">
		
			var loggedInUser = <?php $_SESSION['userSession']->writeUser(); ?>;
		
			function updateLoginForm() {
				if(loggedInUser === "admin") {
					document.getElementById("logout_form").style.visibility = 'visible';
				}
				else {
					document.getElementById("login_form").style.visibility = 'visible';
				}
			}
		</script>
	</body>
</html>
<?php ob_flush(); ?>