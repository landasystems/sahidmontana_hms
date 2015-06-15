
$("a[data-rel^='prettyPhoto']").prettyPhoto();
$("a[data-rel^='prettyPhoto']").each(function() {
    $(this).attr("rel", $(this).data("rel"));
});
