$("document").ready(function() {
    $(".actions a").click(function(e) {
	e.preventDefault();
	$.get("play.php?audio=" + $(this).data('src'));
    });
});
