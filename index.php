<!DOCTYPE html>
<html lang="sv">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css.css?r=<?php echo rand(0, 999999); ?>">
<script>
var levels = [];
<?php
if(isset($_GET["beta"])) {
	echo "var codemode = \"blocks\";";
} else {
	echo "var codemode = \"code\";";
}
?>
</script>
<?php
$tmaps = scandir("maps");
$maps = [];
foreach($tmaps as $tmap) {
	if(!in_array($tmap, [".", ".."])) {
		array_push($maps, $tmap);
	}
}
sort($maps, SORT_STRING);
foreach($maps as $map) {
	echo "<script src=\"maps/".$map."\"></script>
";
}
?>
<script id="mainscript"></script>
<title>Programmering med NTI Gymnasiet Kristianstad</title>
</head>
<body id="body" onload="loaded();">
<!--
<audio autoplay loop>
<source src="prog1.mp3" type="audio/mpeg">
</audio>
<div id="logo">
<img src="logo.png">
</div>
-->
<div id="imgs" style="display: none;">
<img src="imgs/WARRIOR_IDLE_DOWN-sheet.png" id="img_player_down" data-type="animation" data-name="player.down" data-frames="8" alt="image for animation">
<img src="imgs/WARRIOR_IDLE_RIGHT-sheet.png" id="img_player_right" data-type="animation" data-name="player.right" data-frames="10" alt="image for animation">
<img src="imgs/WARRIOR_IDLE_UP-sheet.png" id="img_player_up" data-type="animation" data-name="player.up" data-frames="7" alt="image for animation">
<img src="imgs/WARRIOR_DOWN-sheet.png" id="img_player_run_down" data-type="animation" data-name="player.run.down" data-frames="8" alt="image for animation">
<img src="imgs/WARRIOR_RIGHT-sheet.png" id="img_player_run_right" data-type="animation" data-name="player.run.right" data-frames="8" alt="image for animation">
<img src="imgs/WARRIOR_UP-sheet.png" id="img_player_run_up" data-type="animation" data-name="player.run.up" data-frames="8" alt="image for animation">
<img src="imgs/WARRIOR_LEFT-sheet.png" id="img_player_run_left" data-type="animation" data-name="player.run.left" data-frames="8" alt="image for animation">
<img src="imgs/DungeonTileset.png" id="img_dungeon" data-type="tileset" data-names="none,none,wall.tl,wall.ttl,wall.t,wall.ttr,wall.tr,none,none,stairs1.tl,stairs1.tr,stairs2.tl,stairs2.tr,floor2water.tl,floor2water.t,floor2water.tr,water2floor.tl,water2floor.t,water2floor.tr,none,none,wall.tll,wall2.tl,wall2.t,wall2.tr,wall.trr,none,none,stairs1.bl,stairs1.br,stairs2.bl,stairs2.br,floor2water.l,water,floor2water.r,water2floor.l,none,water2floor.r,none,none,wall.l,wall2.l,floor2,wall2.r,wall.r,none,none,stairs3.tl,stairs3.tr,stairs4.tl,stairs4.tr,floor2water.bl,floor2water.b,floor2water.br,water2floor.bl,water2floor.b,water2floor.br,none,none,wall.tlc,none,none,none,wall.trc,none,none,stairs3.bl,stairs3.br,stairs4.bl,stairs4.br,water.f1,water.f2,water.f3,water.f4,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,floor1.stone,floor1,none,none,none,none,none,none,none,none,none,none,none,none,none,none,wall.bll,none,none,none,none,none,none,none,wall.brr,none,none,none,none,none,none,none,none,none,none,wall.bl,wall.bbl,wall.blc,none,none,none,wall.brc,wall.bbr,wall.br,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,wall1.light.r,wall1.light.b,none,none,none,none,none,none,none,none,none,none,none,none,none,wall2.bl,wall2.b,wall2.br,none,wall1.light.t,wall1.light.l,none,none,none,none,none,none,none,none,none,none,none,none,none,none,wall.b,none,none,none,none,none,none,none,none,none,none,none,none,none,none" data-size="16" alt="image for animation">
	<img src="imgs/chest.png" id="item_chest" data-type="animation" data-name="chest" data-frames="15" alt="image for animation">
	<img src="imgs/treasure.png" id="item_treasure" data-type="animation" data-name="treasure" data-frames="51" alt="image for animation">
	<img src="imgs/door.png" id="item_door" data-type="animation" data-name="door" data-frames="22" alt="image for animation">
	<img src="imgs/KeyIcons.png" id="img_items2" data-type="tileset" data-names="none,key,none,none" data-size="32" alt="image for animation">
	<img src="imgs/question.png" id="effect_question" data-type="animation" data-name="question" data-frames="20" alt="image for animation">
	<img src="imgs/powerup.png" id="effect_powerup" data-type="animation" data-name="powerup" data-frames="15" alt="image for animation">
	<img src="imgs/drown.png" id="drown" data-type="animation" data-name="drown" data-frames="22" alt="image for animation">
	<img src="imgs/death.png" id="death" data-type="animation" data-name="death" data-frames="26" alt="image for animation">
	<img src="imgs/life1.png" id="life1" data-type="animation" data-name="life1" data-frames="12" alt="image for animation">
	<img src="imgs/life2.png" id="life2" data-type="animation" data-name="life2" data-frames="25" alt="image for animation">
	<img src="imgs/teleport1.png" id="teleport1" data-type="animation" data-name="teleport1" data-frames="11" alt="image for animation">
	<img src="imgs/teleport2.png" id="teleport2" data-type="animation" data-name="teleport2" data-frames="16" alt="image for animation">
	<img src="imgs/portal.png" id="portal" data-type="animation" data-name="portal" data-frames="50" alt="image for animation">
	<img src="imgs/win.png" id="win" data-name="win" alt="image for animation">
	<img src="imgs/hinder1.png" data-type="animation" data-name="hinder" data-frames="24" alt="image for animation">
	<img src="imgs/lock.png" data-type="animation" data-name="lock" data-frames="32" alt="image for animation">
	<img src="imgs/unlock.png" data-type="animation" data-name="unlock" data-frames="11" alt="image for animation">
	<img src="imgs/danger1.png" id="danger" data-type="animation" data-name="danger" data-frames="39" alt="image for animation">
	<img src="imgs/notes.png" id="item_notes" data-type="tileset" data-names="note1,note2" data-size="16" alt="image for animation">
	<img src="imgs/dirts.png" id="item_dirts" data-type="tileset" data-names="dirt1,dirt2,dirt3,dirt4,dirt5,dirt6,dirt7,dirt8,dirt9,dirt10,dirt11,dirtt,dirtb,dirtr,dirtl,dirt12" data-size="16" alt="image for animation">
</div>
<table><tbody>
<tr><td style="vertical-align: top; text-align: left;" colspan=2>
<input type="button" id="prevLevel" value="Förra banan" onclick="prevLevel();"><!--
	<input type="button" id="clearPlayer" value="Börja om" onclick="deleteGame();">--><input type="button" id="helpbut" value="Hjälp" onclick="if(document.getElementById('help').style.display != 'block') { document.getElementById('help').style.display = 'block'; this.style.backgroundColor = '#666'; this.style.color = '#fff';} else { document.getElementById('help').style.display = 'none'; this.style.backgroundColor = ''; this.style.color = '';};">
	<input type="button" value="Ledtrådar" id="notesBut" style="display: none;" onclick="if(document.getElementById('notes').style.display != 'block') {document.getElementById('notes').style.display = 'block';} else {document.getElementById('notes').style.display = 'none';}"><div class="notes" id="notes" style="display: none;"></div><p id="err"></p><br>
<div id="help" style="display: none;">
	<input type="button" value="X" onclick="document.getElementById('help').style.display = 'none'; document.getElementById('helpbut').style.backgroundColor = ''; document.getElementById('helpbut').style.color = '';">
	<h1>Hjälp!</h1>
	<p>All kod du skriver här är i programmeringsspråket JavaScript. JavaScript används till allt ifrån servern för en hemsida till att göra hemsidor lite mer rörliga och roliga, lite som denna hemsidan. Här nedanför så ser du hur språket är uppbyggt och vad du kan göra i just detta spelet.</p>
	<h2>Dokumentation</h2>
	<h3>Regler</h3>
	<ul>
		<li>Alla <b>rader</b> kod avslutas med ett semikolon (<span class="pre">;</span>).</li>
		<li>En <b>funktion</b> gör något, till exempel flyttar på spelaren eller plockar upp en sak.</li>
		<li>Alla <b>funktioner</b> avslutas med paranteser. Paranteser ser ut såhär: <b>()</b></li>
		<li>Inuti <b>paranteserna</b> kan man använda så kallade "argument". Argument är värden (text, nummer mm) som skickas till, och används av, funktionen man kör. T ex <span class="pre">höger(2);</span> Den koden skickar värdet <span class="pre">2</span> till funktionen <span class="pre">höger</span> som gör så att spelaren går <span class="pre">2</span> steg till höger. Om du har tomma paranteser så går spelaren ett steg.</li>
	</ul>
	<table><tbody>
		<tr>
			<th></th>
			<th><p>Funktion</p></th>
			<th><p>Exempel</p></th>
			<th><p>Förklaring</p></th>
			<th><p>Argument</p></th>
		</tr>
		<tr>
			<td><img src="icons/door.png" class="icon" alt="image for help"></td>
			<td>vinn</td>
			<td><span class="pre">vinn();</span></td>
			<td><p>Avslutar banan. Körs bara ifall spelaren är i målet/porten. </p></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><img src="icons/up.png" class="icon" alt="image for help"></td>
			<td>upp</td>
			<td><span class="pre">upp(3);</span></td>
			<td><p>Spelaren går upp.</p></td>
			<td><p>Antal steg</p></td>
		</tr>
		<tr>
			<td><img src="icons/down.png" class="icon" alt="image for help"></td>
			<td>ner</td>
			<td><span class="pre">ner(4);</span></td>
			<td><p>Spelare går ner.</p></td>
			<td><p>Antal steg</p></td>
		</tr>
		<tr>
			<td><img src="icons/left.png" class="icon" alt="image for help"></td>
			<td>vänster</td>
			<td><span class="pre">vänster(5);</span></td>
			<td><p>Spelaren går till vänster.</p></td>
			<td><p>Antal steg</p></td>
		</tr>
		<tr>
			<td><img src="icons/right.png" class="icon" alt="image for help"></td>
			<td>höger</td>
			<td><span class="pre">höger(6);</span></td>
			<td><p>Spelaren går till höger.</p></td>
			<td><p>Antal steg</p></td>
		</tr>
		<tr>
			<td><img src="icons/key.png" class="icon" alt="image for help"><img src="icons/chest.png" class="icon" alt="image for help"><img src="icons/life.png" class="icon" alt="image for help"><img src="icons/treasure.png" class="icon" alt="image for help"></td>
			<td>ta</td>
			<td><span class="pre">ta();</span></td>
			<td><p>Spelaren plockar upp en sak (nycklar, skatter, eller extraliv).</p></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><img src="icons/portal.png" class="icon" alt="image for help"></td>
			<td>teleportera</td>
			<td><span class="pre">teleportera(3,5);</span></td>
			<td><p>Teleporterar spelaren från en portal till en annan portal.</p></td>
			<td>X- och Y-position dit man vill teleportera.</td>
		</tr>
		<tr>
			<td><img src="icons/note.png" class="icon" alt="image for help"></td>
			<td>läs</td>
			<td><span class="pre">läs();</span></td>
			<td><p>Spelaren läser en ledtråd/ett papper. Denna används på nästa bana för att <span class="pre">öppna</span> ett lås.</p></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><img src="icons/lock.png" class="icon" alt="image for help"></td>
			<td>öppna</td>
			<td><span class="pre">öppna(7,4,"lösenord");</span></td>
			<td><p>Spelaren låser upp ett lås som hen står intill. Funktionen måste ange platsen för låset och rätt lösenord för att låsa upp låset. Du måste skriva citationstecken (") runt lösenordet.</p></td>
			<td>X-position, Y-position och "lösenord".</td>
		</tr>
	</tbody></table>
</div>
</td></tr><tr><td style="vertical-align: bottom;">
<div id="inpFrame"><?php
if(isset($_GET["beta"])) {
	$actions = [
		"left" => [
			"parameters" => [
				"int"
			]
		],"right" => [
			"parameters" => [
				"int"
			]
		],"up" => [
			"parameters" => [
				"int"
			]
		],"down" => [
			"parameters" => [
				"int"
			]
		],"pickup" => [
		],"read" => [
		],"win" => [
		],"teleport" => [
			"parameters" => [
				"int",
				"int"
			]
		],"open" => [
			"parameters" => [
				"int",
				"int",
				"string"
			]
		]
	];
?>
<table><tr><th id="tools">
<?php
foreach($actions as $action => $data) {
	echo "<span class=\"action\" id=\"".$action."\" draggable=\"true\" ondragstart=\"dragCode(this);\" ondragend=\"endDrag(this);\"><span class=\"actionLeftside\"><img src=\"imgs/move.svg\" class=\"actionImg\" alt=\"image for help\"></span><span class=\"actionMiddle\"><img src=\"imgs/".$action.".svg\" class=\"actionImg\" alt=\"image for help\"></span><span class=\"actionRight\" onclick=\"selectText(this.children[0]);\">";
	if(isset($data["parameters"])) {
		foreach($data["parameters"] as $par) {
			echo "<span class=\"value\" contenteditable oninput=\"actionChangeVal(this);\">1</span>";
		}
	}
	echo "</span></span>";
}
?>
<input type="hidden" id="inp" value="">
</th></tr>
<tr><td id="code" ondragover="updCode(this);" ondrop="addCode(this);" style="min-height: 50vh;"></td></tr>
</table>
<?php
} else {
?>
<textarea id="inp" cols="25" placeholder="Kod" style="min-height: 60vh;" oninput="updLinecount(this);" onscroll="updLinecount(this);"></textarea><?php
}
?></div>
</td><td>
<canvas id="c"></canvas>
</td></tr><tr><td style="text-align: left;" colspan=2>
<input type="button" value="Kör script (-500p)" onclick="start();" id="but">
<input type="button" value="Stoppa script" onclick="stop();" id="but2"><br>
</td></tr>
</tbody>
</table>
<!--
fuska("grymProgr@mmering");
-->
<pre id="errlog"></pre>
<script>
function detectLet(){
  try{
    return !!new Function('let x=true;return x')()
  }catch(e){
    return false
  }
}
if(detectLet() === true) {
	document.getElementById("mainscript").src = "js.js?r=<?php echo rand(0,999); ?>";
} else {
	console.log(document.getElementById("body"));
	document.getElementById("body").innerHTML = "<h1>NTI Gymnasiet Kristianstad</h1><p>Vi är ledsna, men webbläsaren du använder är för gammal för att kunna användas här. Uppdatera webbläsaren eller byt till en nyare enhet.</p>";
	document.getElementById("body").style.color = "#fff";
	document.getElementById("body").style.margin = "50px";
	document.getElementById("mainscript").innerHTML = "function loaded(){}";
}
</script>
</body>
</html>