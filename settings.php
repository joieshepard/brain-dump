<?php
// include database settings
include 'dbc.php';
page_protect();


$query_set = mysql_query("SELECT * FROM users where id='$_SESSION[user_id]'"); 
if($query_set === FALSE) {
    die(mysql_error());
}
while($row = mysql_fetch_array($query_set)){?>
<p>Here you can change some personal stuff.
</p>
<form id="settings-from">
	<ul id="settings-ul">
    	<li>Full Name:</li>
    	<li><input type="text" name="settings-name" value="<?php echo $row['full_name']; ?>" /></li>
        
    	<li>Your Email:</li>
    	<li><input type="text" value="<?php echo $row['user_email']; ?>" disabled="disabled" /></li>
        
    	<li>Current Password:</li>
    	<li><input type="text" id="settings-pwd" name="settings-pwd" value="" /></li>
        
        <li>New Password:</li>
    	<li><input type="text" id="settings-new-pwd" name="settings-new-pwd" value="" /></li>
        
    	<li><input type="submit" id="submit-settings" name="submit-settings" value="Save" class="u-green-btn"> <div class="ajaxpost"></div></li>
	</ul>
</form>
<?php } ?>

<script type="text/javascript">
$(document).ready(function() {
	$('#settings-from').submit(function(e){
    
        //remove error sign
        $('.error-sign').remove();

        // password validation
        var oldpassword = $("#settings-pwd").val(); //current password
        var newpassword = $("#settings-new-pwd").val(); //new password
        
        if(oldpassword.length == 0) { // if current password field is empty but the new password field isn't, show error
            if(newpassword.length > 0) {
                $('#settings-from').append('<span class="error-sign">please fill out the current password field</span>');
                return false;
            }
        }
        
        if(oldpassword.length > 0) { // if the current password field has some characters
        
            if (newpassword.length == 0) { // if the new password field is empty show error
                $('#settings-from').append('<span class="error-sign">new password field is empty</span>');
                return false;
            }else if(newpassword.length < 5 ) { // if the new password field has less than 5 characters show error
                $('#settings-from').append('<span class="error-sign">your new password is too short</span>');
                return false;
            
            }
        
        
        
        }
        
        
        $('.ajaxpost').css('display','inline-block');
	    var data = $(this).serialize();

	    $.ajax({
            type: "POST",
            url: "app_req.php",
            data: data,
            success: function(data) {
              if(data == 'pwdng'){
                //if the posted current password and the current password in databse don't match show error
                $('#settings-from').append('<span class="error-sign">your current password not match</span>');
              }else {
                //password was changed
                $('#submit-settings').val('Saved!');
                setTimeout(function(){$('#submit-settings').val('Saved');},1500);
              }
              $('.ajaxpost').hide();
            }
        });

	    e.preventDefault();
	});
});
</script>