<?php 
require_once 'init.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sign Up - EXCEL PHARMACY</title>
	<link rel="stylesheet" type="text/css" href="assets/css/logged-out.css">
	<script type="text/javascript" src="assets/js/jquery.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.js"></script>
</head>
<body>
	<div class="header-wrapper">
		<div class="logged-out-header">
			<div class="logged-out-container clearfix">
				<div class="header-logo header-title">
					<img src="assets/images/pharma_logo.jpg" height="48" class="octicon octicon-mark-github" viewBox="0 0 16 16" version="1.1" width="48">
					<h1>EXCEL PHARMACY</h1>
					<p class="header-subtitle">Drug Management Information System</p>
				</div>
			</div>
		</div>
	</div>
	<div id="start-of-content" class="show-on-focus sr-only"></div>
	<div class="login-icon">
		<img src="assets/images/icons/lock24.png">
	</div>
	<div class="application-main" role='main'>
		<div class="form-container">
			<div id="login" class="auth-form px-3">
				<form action="login.php" role='form' method="POST" accept-charset="UTF-8" autocomplete="off">
      				<div class="auth-form-body mt-3">
      					<label for="username">Username</label>
      					<input name="username" id="username" class="form-control input-block" placeholder="Enter username" tabindex="1" autocapitalize="off" autocorrect="off" autofocus="autofocus" type="text" required="true">
      					<label for="password">
				          Password
				        </label>
				        <input name="password" id="password" class="form-control input-block" placeholder="Enter password" tabindex="2" type="password" required="true">
				        <input name="login" value="Sign in" tabindex="3" class="btn btn-primary btn-block" type="submit">
      				</div>
				</form>
			    <?php 
					if (isset($_POST['login'])) {
						if(login($_POST['username'], $_POST['password'])){
							redirect('dashboard.php');
						}
						else{ ?>
					<div class="recover-account-callout mt-3 alert alert-danger text-center alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
							&times;
						</button>
						<svg class="octicon octicon-alert" viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M8.893 1.5c-.183-.31-.52-.5-.887-.5s-.703.19-.886.5L.138 13.499a.98.98 0 0 0 0 1.001c.193.31.53.501.886.501h13.964c.367 0 .704-.19.877-.5a1.03 1.03 0 0 0 .01-1.002L8.893 1.5zm.133 11.497H6.987v-2.003h2.039v2.003zm0-3.004H6.987V5.987h2.039v4.006z"></path></svg>
						<b class="text-capitalize">Incorrect username or password</b>
					</div>
						<?php }
					}
				?>   
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</body>
</html>