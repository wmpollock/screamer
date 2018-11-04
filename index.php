<?php
$_SERVER['DOCUMENT_ROOT'] = '/var/www/html';
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

?>
<div class="container">
  <div class="jumbotron">
    <h1>The Screamer</h1>
  </div>
  <form>
<?php

foreach (SRC_DIRS as $src_dir) {
    // $dir_fh = opendir($src_dir);
    echo "<h1>", preg_replace("/_/", " ", $src_dir), "</h1>";
    echo "<div class='d-flex align-content-center flex-wrap actions'>";
    foreach (preg_grep('/\.(mp3|wav)$/', scandir("./audio/" . $src_dir)) as $file) {

        $path = "$src_dir/$file";
        $icon_file = "avatars/$src_dir-" . preg_replace('/\..+?$/', '', $file) . ".png";
        if (! file_exists($icon_file))
        {
            $url = "https://api.adorable.io/avatars/80/$src_dir-$file.png";
            //print ("<p>Getting $icon_file from $url</p>\n");
            file_put_contents($icon_file, fopen($url, 'r'));
        }
        echo "<div class='p-0 mr-auto'><a href='#' data-src='$path'><img src='$icon_file'/></a></div>\n";

    }
    echo "</div>";
}
?>
  </form>
</div>
