$(document).ready(function(){
				$("#landaTreeMenu a").click(function(){
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
                                                $(this).closest('ul ul').addClass('display:block;');
						closest_li.addClass("active");
					}
				})
			})