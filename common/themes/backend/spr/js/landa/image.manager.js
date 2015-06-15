$(document).ready(function() {
    //--------------- Prettyphoto ------------------//
    $("a[rel^='prettyPhoto']").prettyPhoto({
        default_width: 800,
        default_height: 600,
        theme: 'facebook',
        social_tools: false,
        show_title: true
    });
    //--------------- Gallery & lazzy load & jpaginate ------------------//
    $(function() {
        //hide the action buttons
        $('.actionBtn').hide();
        //show action buttons on hover image
        $('.galleryView>li').hover(
                function() {
                    $(this).find('.actionBtn').stop(true, true).show();
                },
                function() {
                    $(this).find('.actionBtn').stop(true, true).hide();
                }
        );
        //remove the gallery item after press delete
//        $('.actionBtn>.delete').click(function() {
//            $(this).closest('li').remove();
//            /* destroy and recreate gallery */
//            $("div.holder").jPages("destroy").jPages({
//                containerID: "itemContainer",
//                animation: "fadeInUp",
//                perPage: 16,
//                scrollBrowse: true, //use scroll to change pages
//                keyBrowse: true,
//                callback: function(pages, items) {
//                    /* lazy load current images */
//                    items.showing.find("img").trigger("turnPage");
//                    /* lazy load next page images */
//                    items.oncoming.find("img").trigger("turnPage");
//                }
//            });
//            // add notificaton 
//            $.pnotify({
//                type: 'success',
//                title: 'Done',
//                text: 'You just delete this picture.',
//                icon: 'picon icon16 brocco-icon-info white',
//                opacity: 0.95,
//                history: false,
//                sticker: false
//            });
//
//        });
//
//        /* initiate lazyload defining a custom event to trigger image loading  */
//        $("ul#itemContainer li img").lazyload({
//            event: "turnPage",
//            effect: "fadeIn"
//        });
//        /* initiate plugin */
//        $("div.holder").jPages({
//            containerID: "itemContainer",
//            animation: "fadeInUp",
//            perPage: 16,
//            scrollBrowse: true, //use scroll to change pages
//            keyBrowse: true,
//            callback: function(pages, items) {
//                /* lazy load current images */
//                items.showing.find("img").trigger("turnPage");
//                /* lazy load next page images */
//                items.oncoming.find("img").trigger("turnPage");
//            }
//        });
    });
})