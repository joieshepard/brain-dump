<?php 
// include database settings
include 'dbc.php';
page_protect();

$query_set = mysql_query("SELECT * FROM users where id='$_SESSION[user_id]'"); 
if($query_set === FALSE) {
    die(mysql_error());
}
while($row = mysql_fetch_array($query_set)){

$name = $row['full_name'];

}

?>
<!DOCTYPE html>

<html>
<head>
    <title><?php echo $name?>'s Brain Dump</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content=" " />
    <meta name="keywords" content="" />

    <!-- CSS STYLES -->
    <link rel="stylesheet" href="_css/reset.css">
    <link rel="stylesheet" href="_css/style.min.css">    
    <link rel="stylesheet" href="_css/font-awesome.css">   

    <!-- LATEST jQuery -->
    <script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
    <script src="_js/jquery.mobile-events.min.js"></script>
    
    <!-- LATEST jQuery UI -->
    <script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
    
    <script src="_js/jquery.transit.min.js"></script>
    <script src="_js/main.js"></script>
</head>
<body class="app-default">
    
  <div id="header-navigation">
  
  	<div id="menu-btn"></div>
    
    <div id="nav-logo"><!--<img src="_img/logo.png" />-->Brain Dump</div>
    
    <!--Pattern HTML-->
    <div id="user-panel" class="pattern">
    <!--Begin Pattern HTML-->
		<a href="#menu" class="menu-link">Settings</a>
		<nav id="menu" role="navigation">
			<ul>
				<li><a href="#"><i class="icon-user"></i>Welcome, <?php echo $name; ?></a></li>
				<?php if($_SESSION['user_id'] == '659'){
				echo '<li class="js-iframe"><a href="users.php"><i class="icon-cogs"></i>Users</a></li>';
				} ?>
				<li class="js-iframe"><a href="settings.php"><i class="icon-cogs"></i>Settings</a></li>
				
				<li><a href="logout.php"><i class="icon-remove-sign"></i>Logout</a></li>
			</ul>
		</nav>
	</div>
    <!--End Pattern HTML-->
    
  </div> <!-- #header-navigation end -->

    <div id="navigation">
      <div id="nav-inner">

        <!--

            SEARCH FUNCTION

        -->


        <div id="search">
        
        	<div class="search-icon"></div>

          <input type="text" id="search-field" placeholder="Search..."/>

          <div id="search-delete"><i class="icon-remove"></i></div>

        </div> <!-- #serach end -->


        <!--

            ECHO CATEGORIES

        -->

        <ul class="category-ul">
           <?php $query = mysql_query("SELECT * FROM categories WHERE id_user='$_SESSION[user_id]' AND category='inbox' LIMIT 1");
            while($row = mysql_fetch_array($query)){ ?>
            	<li id="catitem_<?php echo $row['id']; ?>" data-id="<?php echo $row['id']; ?>" data-category="<?php echo $row['category']; ?>"><span class="edit-cat"><i class="icon-reorder"></i></span><input type="text" data-categoryid="<?php echo $row['id']; ?>" value="<?php echo $row['category']; ?>" name="category_name" readonly="readonly"/><span class="delete-cat"><i class="icon-remove"></i></span></li>
            <?php } ?>
            <ul id="sortable-category" class="category-sec serialize-cat">
            <?php $query = mysql_query("SELECT * FROM categories WHERE id_user='$_SESSION[user_id]' ORDER BY sortid ASC");
            while($row = mysql_fetch_array($query)){ ?>
        
            <li id="catitem_<?php echo $row['id']; ?>" data-id="<?php echo $row['id']; ?>" data-category="<?php echo $row['category']; ?>"><span class="edit-cat"><i class="icon-reorder"></i></span><input type="text" data-categoryid="<?php echo $row['id']; ?>" value="<?php echo $row['category']; ?>" name="category_name" readonly="readonly"/><span class="delete-cat"><i class="icon-remove"></i></span></li>
        
            <?php } ?>
            </ul>
        </ul>
        <div id="add-category">+ Add new category</div>
        
      </div> <!-- #nav-inner end -->
    </div> <!-- #navigation end -->


    <!--

        MAKE A NEW CATEGORY

    -->
    <div class="add-category"></div>

  <div class="app-background">
	  <div class="app-inner">
	  
	  	<div class="category-name"><span>Inbox</span></div><!-- .category-name end -->

	  	
	  	<div class="category-holder">
	  	
	  		<div class="category-title">
		  		<div class="category-title-left"></div>
		  		<div class="category-title-center"><img src="_img/standard_icon.png" /><span>Inbox</span></div>
		  		<div class="category-title-right" onClick="window.print()"></div>
	  		</div>
	  		
	  		<div class="todo-bg">
	  		
	  		
	  		
				  <!--
				
				        MAKE A NEW TODO
				
				  -->
				
				  <div id="add">
				    <form id="addtodo">
				        <input type="text" id="addtodoinput" name="addtodoinput" />
				
				        <input type="submit" value="" />
				    </form>
				  </div> <!-- #add end -->
				  
				  
				  <!--
				
				        ECHO TODOS
				
				  -->
				  
				  
				  <!--  todos -->
				  <div id="app">
				    <form id="save">
				        <ul class="mainul serialize" id="sortable">
				            <?php $query = mysql_query("SELECT * FROM todo where uid='$_SESSION[user_id]' and archive='0' ORDER BY sortid ASC "); // Selects the todos
				            while($row = mysql_fetch_array($query)){ ?>
				        
			            <li style="display:none" id="item_<?php echo $row['id']; ?>" data-todoid="<?php echo $row['id']; ?>" data-category="<?php echo $row['category']; ?>" class="ui-state-default"><div class="container"><span class="sort-icon">&#9776;</span><div class="print-box"></div><input type="text" name="edit" class="todoname" value="<?php echo $row['todo']; ?>"  /><span class="to-find"><?php echo $row['todo'];?></span><span class="delete"></span></div></li>
			      
				            <?php } ?>
				        </ul>
				        <input type="submit" />
				    </form>
				  </div> <!-- #app end -->
				  
				  <div class="friend-icon"></div>
                                <div id="friend">
                                    <ul>
                                    <?php $query = mysql_query("SELECT full_name, id FROM users ORDER BY full_name ASC "); // Selects friends
				        while($row = mysql_fetch_array($query)){ ?>
				        
			            <li data-todoid="<?php echo $row['id']; ?>" data-category="<?php echo $row['full_name']; ?>" class="ui-state-default"><div class="container"><div class="print-box"></div><span class="todoname todo-title"><?php echo $row['full_name']; ?></span><span class="to-find"><?php echo $row['full_name'];?></span><span class="todo-archived"></span><span class="delete-archived"></span></div></li>
				    <?php } ?>
                                    </ul>
                                </div> <!-- #archived end -->
	  		
	  		
		  		<div class="archive-icon"></div>
                                <div id="archived">
                                    <ul>
                                    <?php $query = mysql_query("SELECT * FROM todo where uid='$_SESSION[user_id]' and archive='1' ORDER BY sortid ASC "); // Selects the todos
				        while($row = mysql_fetch_array($query)){ ?>
				        
			            <li style="display:none" data-todoid="<?php echo $row['id']; ?>" data-category="<?php echo $row['category']; ?>" class="ui-state-default"><div class="container"><div class="print-box"></div><span class="todoname todo-title"><?php echo $row['todo']; ?></span><span class="to-find"><?php echo $row['todo'];?></span><span class="todo-archived"></span><span class="delete-archived"></span></div></li>
			      
				    <?php } ?>
                                    </ul>
                                </div><!-- #archived end -->
		  </div> <!-- .todo-bg end -->
	  	
	  	
	  	
	  	</div><!-- .category-holder end -->

		  
		  
		  
		  
	  </div> <!-- .app-inner end -->
	    
  
  </div><!-- .app-background end -->




    <!--

        ELEMENTS FOR THE SETTINGS POP UP

    -->

    <div id="overlay"></div>   
    
    <div id="load-panel">
    
      <div class="title"><span></span><div class="close"></div></div>
      <div id="loading"></div>
      <div id="load-iframe"></div>
    
    </div> <!-- #load-panel end -->
    
<script type="text/javascript">
$(window).resize(function() {
    main.resize();
});
</script>
</body>
</html>
