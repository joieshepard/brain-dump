<?php
// Database Connections


// databse connection
define ("DB_HOST","localhost"); // set database host
define ("DB_USER","root"); // set database user
define ("DB_PASS","root"); // set database password
define ("DB_NAME","Brain_Dump"); // set database name

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.");
$db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database");

define("COOKIE_TIME_OUT", 10); //specify cookie timeout in days (default is 10 days)

define('SALT_LENGTH', 9); // salt length for password - if you change the number (9) be sure that you change this number in the login.php and app_req.php

/* include the page_protect(); function on every page where you need maximum security. With the page_protect(); function included only the signed in users will see the content */
function page_protect() {
session_start();

global $db; 

/* Secure against Session Hijacking by checking user agent */
if (isset($_SESSION['HTTP_USER_AGENT']))
{
    if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']))
    {
        logout();
        exit;
    }
}


/* If session not set, check for cookies set by Remember me */
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name']) ) 
{
    if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_app'])){

		    $_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
            $_SESSION['user_id'] = $_COOKIE['user_id'];
		    $_SESSION['user_name'] = $_COOKIE['user_name'];
	


   } else {
	    header("Location: login.php");
      logout();
	    exit();
	}
}

}




// filter GET and POST data for security reasons.
// further read: http://bitly.com/PIVCUt
function filter($data) {
	$data = trim(htmlentities(strip_tags($data)));
	
	if (get_magic_quotes_gpc())
		$data = stripslashes($data);
	
	$data = mysql_real_escape_string($data);
	
	return $data;
}



// logout function this is used in the logout.php
function logout()
{

session_start();

/************ Delete the sessions ****************/
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['HTTP_USER_AGENT']);
session_unset();
session_destroy(); 

/* Delete the cookies *******************/
setcookie("user_id", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
setcookie("user_name", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
setcookie("user_app", '', time()-60*60*24*COOKIE_TIME_OUT, "/");

header("Location: login.php");
}



// salt generation to keep passwords safe
function PwdHash($pwd, $salt = null)
{
    if ($salt === null)     {
        $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    }
    else     {
        $salt = substr($salt, 0, SALT_LENGTH);
    }
    return $salt . sha1($pwd . $salt);
}

// password generation function used in forgot.php
function GenPwd($length = 7)
{
  $password = "";
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; //no vowels
  
  $i = 0; 
    
  while ($i < $length) { 

    
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
       
    
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  return $password;

}

?>