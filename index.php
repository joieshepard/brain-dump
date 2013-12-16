<?php

// if the user is logged in sends to myapp.php
if(($_COOKIE['user_appv2']==='webapp')){
  header("Location: myapp.php");
}
?>
<!DOCTYPE html>

<html>
<head>
    <title>Brain Dump</title>
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
<?php echo $_COOKIE['user_app'];?>
  <h1 class="u-title"><!--<img src="_img/logo-big.png" />-->Brain Dump</h1>

  <div id="reg-form">

    <form>

        <input type="text" value="Username" id="Username" class="u-input" name="Username" onblur="if (this.value == '') {this.value = 'Username';}"  onfocus="if (this.value == 'Username') {this.value = '';}" />
        <div class="arrow_box error-username"></div>

        <input type="text" value="Email" id="UserEmail" class="u-input" name="UserEmail" onblur="if (this.value == '') {this.value = 'Email';}"  onfocus="if (this.value == 'Email') {this.value = '';}" />
        <div class="arrow_box error-email"></div>
    
        <input type="text" name="Password" id="fakepassword" class="u-input" value="Password" onfocus="pwdFocus()" />
        <input style="display: none" type="password" name="Password" id="Password" class="u-input" value="" onblur="pwdBlur()" />
        <div class="arrow_box error-password"></div>

        <input type="submit" id="submit" name="submit" value="Get Started !" class="button orange" style="float:right;margin-right:20px;">
    </form>
    <a href="login.php" title="Already have an account?" class="block-text" style="float:left;margin-top: 2em;">Already have an account ?</a>
  </div> <!-- #reg-form end -->
    
<script>
$(document).ready(function(){

/* 
    load the background image with the backstretch jQuery plugin 
    plugin homepage: http://srobbin.com/jquery-plugins/backstretch/
*/
$.backstretch("_img/background.png");


/*

    Checks the availability of the email address on the fly (on focus out)
    this is a pre validation, shows instant feedback to the user.
    shows that the chosen email address is available or not.
    if it's available the input field will be green, if it's taken an error sign will appear

*/

$('#UserEmail').focusout(function() {

    // hides the error sign
    $("#UserEmail").removeClass('val-ok');

    // validates the email address
    var hasError = false;
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,6})?$/;

    var emailaddressVal = $("#UserEmail").val();
    if (emailaddressVal == '') {
        // if the input field is empty shows error msg
        $(".arrow_box.error-email").show().text("Please enter your email address");
        hasError = true;
    } else if (!emailReg.test(emailaddressVal)) {
        // if the input field contains an email address but it's invalid shows error msg
        $(".arrow_box.error-email").show().text("Enter a valid email address.");
        hasError = true;
    }

    if (hasError == true) {
        return false;
    }

    // if the email address is valid sends and checks the availability
    var dataString = 'email_quick=' + emailaddressVal;

    $.ajax({
        type: "POST",
        url: "registration.php",
        data: dataString,
        success: function(data) {
            if (data.toString() == 'fail') {

                // if email address already exists in db shows error msg
                $(".arrow_box.error-email").show().text("This email is already registered");

            } else {

                // if the email address is available makes the input green
                $(".arrow_box.error-email").hide();
                $("#UserEmail").addClass('val-ok');

            }

        }
    });
    return false;

});

/*

    Checks the availability of the username on the fly (on focus out)
    this is a pre validation, shows instant feedback to the user.
    shows that the chosen username is available or not.
    if it's available the input field will be green, if it's taken an error sign will appear

*/

$('#Username').focusout(function() {

    // hides the error sign
    $("#Username").removeClass('val-ok');

    var username = $("#Username").val();

    // check username's length if its shorter than 5 characters error msg will appear
    if (username.length < 5) {

        $(".arrow_box.error-username").show().text("Username is too short!");

        return false;
    }

    // validates the username
    var hasError = false;
    var usernameReg = /^[a-z0-9]+$/;

    if (username == '') {
        // if the input field is empty shows error msg
        $(".arrow_box.error-username").show().text("Please enter an username");
        hasError = true;
    } else if (!usernameReg.test(username)) {
        // if the input field contains an username but it's invalid shows error msg
        $(".arrow_box.error-username").show().text("Enter a valid username.");
        hasError = true;
    }

    if (hasError == true) {
        return false;
    }

    // if the username is valid sends and checks the availability
    var dataString = 'username_quick=' + username;

    $.ajax({
        type: "POST",
        url: "registration.php",
        data: dataString,
        success: function(data) {
            if (data.toString() == 'fail') {
                // if username already exists in db shows error msg
                $(".arrow_box.error-username").show().text("This username is already registered");
            } else {

                // if the username is available makes the input green
                $(".arrow_box.error-username").hide();
                $("#Username").addClass('val-ok');

            }

        }
    });
    return false;

});

/*

    Checks the validity of the password on the fly (on focus out)
    this is a pre validation, shows instant feedback to the user.
    shows that the chosen password is valid or not.
    if it's valid the input field will be green, if it's invalid an error sign will appear

*/

$('#Password').focusout(function() {

    // hides the error sign
    $("#Password").removeClass('val-ok');

    var password = $("#Password").val();

    // check password's length if its shorter than 5 characters error msg will appear
    if (password.length < 5) {

        $(".arrow_box.error-password").show().text("Password is too short!");

        return false;

    } else {

        // if the password is valid makes the input green
        $(".arrow_box.error-password").hide();
        $("#Password").addClass('val-ok');

    }

});

/*

    Sends the values from the registration inputs
    Checks and validates everything once again to make sure only valid data will be posted to database
    if everything was OK a thank you message will appear

*/

$('#reg-form form').submit(function() {

    //hides the alert boxes if they are shown
    $(".error-box-username").hide();
    $(".error-box-email").hide();
    $(".error-box-password").hide();

    // validates the username
    //if the User not added a username show warning message
    var username = $("#Username").val();

    // check username's length if its shorter than 5 characters error msg will appear
    if (username.length < 5) {

        $(".error-box-username").show().text("Username is too short!");

        return false;
    }

    var hasError = false;
    var usernameReg = /^[a-z0-9]+$/;

    if (username == '') {
        // if the input field is empty shows error msg
        $(".error-box-username").show().text("Please enter an username");
        hasError = true;
    } else if (!usernameReg.test(username)) {
        // if the input field contains an username but it's invalid shows error msg
        $(".error-box-username").show().text("Enter a valid username.");
        hasError = true;

    } else {

        // if the username is valid makes the input green
        $(".error-box-username").hide();
    }

    if (hasError == true) {
        return false;
    }

    // validates the email address

    var hasError = false;
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,6})?$/;

    var emailaddressVal = $("#UserEmail").val();
    if (emailaddressVal == '') {
        // if the input field is empty shows error msg
        $(".error-box-email").show().text("Please enter your email address");
        hasError = true;
    } else if (!emailReg.test(emailaddressVal)) {
        // if the input field contains an username but it's invalid shows error msg
        $(".error-box-email").show().text("Enter a valid email address.");
        hasError = true;
    } else {

        // if the email address is valid makes the input green
        $(".error-box-email").hide();
    }

    if (hasError == true) {
        return false;
    }

    // Validating password
    var password = $("#Password").val();

    // check password's length if its shorter than 5 characters error msg will appear
    if (password.length < 5) {

        $(".error-box-password").show().text("Password is too short!");

        return false;
    } else {

        // if the password is valid makes the input green
        $(".error-box-password").hide();
    }
    

    // if the username,email address and the password  is valid sends the data to registration.php and checks the username and email address availability
    var dataString = 'username=' + username + '&email=' + emailaddressVal + '&password=' + password;

    $.ajax({
        type: "POST",
        url: "registration.php",
        data: dataString,
        success: function(data) {
            if (data.toString() == 'fail_email') {
                // if the email already exists in db an error message will appear
                $(".error-box-email").show().text("This email is already registered");

            } else if (data.toString() == 'fail_username') {
                // if the username already exists in db an error message will appear
                $(".error-box-username").show().text("This username is already registered");

            } else {

                // on success / if registration succeed
                $('#reg-form form').hide(); //hides form
                $('.block-text').hide(); //hide Already have an account ? text
                
                $('#reg-form').append('<p class="wellcome">Thanks for signing up to Todo app! <a href="login.php">Please login to your account</a></p>'); // shows a confirmation msg

                }

        }
    });

    return false;

});

});
</script>

</body>
</html>

