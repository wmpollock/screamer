<pre>
<?php


if ($_GET) {
   $audio = escapeshellcmd("./audio/" . $_GET['audio']);
} else {
  $audio = "./audio/evil_laughs/wickedwitchlaugh.mp3";
}

//system("/usr/bin/mplayer $audio > lastrun.log");
system("/usr/bin/mplayer -ao pulse $audio 2> lastrun.err");
?>