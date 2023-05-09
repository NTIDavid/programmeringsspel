<?php
$files = scandir("maps");
$name = $_POST["name"];
$ok = file_put_contents("maps/".$name, "levels.push(".rawurldecode($_POST["map"]).");");
if($ok === false) {
	echo "Kunde inte spara kartan.";
} else {
	echo "Kartan sparades som ".$name;
}
?>