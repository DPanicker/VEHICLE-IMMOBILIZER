<?php
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = '$data';
fwrite($myfile, $txt);
$txt = '$data';
fwrite($myfile, $txt);
fclose($myfile);
?>