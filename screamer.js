/*
 
  ██████  ▄████▄   ██▀███  ▓█████ ▄▄▄       ███▄ ▄███▓▓█████  ██▀███  
▒██    ▒ ▒██▀ ▀█  ▓██ ▒ ██▒▓█   ▀▒████▄    ▓██▒▀█▀ ██▒▓█   ▀ ▓██ ▒ ██▒
░ ▓██▄   ▒▓█    ▄ ▓██ ░▄█ ▒▒███  ▒██  ▀█▄  ▓██    ▓██░▒███   ▓██ ░▄█ ▒
  ▒   ██▒▒▓▓▄ ▄██▒▒██▀▀█▄  ▒▓█  ▄░██▄▄▄▄██ ▒██    ▒██ ▒▓█  ▄ ▒██▀▀█▄  
▒██████▒▒▒ ▓███▀ ░░██▓ ▒██▒░▒████▒▓█   ▓██▒▒██▒   ░██▒░▒████▒░██▓ ▒██▒
▒ ▒▓▒ ▒ ░░ ░▒ ▒  ░░ ▒▓ ░▒▓░░░ ▒░ ░▒▒   ▓▒█░░ ▒░   ░  ░░░ ▒░ ░░ ▒▓ ░▒▓░
░ ░▒  ░ ░  ░  ▒     ░▒ ░ ▒░ ░ ░  ░ ▒   ▒▒ ░░  ░      ░ ░ ░  ░  ░▒ ░ ▒░
░  ░  ░  ░          ░░   ░    ░    ░   ▒   ░      ░      ░     ░░   ░ 
      ░  ░ ░         ░        ░  ░     ░  ░       ░      ░  ░   ░     
         ░
         
Wm. Pollock 2018-2019

*/
$("document").ready(function() {
    $(".actions a img").mousedown(function(e) {
		$(this).addClass("down");
		console.log("DOWN")
	});
	$(".actions a img").mouseup(function(e) {
		$(this).removeClass("down");
		console.log("DOWN")
	});

	$(".actions a").click(function(e) {
    	var $clickyThing = $(this);
    	
		e.preventDefault();

		// Server audio (== PI)
		if ($('#audio-out-server').is(":checked")) {
			console.log("Server side - playing " + $clickyThing.data('src'));
			incClass($clickyThing, "server");
			$.get("play.php?audio=" + $clickyThing.data('src'),
				  function() {
					 // Done playing.thanks
					 decClass($clickyThing, "server")
					 console.log("Server side - done.");
	  			  });
		} 

		// Client-side (browser) audio: the default if we don't have the control and otherwise
		// if its enabled.
		if ($('#audio-out-client').length ==0 || $('#audio-out-client').is(":checked")) {
			var audiosrc = $("#" + $clickyThing.data('trigger'))[0];
			var audio = $(audiosrc).clone()[0];
			$(audio).on('ended', function() {
				decClass($("a[data-trigger='" + this.id), "client")
				console.log("Client side - done playing " + this.id);
			});
			console.log("Client side - playing " + $clickyThing.data('trigger'));
			incClass($clickyThing, "client");
			audio.play();
		}
    });
});

function incClass($clickyThing, className) {
	var iName = "instances-" + className;
	
	if (!$clickyThing.data(iName)) {
		$clickyThing.data(iName, 0);
	}

	$clickyThing.addClass('playing-' + className).data(iName, $clickyThing.data(iName) + 1);
}

function decClass($clickyThing, className) {
	// Tag this file as playing and count how many instances we've got.
	// I mean, sure, we should allow + 1 buttonpresses, yes?  YESSS (at least server side...)
	// Establish instance data 

	var iName = "instances-" + className;
	 console.log("There are " + $clickyThing.data(iName) + " " + className + " instances running.");

	 if ($clickyThing.data(iName) == 1) {
	 		$clickyThing.removeClass('playing-' + className);
	 }
	 // Decrease instances.  
	 $clickyThing.data(iName, $clickyThing.data(iName) - 1);
}