<?php
//includes the database settings
include 'dbc.php';


// quick pre validation for the email address
// Checks if the posted email address already exists or not - returns fail or ok
// fail - means it already exists in db 
// ok - means it doesn't exists in db
if($_POST['email_quick']) { 

    $qemail = Trim(stripslashes($_POST['email_quick'])); 
    
    $resulte = mysql_query("select count(*) as total from users where user_email='$qemail'") or die(mysql_error());
    list($total) = mysql_fetch_row($resulte);
    
    if ($total > 0)
    {
    
        echo "fail";
    
    } else {
    
        echo "ok";
    
    }

}

// quick pre validation for the username
// Checks if the posted username already exists or not - returns fail or ok
// fail - means it already exists in db 
// ok - means it doesn't exists in db
if($_POST['username_quick']) { 

    $qusername = Trim(stripslashes($_POST['username_quick'])); 
    
    $resultu = mysql_query("select count(*) as total from users where user_name='$qusername'") or die(mysql_error());
    list($total) = mysql_fetch_row($resultu);
    
    if ($total > 0)
    {
    
        echo "fail";
    
    } else {
    
        echo "ok";
    
    }


}


// main validation this will only run if the user clicks on the Get Started! button
// Checks if the posted username and email already exists or not
// if they not exists in the db creates the user and sends a confirmation email to the inputted email address.

if($_POST['username']) 
{ 

    // gets the posted data
    $email = Trim(stripslashes($_POST['email'])); 
    $username = Trim(stripslashes($_POST['username'])); 
    $password = Trim(stripslashes($_POST['password'])); 
    
    // hashes the password to make it secure
    $hashpass = PwdHash($password);
    
    $resultse = mysql_query("select count(*) as total from users where user_email='$email'") or die(mysql_error());
    list($total) = mysql_fetch_row($resultse);
    $resultsu = mysql_query("select count(*) as total from users where user_name='$username'") or die(mysql_error());
    list($total2) = mysql_fetch_row($resultsu);
    
    if ($total > 0)
    {
       echo "fail_email"; // returns fail_email when email address already exists in db
    
    }elseif ($total2 > 0) {
    
    	echo "fail_username"; // returns fail_username when email address already exists in db
        
    }else {
    
        // email address and username are NOT exists in the db
        // creates the user
        
        
        // Inserts the user info
        $sql_insert = "INSERT into `users`
          			(`full_name`,`user_name`,`user_email`,`pwd`,`date`
        			)
        		    VALUES
        		    ('Anonymus','$username','$email','$hashpass',now()
        			)
        			";
                    
        mysql_query($sql_insert) or die("Insertion Failed:" . mysql_error());
        
        $id = mysql_insert_id();
        
        // Creates the first todo
        $sql_insert_design = "INSERT into `todo`
              		(`uid`,`todo`,`category`
        			)
        		    VALUES
        		    ('$id','First todo','inbox'
        			)
        			";
                    
        // Creates the first category
         $sql_insert_category = "INSERT into `categories`
                    (`id_user`,`category`
                    )
                    VALUES
                    ('$id','inbox'
                    )
                    ";
        			
        
        mysql_query($sql_insert_design) or die("Insertion Failed:" . mysql_error());
        mysql_query($sql_insert_category) or die("Insertion Failed:" . mysql_error());
        
        
        
        // sends confirmation email to the added email address 
        
        $message = 
        "Hello $username , \n
Thank you for registering with Brain Dump. Using Brain Dump is going to change the way you organize your life. \n
To get started, simply login in to the app and dump your brain away. \n
        
        The Brain Dump Team
        ";
        
        	mail($email, "Welcome to Brain Dump", $message,
            "From: \"Brain Dump\" <auto-reply@braindump.com>\r\n" .
             "X-Mailer: PHP/" . phpversion());
        
        
    	 
    } 




}

?>