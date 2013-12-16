<?php 
// include database settings
include 'dbc.php';
page_protect();

// variable for making a new todo
$todo  = $_POST['todo'];
$category  = $_POST['category'];

//make new category
$makeCategory  = $_POST['makecategory'];

//edit category name  
$categoryName  = $_POST['category_name'];
$categoryId  = $_POST['categoryid'];
$categoryNameOld  = $_POST['oldValue'];


//delete category
$deleteCat  = $_POST['deleteCat'];
$delCatName  = $_POST['delCategory'];

//archive a todo
$todoid  = $_POST['todoid'];


//edit todo name
$edited = $_POST['edited'];
$value = $_POST['newname'];

//variable for todo sorting
$item = $_POST['item'];

//category sorting
$catitem = $_POST['catitem'];



//variables for user profile
$settingsName  = $_POST['settings-name']; // full name from the settings form
$settingsPwd  = $_POST['settings-pwd']; // current pwd from the settings form
$settingsPwdNew  = $_POST['settings-new-pwd']; // new pwd from the settings form




// this adds new todo
if ( isset($_POST['todo']) ) {

	$date = date( "Y-m-d H:i:s" );
	$result = mysql_query("SELECT count(sortid) as total FROM `todo` WHERE uid='$_SESSION[user_id]'");
	$data=mysql_fetch_assoc($result);
	$total = $data['total']+1;
	mysql_query("INSERT INTO todo (uid, todo, date, sortid, category) VALUES ('$_SESSION[user_id]','$todo','$date','$total','$category')");
	$newtodoid = mysql_insert_id();

	echo json_encode(array('todo' => $todo, 'todoid' => $newtodoid, 'category' => $category,));

}


// this archive the todos
if ( isset($_POST['archive_todo']) ) {

    mysql_query("UPDATE todo SET archive='1' WHERE id='$todoid' AND uid='$_SESSION[user_id]'");
    $result = mysql_query("SELECT * FROM `todo` WHERE id='$todoid'");
    $data=mysql_fetch_assoc($result);
    $dataTodo = $data['todo'];
    $dataCategory = $data['category'];
    
    echo json_encode(array('todo' => $dataTodo, 'todoid' => $todoid, 'category' => $dataCategory));

}

// this dearchive the archived todos
if ( isset($_POST['dearchive_todo']) ) {

    $result = mysql_query("SELECT * FROM `todo` WHERE uid='$_SESSION[user_id]' AND id='$todoid'");
    $data=mysql_fetch_assoc($result);
    $dataId = $data['id'];
    $dataName = $data['todo'];
    $dataCategory = $data['category'];
    mysql_query("UPDATE todo SET archive='0' WHERE id='$todoid' AND uid='$_SESSION[user_id]'");
    echo json_encode(array('todo' => $dataName, 'todoid' => $dataId, 'category' => $dataCategory));

}

// this deletes the category
if ( isset($_POST['deleteCat']) ) {

    mysql_query("DELETE FROM categories  WHERE id='$deleteCat' and id_user='$_SESSION[user_id]'");
    mysql_query("DELETE FROM todo  WHERE category='$delCatName' and uid='$_SESSION[user_id]'");
    echo $todoid;

}

// this deletes the todos
if ( isset($_POST['delete_todo']) ) {

    mysql_query("DELETE FROM todo  WHERE id='$todoid' and uid='$_SESSION[user_id]'");
	echo $todoid;

}



//make new category
if ( isset($_POST['makecategory']) ) {

	$result = mysql_query("SELECT count(sortid) as total FROM `categories` WHERE id_user='$_SESSION[user_id]'");
	$data=mysql_fetch_assoc($result);
	$dataTest = $data['total'];
	$total = $data['total']+1;

    mysql_query("INSERT INTO categories (id_user, category, sortid) VALUES ('$_SESSION[user_id]','New Category','$total')");
    $id = mysql_insert_id();

    echo json_encode(array('category' => "New Category",'id' =>$id,'total' =>$dataTest));

}

//edit category name
if ( isset($_POST['category_name']) ) {

    // changes the category name
    mysql_query("UPDATE categories SET category='$categoryName' WHERE id='$categoryId'");

    $result = mysql_query("SELECT category FROM `todo` WHERE uid='$_SESSION[user_id]' AND category='$categoryNameOld'");

    //changes the category name for the todos
    mysql_query("UPDATE todo SET category='$categoryName' WHERE category='$categoryNameOld' AND uid='$_SESSION[user_id]'");

}



// sets the changes from the settings form  
if ( isset($_POST['settings-name']) ) { 
    $pwdLength = strlen($settingsPwd);
    if ( $pwdLength > 0 ) {
        $result = mysql_query("SELECT * FROM `users` WHERE id='$_SESSION[user_id]'");
        $data=mysql_fetch_assoc($result);
    	$pwd = $data['pwd'];
        
        if ($pwd === PwdHash($settingsPwd,substr($pwd,0,9))) { 
        // it's matches    
        $hashpass = PwdHash($settingsPwdNew);
        mysql_query("UPDATE users SET full_name='$settingsName',pwd='$hashpass' WHERE id='$_SESSION[user_id]'");
        
        }else {
        echo "pwdng"; // don't matches
    
        }
    
    }else {
    
    mysql_query("UPDATE users SET full_name='$settingsName' WHERE id='$_SESSION[user_id]'");
    
    }

	

}

// todo sorting
if ( isset($_POST['item']) ) {

    for ($i = 0; $i < count($item); $i++) {
        mysql_query("UPDATE `todo` SET `sortid`=" . $i . " WHERE `id`='" . $item[$i] . "'");
        echo $i;
        echo $item[$i];
    }

}

// category sorting
if ( isset($_POST['catitem']) ) {

    for ($i = 0; $i < count($catitem); $i++) {
        mysql_query("UPDATE `categories` SET `sortid`=" . $i . " WHERE `id`='" . $catitem[$i] . "'");
        echo $i;
        echo $catitem[$i];
    }

}


// todo edit name
if ( isset($_POST['edited']) ) {

    mysql_query("UPDATE todo SET todo='$value' WHERE id='$edited'");

}








?>