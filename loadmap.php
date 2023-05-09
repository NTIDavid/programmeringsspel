<?php
$f = file_get_contents("maps/".$_GET["f"]);
if($f === false) {
	echo "fel";
} else {
	echo substr($f, 12, strlen($f)-14);
}
?>