
// building select nav for mobile width only
$(function(){
	// building select menu
	$('<select />').appendTo('nav');

	// building an option for select menu
	$('<option />', {
		'selected': 'selected',
		'value' : '',
		'text': 'Choise Page...'
	}).appendTo('nav select');

	$('nav ul li a').each(function(){
		var target = $(this);

		$('<option />', {
			'value' : target.attr('href'),
			'text': target.text()
		}).appendTo('nav select');

	});

	// on clicking on link
	$('nav select').on('change',function(){
		window.location = $(this).find('option:selected').val();
	});
});
//end

// show and hide sub menu
$(function(){
	$('nav ul li').hover(
		function () {
			//show its submenu
			$('ul', this).slideDown(150);
		}, 
		function () {
			//hide its submenu
			$('ul', this).slideUp(150);			
		}
	);
});
$(document).ready(function(){
				$("#accordian a").click(function(){
					var link = $(this);
					var closest_ul = link.closest("ul");
					var parallel_active_links = closest_ul.find(".active")
					var closest_li = link.closest("li");
					var link_status = closest_li.hasClass("active");
					var count = 0;

					closest_ul.find("ul").slideUp(function(){
						if(++count == closest_ul.find("ul").length)
							parallel_active_links.removeClass("active");
					});

					if(!link_status)
					{
						closest_li.children("ul").slideDown();
						closest_li.addClass("active");
					}
				})
			})
//end

