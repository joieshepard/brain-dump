$(document).ready(function() {
	main = {
		init: function() {
			//hide delete icon & edit name icon from the default category (inbox)
			//the inbox category is a default category what you can't delete
			$('.category-sec li[data-category="inbox"]').hide();
			$('.category-ul li').first().find('.delete-cat').remove().end().find('.edit-cat').remove();
			//specify the category on page load
			category = "inbox";
			//show the todos in inbox category
			$('#app li[data-category="inbox"]').show();
			$('#archived li[data-category="inbox"]').show();
			$('.category-ul li[data-category="inbox"]').addClass('selected');
			//select category
			$('.category-ul').on('tap', 'li', function() {
				$('.category-ul li').removeClass('selected');
				$(this).addClass('selected');
				category = $(this).attr('data-category');
				$('#app li').hide();
				$('#archived li').hide();
				$('#app li[data-category="' + category + '"]').show();
				$('#archived li[data-category="' + category + '"]').show();
				$(".category-title-center span").html(category);
				$(".category-name span").html(category);
			});
			$('#search-field').focus(function() {
				myVar = setInterval(function() {
					var searchVal = $('#search-field').val();
					if (searchVal.length > 0) {
						$(".mainul li[data-category='" + category + "']").hide();
						$('#search-delete').show();
					}
					if (searchVal.length == 0) {
						$(".mainul li[data-category='" + category + "']").show();
						$('#search-delete').hide();
					}
					$(".mainul li[data-category='" + category + "'] span:contains(" + searchVal + ")").parent().parent().show();
				}, 1);
			});
			$('#search-field').focusout(function() {
				var searchVal = $('#search-field').val();
				if (searchVal.length == 0) {
					$(".mainul li[data-category='" + category + "']").show();
					$('#search-delete').hide();
					clearInterval(myVar);
				}
			});
			//delete search inputs value
			$('#search-delete').on('click', function() {
				$('#search-field').attr('value', '');
			});
			//delete category
			$('.category-ul').on('click', '.delete-cat', function() {
				var toDelete = $(this).parent().data('id');
				var toDeleteCategory = $(this).parent().data('category');
				$(this).parent().hide();
				$.ajax({
					type: "POST",
					url: "app_req.php",
					dataType: 'json',
					data: "deleteCat=" + encodeURIComponent(toDelete) + "& delCategory=" + encodeURIComponent(toDeleteCategory),
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					success: function(data) {
						//show default category (inbox) if the delete was successful
						$('#app li').hide();
						$('#app li[data-category="inbox"]').show();
						//when category was deleted 
						$('.category-ul li[data-category="inbox"]').addClass("selected");
						$(".category-title-center span").html("index");
						$(".category-name span").html("index");
					}
				});
			});
			// basic jQuery UI sort function
			// docs: http://jqueryui.com/sortable/
			$("#sortable").sortable({
				revert: true,
				containment: '.mainul',
				start: function(event, ui) {},
				update: function(event, ui) {
					serial = $(".serialize").sortable('serialize');
					$.ajax({
						url: "app_req.php",
						type: "post",
						data: serial
					});
				}
			});
			//Sort Categories
			// basic jQuery UI sort function
			// docs: http://jqueryui.com/sortable/
			$("#sortable-category").sortable({
				revert: true,
				containment: '.category-sec',
				start: function(event, ui) {},
				update: function(event, ui) {
					serial = $(".serialize-cat").sortable('serialize');
					$.ajax({
						url: "app_req.php",
						type: "post",
						data: serial
					});
				}
			});
			//check if mobile function

			function _isMobile() {
				var isMobile = (/iphone|ipod|android|ie|blackberry|fennec/).test(navigator.userAgent.toLowerCase());
				return isMobile;
			}
			//if mobile use the doubletap event
			if (_isMobile()) {
				var clickEvent = "doubletap";
			} else {
				var clickEvent = "dblclick";
			}
			//edit category name
			$('.category-sec').on(clickEvent, 'input', function() {
				$(this).removeAttr('readonly').addClass("edit");
				$(this).focusout(function() {
					$(this).attr('readonly', true).removeClass("edit");
					var oldValue = $(this).parent().attr('data-category');
					var value = $(this).val();
					category = $(this).val();
					$(this).attr("value", value);
					$('#app li[data-category="' + oldValue + '"]').attr("data-category", category);
					var id = $(this).data('categoryid');
					$(this).parent().attr("data-category", value);
					$(".category-title-center span").html(category);
					$(".category-name span").html(category);
					$.ajax({
						type: "POST",
						url: "app_req.php",
						dataType: 'json',
						data: "category_name=" + encodeURIComponent(value) + "&categoryid=" + encodeURIComponent(id) + "&oldValue=" + encodeURIComponent(oldValue),
						contentType: "application/x-www-form-urlencoded; charset=UTF-8",
						success: function(data) {
							//success callback
						}
					});
				});
			});
			// add new todo
			$('#addtodo').submit(function(e) {
				var todo = $("#addtodoinput").val();
				if (todo.length == 0) {
					return false;
				}
				$("#addtodoinput").val("");
				$.ajax({
					type: "POST",
					url: "app_req.php",
					dataType: 'json',
					data: "todo=" + encodeURIComponent(todo) + "& category=" + encodeURIComponent(category),
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					success: function(data) {
						$("#app ul.mainul").append('<li id="item_' + data.todoid + '" style="display:block" data-todoid="' + data.todoid + '" data-category="' + data.category + '" ><div class="container" style="width:' + add + 'px"><span class="sort-icon">&#9776;</span><input type="text" name="edit" class="todoname" value="' + data.todo + '"/><span class="to-find">' + data.todo + '</span><span class="delete"></span></div></li>');
					}
				});
				e.preventDefault();
			});
			//show archived tasks
			$(".archive-icon").on("click", function() {
				$("#archived").slideToggle(50);
			});
			//make a new category
			$("#add-category").on("click", function(e) {
				$.ajax({
					type: "POST",
					url: "app_req.php",
					dataType: 'json',
					data: "makecategory=true",
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					success: function(data) {
						$('.category-sec').append('<li data-id="' + data.id + '" data-category="' + data.category + '"><span class="edit-cat"><i class="icon-reorder"></i></span><input type="text" data-categoryid="' + data.id + '" value="' + data.category + '" name="category_name" readonly="readonly"/><span class="delete-cat"><i class="icon-remove"></i></span></li>');
					}
				});
				e.preventDefault();
			});
			// archive todo
			$('#app ul').on('click', '.delete', function() {
				$(this).parent().parent("li").slideUp("slow").remove();
				var todoid = $(this).parent().parent("li").data("todoid");
				$.ajax({
					type: "POST",
					url: "app_req.php",
					dataType: 'json',
					data: "archive_todo=archive&todoid=" + todoid,
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					success: function(data) {
						$('#archived ul').append('<li data-todoid="' + data.todoid + '" data-category="' + data.category + '" class="ui-state-default"><div class="container"><div class="print-box"></div><span class="todoname todo-title">' + data.todo + '</span><span class="to-find">' + data.todo + '</span><span class="todo-archived"></span><span class="delete-archived"></span></div></li>');
						
						main.resize();
					}
				});
			});
			//de archive todo
			$('#archived ul').on('click', '.todo-archived', function() {
				$(this).parent().parent("li").slideUp("slow").remove();
				var todoid = $(this).parent().parent("li").data("todoid");
				$.ajax({
					type: "POST",
					url: "app_req.php",
					dataType: 'json',
					data: "dearchive_todo=archive&todoid=" + todoid,
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					success: function(data) {
						$("#app ul.mainul").append('<li id="item_' + data.todoid + '" style="display:block" data-todoid="' + data.todoid + '" data-category="' + data.category + '" ><div class="container" style="width:' + add + 'px"><span class="sort-icon">&#9776;</span><input type="text" name="edit" class="todoname" value="' + data.todo + '"/><span class="to-find">' + data.todo + '</span><span class="delete"></span></div></li>');
						resize();
					}
				});
			});
			// delete todo
			$('#archived ul').on('click', '.delete-archived', function() {
				$(this).parent().parent("li").slideUp("slow").remove();
				var todoid = $(this).parent().parent("li").data("todoid");
				$.post("app_req.php", {
					delete_todo: "delete",
					todoid: todoid
				});
			});
			// Close/hides the opened panel
			$('.close').on('click', function() {
				$('#overlay').hide();
				$('#load-panel').hide();
				$('#loading').show();
			});
			// loads the url to the iframe
			$('li.js-iframe').on('click', function(e) {
				$('#overlay').show();
				$('#load-panel').show();
				var href = $(this).find('a').attr('href');
				var title = $(this).text();
				$('#load-panel .title span').text(title);
				$('#load-iframe').load(href, function() {
					$('#loading').hide();
				});
				e.preventDefault();
			});
			// edit todos name
			$(".mainul input[type='text']").on('click', function() {
				$(this).addClass("edited");
			});
			$(".mainul input[type='text']").focusin(function() {
				$(this).addClass("edited");
			}).focusout(function(e) {
				var value = $('.edited').val();
				var data = $('.edited').parent().parent().data('todoid');
				$(".mainul input[type='text']").removeClass("edited");
				$.post("app_req.php", {
					edited: data,
					newname: value
				});
				e.preventDefault();
			});
			$('#save').submit(function(e) {
				e.preventDefault();
			});
			//toggle settings menu - responsive stuff
			$('body').addClass('js');
			var $menu = $('#menu'),
				$menulink = $('.menu-link');
			$menulink.click(function() {
				$menulink.toggleClass('active');
				$menu.toggleClass('active');
				return false;
			});
			//on search input focus change the search icon	
			$('#search-field').focusin(

			function() {
				$(this).prev("div").addClass("search-focus");
			}).focusout(

			function() {
				$(this).prev("div").removeClass("search-focus");
			});
			//show menu on small screen size
			$("#menu-btn").on('click', function() {
				if ($("#navigation").css("left") == "0px") {
					$("#navigation").transition({
						left: -233
					}, 500, 'out');
					$(".app-background").transition({
						right: 0
					}, 500, 'out');
				} else {
					$("#navigation").transition({
						left: 0
					}, 500, 'in');
					$(".app-background").transition({
						right: -233
					}, 500, 'in');
				}
			});
		},
		searchTodo: function() {
			// Search function 
			// uses the jQuery contains function but this is a little improved version becouse it's case insensitive
			$.expr[":"].contains = $.expr.createPseudo(function(arg) {
				return function(elem) {
					return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
				};
			});
		},
		resize: function() {
			var wWidth = $(window).width(); //get the window width
	
			$(".app-background").css('width',(wWidth-263));
			
			var todoBg = $(".todo-bg").width();
			$(".category-title-center").css('width',(todoBg-56));
			
			add = $("#add").width();
			$("#addtodoinput").css('width',(add-20));
			$(".container").css('width',add);
			
			var loadPanel = $("#load-panel").width();
			$("#load-panel").css("margin-left",-(loadPanel/2));
		}
	} //main
	main.init();
	main.resize();
});