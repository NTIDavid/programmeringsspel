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
var ajax = function(callback, doc, method, data) {
	method = typeof method !== 'undefined' ? method : "GET";
	data = typeof data !== 'undefined' ? data : null;
	if(doc === null) {
		return false;
	} else if(callback === null) {
		return false;
	} else {
		let xhr = new XMLHttpRequest();
		xhr.open(method, doc, true);
		xhr.onreadystatechange = function () {
			let DONE = 4;
			let OK = 200;
			if(xhr.readyState === DONE) {
				if(typeof callback == "object") {
					if(xhr.status === OK) {
						callback[0](xhr.response, callback[1]);
					} else {
						callback[0]([false, xhr.status], callback[1]);
					}
				} else {
					if(xhr.status === OK) {
						callback(xhr.response);
					} else {
						callback([false, xhr.status]);
					}
				}
			}
		};
		if(data !== null) {
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		}
		xhr.send(data);
	}
};
var canvas = null;
function getCanvas() {
	canvas = document.getElementById("c");
	var ctx = canvas.getContext("2d");
	ctx.mozImageSmoothingEnabled = false;
	ctx.webkitImageSmoothingEnabled = false;
	ctx.msImageSmoothingEnabled = false;
	ctx.imageSmoothingEnabled = false;
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

var mouse = {
	x: 0,
	y: 0,
	click: {
		x: 0,
		y: 0
	}
};

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
function loaded() {
	document.getElementById("c").width = size.width;
	document.getElementById("c").height = size.height;
	ca = getCanvas();
	canvas.addEventListener("mousemove", function(e) {
		if((e.offsetX < size.width) && (e.offsetY < size.height) && (e.offsetX > 0) && (e.offsetY > 0)) {
			mouse.x = Math.floor(e.offsetX/scale);
			mouse.y = Math.floor(e.offsetY/scale);
		}
	});
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
	window.addEventListener("mouseup", mouseIsUp);
	(function animLoop(){
		requestAnimFrame(animLoop);
		ticker();
	})();
}

function ticker() {
	draw.clear();
	draw.filledRect(0, 0, size.width, size.height, "#666");
	if(mouseDown) {
		place();
	}
	updMap()
	ca.lineWidth = 2;
	for(let y in level.maps.rmap) {
		for(let x in level.maps.rmap[y]) {
			if(level.maps.rmap[y][x].aniType == "t") {
				draw.tile(x*scale, y*scale, scale, scale, level.maps.rmap[y][x].src);
				if(level.maps.rmap[y][x].src == "floor1") {
					draw.tile(x*scale, y*scale, scale, scale, "dirt"+Math.floor((Math.abs((x/y)%11))+1));
				}
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
	for(let y in level.maps.rmap) {
		for(let x in level.maps.rmap[y]) {
			draw.rect(x*scale, y*scale, scale, scale, "rgba(0,0,0,0.1)");
		}
	}
	draw.rect(mouse.x*scale, mouse.y*scale, scale, scale, "#0ff");
	draw.rect(mouse.click.x*scale, mouse.click.y*scale, scale, scale, "#0f0");
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
var map = [];
var rmap = [];
var imap = [];
for(let y = 0; y < size.height/scale; y++) {
	level.maps.map[y] = [];
	level.maps.rmap[y] = [];
	level.maps.imap[y] = [];
	for(let x = 0; x < size.width/scale; x++) {
		level.maps.map[y][x] = {aniType: "t", src: "floor1"};
	}
}
var startWallSize = 2;
for(let y = 0; y < startWallSize; y++) {
	for(let x = 0; x < size.width/scale; x++) {
		level.maps.map[y][x] = {aniType: "t", src: "wall"};
	}
}
for(let y = (size.height/scale)-startWallSize; y < size.height/scale; y++) {
	for(let x = 0; x < size.width/scale; x++) {
		level.maps.map[y][x] = {aniType: "t", src: "wall"};
	}
}
for(let y = 0; y < size.height/scale; y++) {
	for(let x = 0; x < startWallSize; x++) {
		level.maps.map[y][x] = {aniType: "t", src: "wall"};
	}
}
for(let y = 0; y < size.height/scale; y++) {
	for(let x = (size.width/scale)-startWallSize; x < size.width/scale; x++) {
		level.maps.map[y][x] = {aniType: "t", src: "wall"};
	}
}

for(let y = 0; y < size.height/scale; y++) {
	for(let x = 0; x < size.width/scale; x++) {
		level.maps.rmap[y][x] = level.maps.map[y][x];
		level.maps.imap[y][x] = false;
	}
}
var maptype = "player";
var placeType = 1;
var tileType = "a";
function select(o, type) {
	document.getElementById("val").style.display = "none";
	if(o.value == "DEL") {
		tileType = "t";
		maptype = {aniType: "t", src: "floor1"};
		placeType = false;
	} else {
		placeType = type;
		tileType = o.value.substring(0, 1);
		maptype = o.value.substring(2, o.value.length);
	}
}
var mouseDown = false;
function mouseIsDown() {
	mouseDown = true;
}
function mouseIsUp() {
	mouseDown = false;
}
function place() {
	mouse.click.x = mouse.x;
	mouse.click.y = mouse.y;
	var x = mouse.x;
	var y = mouse.y;
	let newTile = {aniType: tileType, src: maptype};
	if(placeType === false) {
		if(tileType == "select") {
			if(level.maps.imap[y][x] !== false) {
				document.getElementById("val").placeholder = "Värde för "+level.maps.imap[y][x].src;
				document.getElementById("val").dataset.x = x;
				document.getElementById("val").dataset.y = y;
				console.log(level.maps.imap[y][x]);
				if(typeof level.maps.imap[y][x].value != "undefined") {
					document.getElementById("val").value = level.maps.imap[y][x].value;
				} else {
					document.getElementById("val").value = "";
				}
				document.getElementById("val").style.display = "block";
			} else {
				document.getElementById("val").style.display = "none";
			}
		} else {
			level.maps.map[y][x] = {aniType: "t", src: "floor1"};
			level.maps.rmap[y][x] = {aniType: "t", src: "floor1"};
			level.maps.imap[y][x] = false;
		}
	} else if(placeType == 0) {
		level.maps.map[y][x] = newTile;
		level.maps.rmap[y][x] = newTile;
	} else if(placeType == 1) {
		if(maptype == "player") {
			level.player.x = x;
			level.player.y = y;
		} else {
			type = maptype;
			if(type.substring(0,7) == "crystal") {
				type = "crystal"; 
			}
				newTile = {aniType: tileType, type: type, src: maptype};
			if(type.substring(0,4) == "note") {
				type = "note";
				newTile = {aniType: tileType, type: type, src: maptype, value: "Test"};
			}
			level.maps.imap[y][x] = newTile;
		}
	}
}
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
function save() {
	//document.getElementById("map").value = JSON.stringify(level);
	level.startMessage = document.getElementById("st").value;
	ajax(saved, "savemap.php", "POST", "name="+document.getElementById("filename").value+"&map="+encodeURIComponent(JSON.stringify(level)));
}
function saved(msg) {
	alert(msg);
}
function loadFile(file) {
	//level = JSON.parse(document.getElementById("out").value);
	ajax(fileLoaded, "loadmap.php?f="+file);
	nn = file;
	document.getElementById("filename").value = nn;
}
function fileLoaded(msg) {
	if(msg === "fel") {
		alert("Kunde inte ladda kartan");
		nn = newName;
		document.getElementById("filename").value = nn;
	} else {
		eval("level = "+msg);
		if(typeof level.startMessage != "undefined") {
			document.getElementById("st").value = level.startMessage;
		} else {
			document.getElementById("st").value = "";
		}
	}
}
function deleteFile(file) {
	ajax(fileDeleted, "deletemap.php?f="+file);
}
function fileDeleted(msg) {
	if(msg === "fel") {
		alert("Kunde inte radera kartan");
	} else {
		let obj = document.getElementById("m"+msg);
		obj.parentNode.removeChild(obj);
		alert("Kartan har raderats");
	}
}
</script>
</head>
<body onload="loaded();">
<div id="imgs" style="display: none;">
	<img src="imgs/DungeonTileset.png" id="img_dungeon" data-type="tileset" data-names="none,none,wall.tl,wall.ttl,wall.t,wall.ttr,wall.tr,none,none,stairs1.tl,stairs1.tr,stairs2.tl,stairs2.tr,floor2water.tl,floor2water.t,floor2water.tr,water2floor.tl,water2floor.t,water2floor.tr,none,none,wall.tll,wall2.tl,wall2.t,wall2.tr,wall.trr,none,none,stairs1.bl,stairs1.br,stairs2.bl,stairs2.br,floor2water.l,water,floor2water.r,water2floor.l,none,water2floor.r,none,none,wall.l,wall2.l,floor2,wall2.r,wall.r,none,none,stairs3.tl,stairs3.tr,stairs4.tl,stairs4.tr,floor2water.bl,floor2water.b,floor2water.br,water2floor.bl,water2floor.b,water2floor.br,none,none,wall.tlc,none,none,none,wall.trc,none,none,stairs3.bl,stairs3.br,stairs4.bl,stairs4.br,water.f1,water.f2,water.f3,water.f4,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,floor1.stone,floor1,none,none,none,none,none,none,none,none,none,none,none,none,none,none,wall.bll,none,none,none,none,none,none,none,wall.brr,none,none,none,none,none,none,none,none,none,none,wall.bl,wall.bbl,wall.blc,none,none,none,wall.brc,wall.bbr,wall.br,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,none,wall1.light.r,wall1.light.b,none,none,none,none,none,none,none,none,none,none,none,none,none,wall2.bl,wall2.b,wall2.br,none,wall1.light.t,wall1.light.l,none,none,none,none,none,none,none,none,none,none,none,none,none,none,wall.b,none,none,none,none,none,none,none,none,none,none,none,none,none,none" data-size="16">
	<img src="imgs/WARRIOR_IDLE_DOWN-sheet.png" id="img_player_down" data-type="animation" data-name="player" data-frames="8">
	
	<img src="imgs/KeyIcons.png" id="img_items2" data-type="tileset" data-names="none,key,none,none" data-size="32">
	<img src="imgs/chest.png" id="item_chest" data-type="animation" data-name="chest" data-frames="15">
	<img src="imgs/treasure.png" id="item_treasure" data-type="animation" data-name="treasure" data-frames="51">
	<img src="imgs/question.png" id="item_q" data-type="animation" data-name="q" data-frames="20">
	<img src="imgs/powerup.png" id="item_powerup" data-type="animation" data-name="powerup" data-frames="15">
	<img src="imgs/life1.png" id="life1" data-type="animation" data-name="life1" data-frames="12">
	<img src="imgs/door.png" id="item_door" data-type="animation" data-name="door" data-frames="22">
	<img src="imgs/portal.png" id="portal" data-type="animation" data-name="portal" data-frames="50">
	<img src="imgs/lock.png" id="hinder" data-type="animation" data-name="lock" data-frames="32">
	<img src="imgs/danger1.png" id="danger" data-type="animation" data-name="danger" data-frames="39">
	<img src="imgs/notes.png" id="item_notes" data-type="tileset" data-names="note1,note2" data-size="16">
	
	<img src="imgs/dirts.png" id="item_dirts" data-type="tileset" data-names="dirt1,dirt2,dirt3,dirt4,dirt5,dirt6,dirt7,dirt8,dirt9,dirt10,dirt11,dirtt,dirtb,dirtr,dirtl,dirt12" data-size="16">
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
<select id="items" onchange="select(this, 1);" onclick="select(this, 1);">
	<option value="a.player">Player</option>
	<option value="a.door">Door</option>
	<option value="t.key">Key</option>
	<option value="a.life1">Life</option>
	<option value="a.chest">Chest</option>
	<option value="a.treasure">Treasure</option>
	<option value="a.portal">Portal</option>
	<option value="t.note1">Note 1</option>
	<option value="t.note2">Note 2</option>
	<option value="a.lock">Lock</option>
	<option value="a.danger">Danger</option>
</select>
<input type="button" onclick="tileType = 'select'; placeType = false; document.getElementById('val').style.display = 'block';" value="Select"><input type="text" id="val" placeholder="Värde" style="display: none;" data-x="-1" data-y="-1" oninput="if(this.dataset.x != -1) { level.maps.imap[this.dataset.y][this.dataset.x].value = this.value;};">
</td><td style="vertical-align: top;">
<?php
$tmaps = scandir("maps");
$maps = [];
foreach($tmaps as $tmap) {
	if(!in_array($tmap, [".", ".."])) {
		array_push($maps, $tmap);
	}
}
sort($maps, SORT_STRING);
echo "<script>
var newName = \"m".str_pad(count($maps)+1, 2, "0", STR_PAD_LEFT).".js\";
var nn = newName;
</script>";
?>
<input type="button" id="newname" value="Spara som" onclick="save();"><input type="text" name="filename" id="filename" value="<?php echo "m".str_pad(count($maps)+1, 2, "0", STR_PAD_LEFT).".js"; ?>" size=8><br>
<?php
foreach($maps as $map) {
	echo "<div id=\"m".$map."\">".$map." <input type=\"button\" value=\"Öppna\" onclick=\"loadFile('".$map."');\"><input type=\"button\" value=\"Radera\" onclick=\"deleteFile('".$map."');\"></div>";
}
?>
<textarea placeholder="Start text" id="st"></textarea>
<input type="button" value="Visa kod">
<p id="out"></p>
</td></tr></tbody></table>
<pre id="output"></pre>
</body>
</html>