// SCREAMER

$("document").ready(function() {
    $(".actions a").click(function(e) {
    	var $clickyThing = $(this);
    	
	e.preventDefault();
	
	$.get("play.php?audio=" + $clickyThing.data('src'),
		  function() {
		// Done playing.thanks
		if ($clickyThing.data('instances') == 1) {
			$clickyThing.removeClass('playing');
		}
		$clickyThing.data('instances', $clickyThing.data('instances') - 1);
	});
	// Tag this file as playing and count how many instances we've got.
	// I mean, sure, we should allow + 1 buttonpresses, yes?  YESSS.
	if (!$clickyThing.data('instances')) {
		$clickyThing.data('instances', 0);
	}
	$clickyThing.addClass('playing').data('instances', $(this).data('instances') + 1);
    });
});
