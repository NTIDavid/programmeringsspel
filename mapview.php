<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
	font-family: Verdana, Sans-Serif;
}
</style>
<script>
var levels = [];
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
<script>

var canvas = null;
function getCanvas() {
	canvas = document.getElementById("c");
	var ctx = canvas.getContext("2d");
	return ctx;
}
var draw = {
	filledRect: function(x, y, w, h, color) {
		ca.beginPath();
		ca.fillStyle = color;
		ca.fillRect(x, y, w, h);
	},
	line: function(x1, y1, x2, y2, color) {
		ca.beginPath();
		ca.strokeStyle = color;
		ca.moveTo(x1, y1);
		ca.lineTo(x2, y2);
		ca.stroke();
	},
	rect: function(x, y, w, h, color) {
		ca.beginPath();
		ca.strokeStyle = color;
		ca.moveTo(x, y);
		ca.lineTo(x, y);
		ca.lineTo(x+w, y);
		ca.lineTo(x+w, y+h);
		ca.lineTo(x, y+h);
		ca.lineTo(x, y);
		ca.stroke();
	},
	circle: function(x, y, radie, borderSize, color) {
		ca.beginPath();
		ca.lineStyle = color;
		ca.arc(x, y, radie, 0, 2*Math.PI);
		ca.stroke();
	},
	filledCircle: function(x, y, radie, color, start, stop) {
		ca.beginPath();
		ca.arc(x, y, radie, start, stop);
		ca.fillStyle = color;
		ca.fill();
		ca.closePath();
	},
	text: function(x, y, text, col = "#000", size = 20, align = "left", base = "top") {
		ca.font = size+"px Verdana";
		ca.textAlign = align;
		ca.textBaseline = base;
		ca.fillStyle = col;
		ca.fillText(text, x, y);
	},
	image: function(x, y, width, height, src) {
		ca.drawImage(getImage(src), x, y, width, height);
	},
	ani: function(x, y, width, height, src) {
		let animation = getAnimation(src);
		let imgWidth = animation.sprite.naturalWidth;
		let imgHeight = animation.sprite.naturalHeight;
		ca.drawImage(
			animation.sprite,
			(imgWidth/animation.frames)*animation.frame,
			0,
			(imgWidth/animation.frames),
			imgHeight,
			x,
			y,
			width,
			height
		);
		/*
		if(animation.timer == 0) {
			animation.frame++;
			animation.timer = animation.interval;
		} else {
			animation.timer--;
		}
		if(animation.frame >= animation.frames) {
			animation.frame = 0;
		}*/
	},
	tile: function(x, y, width, height, src) {
		let tile = getTile(src);
		if(tile != null) {
			let imgWidth = tile.tilesheet.naturalWidth;
			let imgHeight = tile.tilesheet.naturalHeight;
			ca.drawImage(
				tile.tilesheet,
				tile.pos.x,
				tile.pos.y,
				tile.size,
				tile.size,
				x,
				y,
				width,
				height
			);
		}
		/*
		if(animation.timer == 0) {
			animation.frame++;
			animation.timer = animation.interval;
		} else {
			animation.timer--;
		}
		if(animation.frame >= animation.frames) {
			animation.frame = 0;
		}*/
		//console.log(tile);
	},
	clear: function() {
		ca.clearRect(0, 0, 1600, 800);
	}
}
var requestAnimFrame = (function(){
	return window.requestAnimationFrame||
	window.webkitRequestAnimationFrame||
	window.mozRequestAnimationFrame||
	function(callback){
		window.setTimeout(callback, 1000 / 60);
	};
})();


var scale = 32;
var size = {
	width: 40 * scale,
	height: 25 * scale
};
size.width = Math.floor(size.width/scale)*scale;
size.height = Math.floor(size.height/scale)*scale;

var images = [];
function getImage(name) {
	for(let c in images) {
		if(images[c].name == name) {
			return images[c].value;
		}
	}
	return null;
}
var animations = [];
function getAnimation(name) {
	for(let c in animations) {
		if(animations[c].name == name) {
			return animations[c];
		}
	}
	return null;
}
var tiles = [];
function getTile(name) {
	for(let c in tiles) {
		if(tiles[c].name == name) {
			return tiles[c];
		}
	}
	return null;
}
var mapIndex = 0;
function loaded() {
	document.getElementById("c").width = size.width;
	document.getElementById("c").height = size.height;
	ca = getCanvas();
	level = levels[mapIndex];
	// ladda alla bilder
	var timgs = document.getElementById("imgs").children;
	for(let c = 0; c < timgs.length; c++) {
		images[c] = {
			name: timgs[c].dataset.name, 
			value: timgs[c]
		};
		if(timgs[c].dataset.type == "animation") {
			animations[c] = {
				name: timgs[c].dataset.name,
				index: c,
				sprite: images[c].value,
				timer: 0,
				interval: 4,
				frame: -1,
				frames: timgs[c].dataset.frames
			};
			setInterval(function() {
				animation = animations[c];
				animation.frame++;
				if(animation.frame >= animation.frames) {
					animation.frame = 0;
				}
			}, 1000/12);
		} else if(timgs[c].dataset.type == "tileset") {
			let size = parseInt(timgs[c].dataset.size);//naturalWidth;
			let names = timgs[c].dataset.names.split(",");
			let c2 = 0;
			for(let y = 0; y < timgs[c].naturalHeight; y += size) {
				for(let x = 0; x < timgs[c].naturalWidth; x += size) {
					if(c2 < names.length) {
						if(names[c2] != "none") {
							tiles.push({
								name: names[c2],
								tilesheet: images[c].value,
								pos: {x: x, y: y},
								size: size
							});
						}
						c2++;
					}
				}
			}
		}
	}
	(function animLoop(){
		requestAnimFrame(animLoop);
		ticker();
	})();
}

function ticker() {
	draw.clear();
	draw.filledRect(0, 0, size.width, size.height, "#666");
	updMap()
	for(let y in level.maps.rmap) {
		for(let x in level.maps.rmap[y]) {
			if(level.maps.rmap[y][x].aniType == "t") {
				draw.tile(x*scale, y*scale, scale, scale, level.maps.rmap[y][x].src);
			} else if(level.maps.rmap[y][x].aniType == "a") {
				draw.ani(x*scale, y*scale, scale, scale, level.maps.rmap[y][x].src);
			}
			if(level.maps.imap[y][x].aniType == "t") {
				draw.tile(x*scale, y*scale, scale, scale, level.maps.imap[y][x].src);
			} else if(level.maps.imap[y][x].aniType == "a") {
				draw.ani(x*scale, y*scale, scale, scale, level.maps.imap[y][x].src);
			}
			//draw.text((x*scale)+(scale/2), (y*scale)+(scale/2), level.maps.rmap[y][x], "#ccc", 10, "center", "middle");
		}
	}
	draw.ani(level.player.x*scale, level.player.y*scale, scale, scale, "player");
}
var level = {
	player: {
		x: 2,
		y: 2
	},
	maps: {
		map: [],
		rmap: [],
		imap: []
	}
};
function updMap() {
	for(let y in level.maps.map) {
		for(let x in level.maps.map[y]) {
			y = parseInt(y);
			x = parseInt(x);
			let dirs = {
				tl: false,
				t: false,
				tr: false,
				r: false,
				br: false,
				b: false,
				bl: false,
				l: false
			};
			if(y > 0) {
				if(x > 0) {
					if(level.maps.map[y-1][x-1].src == level.maps.map[y][x].src) {
						dirs.tl = true;
					}
				}
				if(level.maps.map[y-1][x].src == level.maps.map[y][x].src) {
					dirs.t = true;
				}
				if(x < (size.width/scale)-1) {
					if(level.maps.map[y-1][x+1].src == level.maps.map[y][x].src) {
						dirs.tr = true;
					}
				}
			}
			if(x < (size.width/scale)-1) {
				if(level.maps.map[y][x+1].src == level.maps.map[y][x].src) {
					dirs.r = true;
				}
			}
			if(y < (size.height/scale)-1) {
				if(x < (size.width/scale)-1) {
					if(level.maps.map[y+1][x+1].src == level.maps.map[y][x].src) {
						dirs.br = true;
					}
				}
				if(level.maps.map[y+1][x].src == level.maps.map[y][x].src) {
					dirs.b = true;
				}
				if(x > 0) {
					if(level.maps.map[y+1][x-1].src == level.maps.map[y][x].src) {
						dirs.bl = true;
					}
				}
			}
			if(x > 0) {
				if(level.maps.map[y][x-1].src == level.maps.map[y][x].src) {
					dirs.l = true;
				}
			}
			if(level.maps.map[y][x].src == "water") {
				if((dirs.tl) && (dirs.t) && (dirs.tr) && (dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "water"};
				} else if((!dirs.tl) && (dirs.t) && (dirs.tr) && (dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "water2floor.br"};
				} else if((dirs.tl) && (!dirs.t) && (dirs.tr) && (dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "water2floor.b"};
				} else if((dirs.tl) && (dirs.t) && (!dirs.tr) && (dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "water2floor.bl"};
				} else if((dirs.tl) && (dirs.t) && (dirs.tr) && (!dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "water2floor.l"};
				} else if((dirs.tl) && (dirs.t) && (dirs.tr) && (dirs.r) &&(!dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "water2floor.tl"};
				} else if((dirs.tl) && (dirs.t) && (dirs.tr) && (dirs.r) &&(dirs.br) && (!dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "water2floor.t"};
				} else if((dirs.tl) && (dirs.t) && (dirs.tr) && (dirs.r) &&(dirs.br) && (dirs.b) && (!dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "water2floor.tr"};
				} else if((dirs.tl) && (dirs.t) && (dirs.tr) && (dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (!dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "water2floor.r"};
				} else if((!dirs.t) && (dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "floor2water.t"};
				} else if((!dirs.b) && (dirs.r) &&(dirs.tr) && (dirs.t) && (dirs.tl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "floor2water.b"};
				} else if((!dirs.l) && (dirs.t) &&(dirs.tr) && (dirs.r) && (dirs.br) && (dirs.b)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "floor2water.l"};
				} else if((!dirs.r) && (dirs.t) &&(dirs.tl) && (dirs.l) && (dirs.bl) && (dirs.b)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "floor2water.r"};
				} else if((!dirs.tl) && (!dirs.t) && (!dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "floor2water.tl"};
				} else if((!dirs.tr) && (!dirs.t) && (!dirs.r)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "floor2water.tr"};
				} else if((!dirs.bl) && (!dirs.b) && (!dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "floor2water.bl"};
				} else if((!dirs.br) && (!dirs.b) && (!dirs.r)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "floor2water.br"};
				} else {
					level.maps.rmap[y][x] = {aniType: "t", src: "water"};
				}
			} else if(level.maps.map[y][x].src == "wall") {
				if((dirs.tl) && (dirs.t) && (dirs.tr) && (dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "floor2"};
				} else if((!dirs.tl) && (dirs.t) && (dirs.tr) && (dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.tlc"};
				} else if((dirs.tl) && (!dirs.t) && (dirs.tr) && (dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.t"};
				} else if((dirs.tl) && (dirs.t) && (!dirs.tr) && (dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.trc"};
				} else if((dirs.tl) && (dirs.t) && (dirs.tr) && (!dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.r"};
				} else if((dirs.tl) && (dirs.t) && (dirs.tr) && (dirs.r) &&(!dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.brc"};
				} else if((dirs.tl) && (dirs.t) && (dirs.tr) && (dirs.r) &&(dirs.br) && (!dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.b"};
				} else if((dirs.tl) && (dirs.t) && (dirs.tr) && (dirs.r) &&(dirs.br) && (dirs.b) && (!dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.blc"};
				} else if((dirs.tl) && (dirs.t) && (dirs.tr) && (dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (!dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.l"};
				} else if((!dirs.t) && (dirs.r) &&(dirs.br) && (dirs.b) && (dirs.bl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.t"};
				} else if((!dirs.b) && (dirs.r) &&(dirs.tr) && (dirs.t) && (dirs.tl) && (dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.b"};
				} else if((!dirs.l) && (dirs.t) &&(dirs.tr) && (dirs.r) && (dirs.br) && (dirs.b)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.l"};
				} else if((!dirs.r) && (dirs.t) &&(dirs.tl) && (dirs.l) && (dirs.bl) && (dirs.b)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.r"};
				} else if((!dirs.tl) && (!dirs.t) && (!dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.tl"};
				} else if((!dirs.tr) && (!dirs.t) && (!dirs.r)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.tr"};
				} else if((!dirs.bl) && (!dirs.b) && (!dirs.l)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.bl"};
				} else if((!dirs.br) && (!dirs.b) && (!dirs.r)) {
					level.maps.rmap[y][x] = {aniType: "t", src: "wall.br"};
				} else {
					level.maps.rmap[y][x] = {aniType: "t", src: "floor2"};
				}
			}
		}
	}
}
<?php
$tmaps = scandir("maps");
$maps = [];
foreach($tmaps as $tmap) {
	if(!in_array($tmap, [".", ".."])) {
		array_push($maps, $tmap);
	}
}
sort($maps, SORT_STRING);
?>
var mapNames = [<?php
$tm = [];
foreach($maps as $map) {
	array_push($tm, "\"".$map."\"");
}
echo implode(",", $tm);
?>];
function go(step) {
	mapIndex += step;
	if(mapIndex < 0) {
		mapIndex = levels.length-1;
	} else if(mapIndex >= levels.length) {
		mapIndex = 0;
	}
	level = levels[mapIndex];
	document.getElementById("mapindex").innerText = "Karta "+mapNames[mapIndex];
}
</script>
</head>
<body onload="loaded();">
<div id="imgs" style="display: none;">
	<img src="imgs/DungeonTileset.png" id="img_dungeon" data-type="tileset" data-names="none,none,wall.tl,wall.ttl,wall.t,wall.ttr,wall.tr,none,none,stairs1.tl,stairs1.tr,stairs2.tl,stairs2.tr,floor2water.tl,floor2water.t,floor2water.tr,water2floor.tl,water2floor.t,water2floor.tr,none,none,wall.tll,wall2.tl,wall2.t,wall2.tr,wall.trr,none,none,stairs1.bl,stairs1.br,stairs2.bl,stairs2.br,floor2water.l,water,floor2water.r,water2floor.l,none,water2floor.r,none,none,wall.l,wall2.l,floor2,wall2.r,wall.r,none,none,stairs3.tl,stairs3.tr,stairs4.tl,stairs4.tr,floor2water.bl,floor2water.b,floor2water.br,water2floor.bl,water2floor.b,water2floor.br,none,none,wall.tlc,none,none,none,wall.trc,none,none,stairs3.bl,stairs3.br,stairs4.bl,stairs4.br,water.f1,water.f2,water.f3,water.f4,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,floor1.stone,floor1,none,none,none,none,none,none,none,none,none,none,none,none,none,none,wall.bll,none,none,none,none,none,none,none,wall.brr,none,none,none,none,none,none,none,none,none,none,wall.bl,wall.bbl,wall.blc,none,none,none,wall.brc,wall.bbr,wall.br,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,wall1.light.r,wall1.light.b,none,none,none,none,none,none,none,none,none,none,none,none,none,wall2.bl,wall2.b,wall2.br,none,wall1.light.t,wall1.light.l,none,none,none,none,none,none,none,none,none,none,none,none,none,none,wall.b,none,none,none,none,none,none,none,none,none,none,none,none,none,none" data-size="16">
	<img src="imgs/KeyIcons.png" id="img_items2" data-type="tileset" data-names="none,key,none,none" data-size="32">
	<img src="imgs/chest.png" id="item_chest" data-type="animation" data-name="chest" data-frames="15">
	<img src="imgs/question.png" id="item_q" data-type="animation" data-name="q" data-frames="20">
	<img src="imgs/powerup.png" id="item_powerup" data-type="animation" data-name="powerup" data-frames="15">
	<img src="imgs/life1.png" id="life1" data-type="animation" data-name="life1" data-frames="12">
	<img src="imgs/door.png" id="item_door" data-type="animation" data-name="door" data-frames="22">
	<img src="imgs/portal.png" id="portal" data-type="animation" data-name="portal" data-frames="50">
	<img src="imgs/WARRIOR_IDLE_DOWN-sheet.png" id="img_player_down" data-type="animation" data-name="player" data-frames="8">
</div>
<table><tbody><tr><td style="vertical-align: top;">
<canvas id="c" style="border: 2pt solid #000;" onmousedown="mouseIsDown();"></canvas><br>
<select id="tileset" onchange="select(this, 0);" onclick="select(this, 0);">
	<option value="DEL">Delete</option>
	<option value="t.floor1">Floor1</option>
	<option value="t.floor2">Floor2</option>
	<option value="t.water">Water</option>
	<option value="t.wall">Wall 1</option>
	<option value="t.floor1.stone">Stone</option>
</select>
<select id="tileset" onchange="select(this, 1);" onclick="select(this, 1);">
	<option value="a.player">Player</option>
	<option value="a.door">Door</option>
	<option value="t.key">Key</option>
	<option value="a.life1">Life</option>
	<option value="a.chest">Chest</option>
	<option value="a.portal">Portal</option>
</select>
</td><td style="vertical-align: top;">
<h3 id="mapindex">Karta <?php echo $maps[0]; ?></h3>
<input type="button" value="Bak" onclick="go(-1);">
<input type="button" value="Fram" onclick="go(1);">
<br><br>
<?php
foreach($maps as $map) {
	echo $map."<br>";
}
?>
</td></tr></tbody></table>
</body>
</html>