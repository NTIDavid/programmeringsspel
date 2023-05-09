<?php
if(file_exists("maps/".$_GET["f"])) {
	if(unlink("maps/".$_GET["f"]) === true) {
		echo $_GET["f"];
	} else {
		echo "fel";
	}
} else {
	echo "fel";
}
?>