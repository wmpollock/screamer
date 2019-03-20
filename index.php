<?php

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


require_once ($_SERVER['DOCUMENT_ROOT'] . "/Phplib/PageGen/Bootstrap4/Redbox.php");

const SRC_DIRS = array(
    "screams",
    "moans",
    "evil_laughs",
    "misc"
    
);

$if_i_dont_assign_it_spews_before_content = new Redbox(array(
    title => "The Screamer",
    js => array("screamer.js"),
    css => array("screamer.css"),
    favicon => file_get_contents('favicon/favicon.include'),
    
));


// Generate a safe ID -- we could do UUIds or whatever here as long
// as its derived from $string
function safeId($string) {
    //Lower case everything
    $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}
// ------------------------------------------------------------------------------------------- ?>
<div class="container">
  <div class="jumbotron">
    <h1>The Screamer</h1>
  </div>
  <form>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="audio-out-client" checked='checked'>
      <label class="form-check-label" for="audio-out-client">
          Audio to browser
      </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="audio-out-server">
      <label class="form-check-label" for="audio-out-server">
          Audio to server
      </label>
    </div>
<?php
// -------------------------------------------------------------------------------------------
$start_outer_container = "<div class='row actions'>\n";
$start_outer_grid = "\t<div class='col-md-6 col-12'>\n";
$start_inner_grid = "\t\t<div class='row justify-content-between'>\n";

foreach (SRC_DIRS as $src_dir) {
    // $dir_fh = opendir($src_dir);
    echo "\n<h1>", preg_replace("/_/", " ", $src_dir), "</h1>\n",
         $start_outer_container,
         $start_outer_grid,
         $start_inner_grid;
         
    $counter = 0;
    foreach (preg_grep('/\.(mp3|wav)$/', scandir("./audio/" . $src_dir)) as $file) {
        if ($counter && ($counter % 4 == 0 )) {
            echo "\t\t</div>\n\t</div>\n";
            if ($counter % 8 == 0 ) {
                echo "</div>\n",
                $start_outer_container;
            }
            echo $start_outer_grid,
                 $start_inner_grid;
        }
        ++$counter;
        $path = "$src_dir/$file";
        $icon_file = "avatars/$src_dir-" . preg_replace('/\..+?$/', '', $file) . ".png";
        if (! file_exists($icon_file))
        {
            $url = "https://api.adorable.io/avatars/80/$src_dir-$file.png";
            print ("<!--Getting $icon_file from $url -->\n");
            file_put_contents($icon_file, fopen($url, 'r'));
        }
        $audio_id = safeID($path);        
        $audio_type = '';
        if (preg_match('/mp3$/', $file)) {
            $audio_type = 'audio/mpeg';
        } else if (preg_match('/\.wav$/', $file)) {
            // Apparently no WAV for IE (wah wahhh) -- these s/b refactored...
            $audio_type = 'audio/wav';
        } else {
            trigger_error("Unknown audio type for " . $file);
        }
        echo "<div class='col-3'>\n",
             "  <a href='#' data-src='$path' data-trigger='$audio_id'><img src='$icon_file' class='img-fluid'/></a>\n",
            //" <audio src='audio/$path' id='$audio_id' type='$audio_type'></audio>\n",
            "   <audio src='audio/$path' id='$audio_id' preload='auto'></audio>\n",
            "</div>\n";

    }
    
    // When we close it, its nice to consider packing the remaining elements in the
    // grid so they don't space all "we don't care."
    $gridno = $counter % 4;
    if ($counter > 0) {
        $infill = 4 - $gridno;
        if ($infill) {
            $grid_size = $infill * 3; // 8/2 = 4 cells of 3 units in the subgrids
            echo "<div class='col-$grid_size'>&nbsp;</div>\n";
        }
    }
    
    echo "</div></div></div>";
}

// -------------------------------------------------------------------------------------------?>
  </form>
</div>
