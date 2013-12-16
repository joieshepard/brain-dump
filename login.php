<?php 
//includes the database settings
include 'dbc.php';

// if the user is logged in sends to myapp.php
if(($_COOKIE['user_appv2']==='webapp')){
  header("Location: myapp.php");
}

$err = array();

if ($_POST['doLogin'] == 'Login')
    {
	foreach($_POST as $key => $value)
		{
		$data[$key] = filter($value); // post variables are filtered for security reasons.
		}

	$user_email = $data['usr_email'];
	$pass = $data['pwd'];
	$user_cond = "user_name='$user_email'";
	$result = mysql_query("SELECT `id`,`pwd`,`user_name` FROM users WHERE $user_cond") or die(mysql_error());
	$num = mysql_num_rows($result);

	// Match row found with more than 1 results

	if ($num > 0)
		{
		list($id, $pwd, $user_name) = mysql_fetch_row($result);
        
        //  hashes the posted password to check that the posted password and the password in the db are same
		if ($pwd === PwdHash($pass, substr($pwd, 0, 9)))
			{
			if (empty($err))
				{

				// this sets session and logs user in

				session_start();
				session_regenerate_id(true); //prevent against session fixation attacks.

				// this sets the session variables

				$_SESSION['user_id'] = $id;
				$_SESSION['user_name'] = $user_name;
				$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);

				// if remember me was checked - set the cookies as well

				if (isset($_POST['remember']))
					{
					setcookie("user_id", $_SESSION['user_id'], time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
					setcookie("user_name", $_SESSION['user_name'], time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
					setcookie("user_app", "webapp", time() + 60 * 60 * 24 * COOKIE_TIME_OUT, "/");
                    // the COOKIE_TIME_OUT variable is defined in the dbc.php
					}
                
                // if login was successful redirect the user to the app
				header("Location: myapp.php");
				}
			}
		  else
			{
            // if the username and password don't match an error msg will appear inside the .error-box-login div
			$err[] = "Invalid Login. Please try again with correct username and password.";
			}
		}
	  else
		{
        // if the username is not exists in the db an error msg will appear inside the .error-box-login div
		$err[] = "Invalid login. No such user exists";
		}
	}


?>
<!DOCTYPE html>

<html>
<head>
    <title>Login to Brain Dump</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width">
    <meta name="description" content=" " />
    <meta name="keywords" content="" />
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="_js/backstretch.js"></script>
    <link rel="stylesheet" href="_css/reset.css">
    <link rel="stylesheet" href="_css/style.min.css">
    <!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="css/ie8-and-down.css" />
    <![endif]-->
     <script type="text/javascript">
	 
	  // This script changes the text input to password input on focus and vica versa
      function pwdFocus() {
            $('#fakepassword').hide();
            $('#Password').show();
            $('#Password').focus();
      }

      function pwdBlur() {
            if ($('#Password').attr('value') == '') {
                $('#Password').hide();
                $('#fakepassword').show();
            }
      }
    </script>
</head>
<body>
	<h1 class="u-title">Login to Brain Dump</h1>
	<p class="p-forgot">Please login to the Brain Dump with your username and password.</p>

	
	<div id="login-form">



		<!-- this div (.error-box-login) is reserved for error messages. If an error will occur during the login process the error msg here will be shown -->
		<?php
		  if(!empty($err))  {
		  		echo "<div class=\"arrow_box error-login \">";
		  		foreach ($err as $e) {
		  			echo "$e";
		    	}
		  		echo "</div>";	
		  }
		?>

	    <form action="login.php" method="post" name="logForm" id="logForm" >
	        
		    <input type="text" value="Username" id="txtbox" class="u-input" name="usr_email" onblur="if (this.value == '') {this.value = 'Username';}"  onfocus="if (this.value == 'Username') {this.value = '';}" />
		
		    <input type="text" name="pwd1" id="fakepassword" class="u-input" value="Password" onfocus="pwdFocus()" />
		
		    <input style="display: none" type="password" class="u-input" name="pwd" id="Password" value="" onblur="pwdBlur()" />
            

		    <div class="remember">
        
		        <input name="remember" type="checkbox" id="remember" value="1" checked="checked"> <span>Remember me</span>
        
		    </div> <!-- .remember end -->
            

		    <input name="doLogin" type="submit" class="button orange" style="float:right;margin-right:20px;" value="Login">
        
		    <div style="margin-left:42px;float: left;">
	            <a href="index.php" class="block-text" style="float:left;margin-top: 2em;width: auto;">New to Brain Dump? </a><span class="divider"> | </span> <a href="forgot.php" class="block-text" style="float:left;margin-top: 2em;width: auto;"> Forgot password?</a>
	        </div>
        
	</div> <!-- #login-form end -->
	<script type="text/javascript">
	$(document).ready(function(){
	
	
	//Remove the yellow background on webkit browsers
	if (navigator.userAgent.toLowerCase().indexOf("chrome") >= 0) {
	    $(window).load(function(){
	        $('input:-webkit-autofill').each(function(){
	            var text = $(this).val();
	            var name = $(this).attr('name');
	            $(this).after(this.outerHTML).remove();
	            $('input[name=' + name + ']').val(text);
	        });
	    });
	}

	/* 
    load the background image with the backstretch jQuery plugin 
    plugin homepage: http://srobbin.com/jquery-plugins/backstretch/
	*/
	$.backstretch("_img/background.png");

	});
	</script>
</body>
</html>
