$(window).resize(function() {
    var s = $(window).width();
    s > 980 && ($(".shortcuts.hided").removeClass("hided").attr("style", ""), $(".sidenav.hided").removeClass("hided").attr("style", "")), "Window size is:" + $(window).width()
}), $(document).ready(function() {

    $("form").submit(function() {
        $(this).find(":submit").attr("disabled", "disabled")
    })
    $(".collapseBtn").trigger("click")

    function s() {
        $this = $(".collapseBtn"), $this.hasClass("minim") ? ($("#sidebarbg").css("margin-left", "0"), $("#content").css("margin-left", "213px"), $("#sidebar").css("left", "0").css("margin-left", "0"), $this.removeClass("minim"), $this.children().children("i").attr("class", "icon-arrow-left")) : ($("#sidebarbg").css("margin-left", "-299px"), $("#sidebar").css("margin-left", "-299px"), $(".collapseBtn").css("top", "20px").css("left", "280px").addClass("shadow"), $this.addClass("minim"), $this.children().children("i").attr("class", "icon-arrow-right"), $("#content").css("margin-left", "0"))
    }
    $("body").on("keypress", ".angka", function(s) {
        var i = s.which ? s.which : event.keyCode;
        return i > 31 && (48 > i || i > 57) && 45 != i && 46 != i ? !1 : !0
    }), $("body").on("focus", ".angka", function() {
        0 == $(this).val() && $(this).val("")
    }), $("body").on("blur", ".angka", function() {
        "" == $(this).val() && $(this).val(0)
    }), $("input").keypress(function(s) {
        13 == s.keyCode && s.preventDefault()
    }),
    function() {
        function s() {
            e.parentNode && e.parentNode.removeChild(e)
        }
        var i = document,
            a = i.documentElement,
            e = i.createElement("style");
        "" === a.style.MozTransform && (e.textContent = "body{visibility:hidden}", a.firstChild.appendChild(e), addEventListener("load", s, !1), setTimeout(s, 3e3))
    }(), $("a[href^=#]").click(function(s) {
        s.preventDefault()
    }), mainNav = $(".mainnav>ul>li"), mainNav.find("ul").siblings().addClass("hasUl").append('<span class="hasDrop icon12 icon-chevron-down"></span>'), mainNavLink = mainNav.find("a").not(".sub a"), mainNavLinkAll = mainNav.find("a"), mainNavSubLink = mainNav.find(".sub a").not(".sub li .sub a"), mainNavCurrent = mainNav.find("a.current"), mainNavCurrent.removeClass("current");
    var i = window.location.pathname;
    i = i.replace(/\/$/, ""), i = decodeURIComponent(i), mainNavLinkAll.each(function() {
        var s = $(this).attr("href");
        i.substring(0, s.length) === s && ($(this).addClass("current"), ulElem = $(this).closest("ul"), ulElem.hasClass("sub") && (aElem = ulElem.prev("a.hasUl").addClass("drop"), ulElem.addClass("expand")))
    }), mainNavLink.click(function(s) {
        $this = $(this), $this.hasClass("hasUl") ? (s.preventDefault(), $this.hasClass("drop") ? $(this).siblings("ul.sub").slideUp(500).siblings().removeClass("drop") : $(this).siblings("ul.sub").slideDown(500).siblings().addClass("drop")) : $.cookie("newCurrentMenu", $this.attr("href"), {
            expires: 1
        })
    }), mainNavSubLink.click(function(s) {
        $this = $(this), $this.hasClass("hasUl") && (s.preventDefault(), $this.hasClass("drop") ? $(this).siblings("ul.sub").slideUp(500).siblings().removeClass("drop") : $(this).siblings("ul.sub").slideDown(250).siblings().addClass("drop"))
    }), $(".resBtn>a").click(function() {
        $this = $(this), $this.hasClass("drop") ? ($("#sidebar>.shortcuts").slideUp(500).addClass("hided"), $("#sidebar>.sidenav").slideUp(500).addClass("hided"), $this.removeClass("drop")) : ($("#sidebar>.shortcuts").slideDown(250), $("#sidebar>.sidenav").slideDown(250), $this.addClass("drop"))
    }), $(".resBtnSearch>a").click(function() {
        $this = $(this), $this.hasClass("drop") ? ($(".search").slideUp(500), $this.removeClass("drop")) : ($(".search").slideDown(250), $this.addClass("drop"))
    }), $(".collapseBtn").on("click", function() {
        s()
    }), setTimeout('$("html").removeClass("loadstate")', 500), $("#loader").hide().ajaxStart(function() {
        $(this).show()
    }).ajaxStop(function() {
        $(this).hide();
        $("#content").find(":submit").attr("disabled", false);
    })
});
