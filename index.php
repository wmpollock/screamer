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
$start_outer_container = "<div class='row actions'>\n";
$start_outer_grid = "\t<div class='col-md-6 col-12'>\n";
$start_inner_grid = "\t\t<div class='row justify-content-between'>\n";
foreach (SRC_DIRS as $src_dir) {
    // $dir_fh = opendir($src_dir);
    echo "\n<h1>", preg_replace("/_/", " ", $src_dir), "</h1>\n";
    echo $start_outer_container,
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
        echo "\t\t\t<div class='col-3'>\n",
             "\t\t\t\t<a href='#' data-src='$path'><img src='$icon_file' class='img-fluid'/></a>\n",
             "\t\t\t</div>\n";

    }
    
    // When we close it, its nice to consider packing the remaining elements in the
    // grid so they don't space all "we don't care."
    $gridno = $counter % 4;
    if ($counter > 0) {
        $infill = 4 - $gridno;
        if ($infill) {
            $grid_size = $infill * 3; // 8/2 = 4 cells of 3 units in the subgrids
            echo "\t\t\t<div class='col-$grid_size'>&nbsp;</div>\n";
        }
    }
    
    echo "\t\t</div>\n\t</div>\n</div>";
}
?>
  </form>
</div>
