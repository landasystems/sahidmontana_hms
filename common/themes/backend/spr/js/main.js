//window resize events
$(window).resize(function() {
    //get the window size
    var wsize = $(window).width();
    if (wsize > 980) {
        $('.shortcuts.hided').removeClass('hided').attr("style", "");
        $('.sidenav.hided').removeClass('hided').attr("style", "");
    }

    var size = "Window size is:" + $(window).width();
    /*console.log(size)*/
});

// document ready function
$(document).ready(function() {
    $("body").on("keypress", ".angka", function (evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        // Validasi hanya tombol angka, kecuali titik dan minus
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 45 && charCode != 46)
            return false;
        return true;
    });
    $("body").on("focus", ".angka", function (evt) {
        if ($(this).val()==0){
            $(this).val("");
        }
    });
    $("body").on("blur", ".angka", function (evt) {
        if ($(this).val()==""){
            $(this).val(0);
        }
    });
    
    //disable enter di input text
    $('input').keypress(function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
        }
    });
    
    //prevent font flickering in some browsers 
    (function() {
        //if firefox 3.5+, hide content till load (or 3 seconds) to prevent FOUT
        var d = document, e = d.documentElement, s = d.createElement('style');
        if (e.style.MozTransform === '') { // gecko 1.9.1 inference
            s.textContent = 'body{visibility:hidden}';
            e.firstChild.appendChild(s);
            function f() {
                s.parentNode && s.parentNode.removeChild(s);
            }
            addEventListener('load', f, false);
            setTimeout(f, 3000);
        }
    })();

    //Disable certain links
    $('a[href^=#]').click(function(e) {
        e.preventDefault()
    })

    mainNav = $('.mainnav>ul>li');
    mainNav.find('ul').siblings().addClass('hasUl').append('<span class="hasDrop icon16 icomoon-icon-arrow-down"></span>');
    mainNavLink = mainNav.find('a').not('.sub a');
    mainNavLinkAll = mainNav.find('a');
    mainNavSubLink = mainNav.find('.sub a').not('.sub li .sub a');
    mainNavCurrent = mainNav.find('a.current');

    //remove current class if have
    mainNavCurrent.removeClass('current');
    //set the seleceted menu element
//    if ($.cookie("newCurrentMenu")) {
//        mainNavLinkAll.each(function(index) {
//            if ($(this).attr('href') == $.cookie("newCurrentMenu")) {
//                //set new current class
//                $(this).addClass('current');
//
//                ulElem = $(this).closest('ul');
//                if (ulElem.hasClass('sub')) {
//                    //its a part of sub menu need to expand this menu
//                    aElem = ulElem.prev('a.hasUl').addClass('drop');
//                    ulElem.addClass('expand');
//                }
//                //destroy cookie	
//                $.cookie("newCurrentMenu", null);
//            }
//        });
//    }
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);

    mainNavLinkAll.each(function(index) {
        var href = $(this).attr('href');

        if (path.substring(0, href.length) === href) {
            $(this).addClass('current');

            ulElem = $(this).closest('ul');
            if (ulElem.hasClass('sub')) {
                //its a part of sub menu need to expand this menu
                aElem = ulElem.prev('a.hasUl').addClass('drop');
                ulElem.addClass('expand');
            }
        }
    });

    //hover magic add blue color to icons when hover - remove or change the class if not you like.
    mainNavLinkAll.hover(
            function() {
                $(this).find('span.icon16').addClass('blue');
            },
            function() {
                $(this).find('span.icon16').removeClass('blue');
            }
    );

    //click magic
    mainNavLink.click(function(event) {
        $this = $(this);

        if ($this.hasClass('hasUl')) {
            event.preventDefault();
            if ($this.hasClass('drop')) {
                $(this).siblings('ul.sub').slideUp(500).siblings().removeClass('drop');
            } else {
                $(this).siblings('ul.sub').slideDown(500).siblings().addClass('drop');
            }
        } else {
            //has no ul so store a cookie for change class.
            $.cookie("newCurrentMenu", $this.attr('href'), {expires: 1});
        }
    });
    mainNavSubLink.click(function(event) {
        $this = $(this);

        if ($this.hasClass('hasUl')) {
            event.preventDefault();
            if ($this.hasClass('drop')) {
                $(this).siblings('ul.sub').slideUp(500).siblings().removeClass('drop');
            } else {
                $(this).siblings('ul.sub').slideDown(250).siblings().addClass('drop');
            }
        } else {
            //has no ul so store a cookie for change class.
//            $.cookie("newCurrentMenu", $this.attr('href'), {expires: 1});
        }
    });

    //responsive buttons
    $('.resBtn>a').click(function(event) {
        $this = $(this);
        if ($this.hasClass('drop')) {
            $('#sidebar>.shortcuts').slideUp(500).addClass('hided');
            $('#sidebar>.sidenav').slideUp(500).addClass('hided');
            $this.removeClass('drop');
        } else {
            $('#sidebar>.shortcuts').slideDown(250);
            $('#sidebar>.sidenav').slideDown(250);
            $this.addClass('drop');
        }
    });
    $('.resBtnSearch>a').click(function(event) {
        $this = $(this);
        if ($this.hasClass('drop')) {
            $('.search').slideUp(500);
            $this.removeClass('drop');
        } else {
            $('.search').slideDown(250);
            $this.addClass('drop');
        }
    });
    
    $('.collapseBtn').on( "click", function() {
        showHideSidebar();
    });
    //Hide and show sidebar btn
    function showHideSidebar(){
        $this = $('.collapseBtn');
        //check if sidebar is show
        if ($this.hasClass('minim')) {            
            $('#sidebarbg').css('margin-left', '0');
            $('#content').css('margin-left', '213' + 'px');
            $('#sidebar').css('left', '0').css('margin-left', '0');
            $this.removeClass('minim');
            $this.children().children('i').attr('class', 'icon-arrow-left')
        } else {            
            //hide sidbar
            $('#sidebarbg').css('margin-left', '-299' + 'px');
            $('#sidebar').css('margin-left', '-299' + 'px');
            $('.collapseBtn').css('top', '20' + 'px').css('left', '280' + 'px').addClass('shadow');
            //expand content
            $this.addClass('minim');
            $this.children().children('i').attr('class', 'icon-arrow-right')
            $('#content').css('margin-left', '0');

        }
    };

    //remove loadstate class from body and show the page
    setTimeout('$("html").removeClass("loadstate")', 500);

    //ajax loader
    $("#loader")
            .hide()
            .ajaxStart(function() {
                $(this).show();
            })
            .ajaxStop(function() {
                $(this).hide();
            });

});
$( ".collapseBtn" ).trigger( "click" );
