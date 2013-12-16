<?php
// include database
include 'dbc.php';

// If the user clicked the Reset Password button 
if($_POST['doReset'])
{
$err = array();
$msg = array();

foreach($_POST as $key => $value) {
	$data[$key] = filter($value); // post variables are filtered for security reasons.
}

$user_email = $data['user_email'];

//checks that email address is exists in the db  
$rs_check = mysql_query("select id from users where user_email='$user_email'") or die (mysql_error()); 
$num = mysql_num_rows($rs_check);

    if ( $num <= 0 ) { // the email address doesn't exists in db
    
	$err[] = "Sorry but no such account exists or registered.";

	}


// if email exists in db 
if(empty($err)) {

// generates a new password
// the GenPwd() function is in the dbc.php
$new_pwd = GenPwd();

// hashes the generated password for security reasons
$pwd_reset = PwdHash($new_pwd);

// updates password for the user
$rs_activ = mysql_query("update users set pwd='$pwd_reset' WHERE user_email='$user_email'") or die(mysql_error());
						 					 
						 
//sends a message to the user with the generated password

$message = 
"Your password has been reseted on www.example.com. Here are your new password details ...\n
Username: not changed!
New Password: $new_pwd \n

Thank You for using our product!

Administrator
$host_upper
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";

	mail($user_email, "Reset Password", $message,
    "From: \"Member Registration\" <auto-reply@$host>\r\n" .
     "X-Mailer: PHP/" . phpversion());						 

// success message 
$msg[] = "Your account password has been reset and a new password has been sent to your email address. Please <a href='login.php'>login</a> with your new password!";						 
						 
 }
}
?>
<!DOCTYPE html>

<html>
<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width">
    <meta name="description" content=" " />
    <meta name="keywords" content="" />
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="js/backstretch.js"></script>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.min.css"> 
    <!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="css/ie8-and-down.css" />
    <![endif]-->
</head>
<body>
	<h1 class="u-title">Forgot Password ?</h1>
	<p class="p-forgot">If you have forgot the account password, you can <strong>reset password</strong> 
        and a new password will be sent to your email address.</p>
	
	<div id="forgot-pass">
    
    
        <!-- this div (.error-box-forgot) is reserved for the error / confirm messages. If an error will occur during the password recovery process the error msg here will be shown -->
		<?php

    		if(!empty($err))  {
    		   echo "<div class=\"arrow_box error-forgot\">";
    		  foreach ($err as $e) {
    		    echo "$e <br>";
    		    }
    		  echo "</div>";	
    		}
           
		   if(!empty($msg))  {
		    echo "<div class=\"msg\" style=\"margin-bottom:30px\">" . $msg[0] . "</div>";
	
		   }	  
		 ?>
         <?php if(empty($msg)) { ?>


		
	    <form action="forgot.php" method="post" name="actForm" id="actForm" >
	
		    <input type="text" value="Your Email" id="txtboxn" class="u-input" name="user_email" onblur="if (this.value == '') {this.value = 'Your Email';}"  onfocus="if (this.value == 'Your Email') {this.value = '';}" />

		    <input name="doReset" type="submit" id="doLogin3" class="button orange" value="Reset Password" style="display:block;margin:18px auto 0 auto;">
        
        </form>

        <?php } ?>
            	
	</div> <!-- #forgot-pass -->
        <script type="text/javascript">
    $(document).ready(function(){

    /* 
    load the background image with the backstretch jQuery plugin 
    plugin homepage: http://srobbin.com/jquery-plugins/backstretch/
    */
    $.backstretch("_img/background.png");

    });
    </script>
</body>
</html>
