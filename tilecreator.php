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
	text: function(x, y, text, size = 20, align = "left", base = "top") {
		ca.font = size+"px Verdana";
		ca.textAlign = "left";
		ca.textBaseline = "top";
		ca.fillStyle = "#000";
		ca.fillText(text, x, y);
	},
	image: function(x, y, width, height, src) {
		ca.drawImage(getImage(src), x, y, width, height);
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
var zoom = 1;
var size = {
	width: 19 * scale*zoom,
	height: 11 * scale*zoom
};
size.width = Math.floor(size.width/scale)*scale;
size.height = Math.floor(size.height/scale)*scale;
var offset = {
	x: 0,
	y: 0
};
var images = [];
function getImage(name) {
	for(let c in images) {
		if(images[c].name == name) {
			return images[c].value;
		}
	}
	return null;
}
var currentImage = "";
function loaded() {
	document.getElementById("c").width = size.width;
	document.getElementById("c").height = size.height;
	ca = getCanvas();
	canvas.addEventListener("mousemove", function(e) {
		mouse.x = Math.floor(e.offsetX/scale);
		mouse.y = Math.floor(e.offsetY/scale);
	});
	// ladda alla bilder
	var timgs = document.getElementById("imgs").children;
	for(let c = 0; c < timgs.length; c++) {
		images[c] = {
			name: timgs[c].dataset.name, 
			value: timgs[c]
		};
	}
	for(let c in images) {
		if(currentImage == "") {
			currentImage = images[c].name;
			console.log(images[c]);
		}
		let o = document.createElement("OPTION");
		o.value = images[c].name;
		o.innerText = images[c].name;
		document.getElementById("img").appendChild(o);
	}
	(function animLoop(){
		requestAnimFrame(animLoop);
		ticker();
	})();
}

function ticker() {
	draw.clear();
	draw.image(offset.x, offset.y, getImage(currentImage).naturalWidth*2*zoom, getImage(currentImage).naturalHeight*2*zoom, currentImage);
	ca.lineWidth = 2;
	for(let y in names) {
		for(let x in names[y]) {
			if(typeof names[y][x] == "undefined") {
				draw.filledRect(x*scale*zoom, y*scale*zoom, (scale)*zoom, (scale)*zoom, "rgba(255,255,255,0.5)");
			}
		}
	}
	
	draw.rect(mouse.x*scale, mouse.y*scale, scale*zoom, scale*zoom, "#0ff");
	draw.rect(mouse.click.x*scale, mouse.click.y*scale*zoom, scale*zoom, scale*zoom, "#0f0");
}
var names = [];
function select() {
	mouse.click.x = Math.floor(event.offsetX/scale);
	mouse.click.y = Math.floor(event.offsetY/scale);
	var x = mouse.click.x;
	var y = mouse.click.y;
	console.log(names[y][x]);
	document.getElementById("namn").placeholder = "Name: "+x+"x"+y;
	if(typeof names[y][x] != "undefined") {
		document.getElementById("namn").value = names[y][x];
	} else {
		document.getElementById("namn").value = "";
	}
}
function empty() {
	names[mouse.click.y][mouse.click.x] = undefined;
	document.getElementById("namn").value = "";
}
function saveName() {
	names[mouse.click.y][mouse.click.x] = document.getElementById("namn").value;
}
function exp() {
	let t = "";
	for(let y in names) {
		for(let x in names[y]) {
			if(t += "") {
				t += ",";
			}
			if(typeof names[y][x] == "undefined") {
				t += "none";
			} else {
				t += names[y][x];
			}
		}
	}
	document.getElementById("out").value = t;
}
function imp() {
	let t = document.getElementById("out").value.split(",");
	let id = 0;
	for(let y in names) {
		for(let x in names[y]) {
			if(t[id] != "none") {
				names[y][x] = t[id];
			}
			id++;
		}
	}
}
function updSize(o) {
	var o = document.getElementById("size");
	scale = parseInt(o.value);
	size = {
		width: parseInt(document.getElementById("xsize").value) * scale,
		height: parseInt(document.getElementById("ysize").value) * scale
	};
	size.width = Math.floor(size.width/scale)*scale*zoom;
	size.height = Math.floor(size.height/scale)*scale*zoom;
	document.getElementById("c").width = size.width;
	document.getElementById("c").height = size.height;
	names = [];
	for(let y = 0; y < size.height/scale; y++) {
		names[y] = [];
		for(let x = 0; x < size.width/scale; x++) {
			names[y][x] = undefined;
		}
	}
}
</script>
</head>
<body onload="loaded();">
<div id="imgs" style="display: none;">
	<img src="imgs/DungeonTileset.png" id="img_dungeon" data-name="dungeon">
	<img src="imgs/items.png" id="img_icons" data-name="icons">
	<img src="imgs/KeyIcons.png" id="img_icons2" data-name="keys">
	<img src="imgs/Basic_Door_Pixel.png" id="img_door" data-name="door">
	<img src="imgs/dirts.png" id="img_dirts" data-name="dirt">
</div>
<table><tbody><tr><td style="vertical-align: top;">
<select id="img" onchange="currentImage = this.value;">
</select>
<input type="text" id="size" placeholder="Size" value="32" oninput="updSize();"><br>
<input type="text" id="xsize" placeholder="XSize" value="19" oninput="updSize();">
<input type="text" id="ysize" placeholder="YSize" value="11" oninput="updSize();"><br>
<input type="text" id="xoffset" placeholder="X-Offset" value="0" oninput="offset.x = -this.value;">
<input type="text" id="yoffset" placeholder="Y-Offset" value="0" oninput="offset.y = -this.value;"><br>
<canvas id="c" style="border: 2pt solid #000;" onclick="select();"></canvas><br>
<input type="button" onclick="empty();" value="Save as empty"><br>
<input type="text" id="namn" placeholder="None selected"><input type="button" onclick="saveName();" value="Save">
</td><td style="vertical-align: top;">
<input type="button" value="Export" onclick="exp();">
<input type="button" value="Import" onclick="imp();"><br>
<textarea id="out" style="word-break: break-word;"></textarea>
</td></tr></tbody></table>
</body>
</html>