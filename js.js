function log(txt) {
	console.log(txt);
}
let mwl = -5;
var m = {
	dist: function(pos1, pos2) {
		let a = pos1.x - pos2.x;
		let b = pos1.y - pos2.y;
		return Math.sqrt(a*a + b*b);
	}
};
var cookie = {
	set: function(name, value) {
		if(cookie.get(name) !== false) {
			cookie.delete(name);
		}
		let tc = name+"="+value;
		document.cookie = tc+"; expires=Thu, 01 Jan 2050 00:00:00 UTC; path=/;";
		return tc;
	}, delete: function(name) {
		document.cookie = name+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
		document.cookie = name+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
	}, get: function(name) {
		let tc = document.cookie.split("; ");
		let r = "";
		for(let q in tc) {
			let v = tc[q].split("=");
			if(v[0] == name) {
				return v[1];
			}
		}
		return false;
	}
}
mwl +=6;
var resetCookie = function() {
	cookie.delete("lives");
	cookie.delete("score");
	cookie.delete("level");
}
function obj(id) {
	return document.getElementById(id);
}
mwl++;
function selectText(o) {
    var sel, range;
    var el = o; //get element id
    if(window.getSelection && document.createRange) { //Browser compatibility
      sel = window.getSelection();
      if(sel.toString() == ''){ //no text selection
         window.setTimeout(function(){
            range = document.createRange(); //range object
            range.selectNodeContents(el); //sets Range
            sel.removeAllRanges(); //remove all ranges from selection
            sel.addRange(range);//add Range to a Selection.
        },1);
      }
    } else if (document.selection) { //older ie
        sel = document.selection.createRange();
        if(sel.text == ''){ //no text selection
            range = document.body.createTextRange();//Creates TextRange object
            range.moveToElementText(el);//sets Range
            range.select(); //make selection.
        }
    }
}
function prevLevel() {
	if(confirm("Gå till förra banan?") === true) {
		if(level > 0) {
			cookie.set("level", level-1);
			window.location.reload();
		} else {
			alert("Du är på första banan");
		}
	}
}
mwl-=2;
function updLinecount(o) {
	if(codemode != "blocks") {
		let lines = o.value.split("\n").length;
		lno.innerHTML = "";
		for(let c = 0; c < lines; c++) {
			lno.innerHTML += (parseInt(c)+1)+"<br>";
		}
		lno.style.height = o.style.height;
		lno.scrollTop = o.scrollTop;
	}
}
var drag = {
	e: null,
	divider: null,
	targ: null
};
function dragCode(o) {
	drag.e = o;
	let d = document.createElement("DIV");
	d.id = "divider";
	drag.divider = d;
	//document.getElementById("code").appendChild;
}
mwl += 7;
function updCode(o) {
	event.preventDefault();
	let closest = [null, Infinity];
	for(let c = 0; c < document.getElementById("code").children.length; c++) {
		document.getElementById("code").children[c].style.margin = "0px";
	}
	let tlist = document.getElementById("code").children;
	let list = [];
	for(let c = 0; c < tlist.length; c++) {
		list[c] = [tlist[c].offsetTop+(tlist[c].offsetHeight/2), tlist[c]];
	}
	list.sort(function(a, b) {
		return a[0]-b[0];
	});
	for(let c = 0; c < list.length; c++) {
		let e = list[c][1];
		let my = 0;
		if(event.target.id === "code") {
			my = event.offsetY;
		} else {
			my = event.target.offsetTop+event.offsetY;
		}
		if(my < list[c][0]) {
			if(Math.abs((e.offsetTop+(e.offsetHeight/2))-my) < closest[1]) {
				closest = [e, e.offsetTop];
			}
		}
	}
	drag.targ = null;
	if(closest[0] !== null) { 
		closest[0].style.marginTop = "20px";
		drag.targ = closest[0];
	}
}
function addCode(o) {
	event.preventDefault();
	if((event.toElement.id == "code") && (drag.e.parentNode.id == "tools")) {
		let clone = drag.e.cloneNode(true);
		document.getElementById("code").appendChild(clone);
	} else {
		console.log(drag.e.parentNode.id);
		let clone = drag.e.cloneNode(true);
		drag.targ.insertAdjacentElement("beforebegin", clone);
		drag.targ = null;
	}
}
function endDrag(o) {
	drag.e = null;
	for(let c = 0; c < document.getElementById("code").children.length; c++) {
		document.getElementById("code").children[c].style.margin = "0px";
	}
}
mwl++;
function generateCode() {
	let els = document.getElementById("code").children;
	let code = "";
	let valel = ["right", "left", "up", "down"];
	let translate1 = [
		"right", 
		"left", 
		"up", 
		"down",
		"pickup",
		"teleport",
		"win",
		"read",
		"open"
	];
	let translate2 = [
		"höger", 
		"vänster", 
		"upp", 
		"ner",
		"ta",
		"teleportera",
		"vinn",
		"läs",
		"öppna"
	];
	for(let c = 0; c < els.length; c++) {
		if(valel.indexOf(els[c].id) != -1) {
			code += translate2[translate1.indexOf(els[c].id)]+"("+els[c].innerText.trim()+");";
		} else {
			code += translate2[translate1.indexOf(els[c].id)]+"();";
		}
	}
	console.log(code);
	return code;
}

function actionChangeVal(o) {
	let v = o.innerText;
	v = v.replace(/(?:\r\n|\r|\n)/g, "");
	if(v.length > 2) {
		v = 99;
	}
	o.innerText = v;
}

mwl ++;
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
var requestAnimFrame = (function(){
	return window.requestAnimationFrame||
	window.webkitRequestAnimationFrame||
	window.mozRequestAnimationFrame||
	function(callback){
		window.setTimeout(callback, 1000 / 60);
	};
})();
mwl +=3;
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
	filledCircle: function(x, y, radie, color) {
		ca.beginPath();
		ca.arc(x, y, radie, 0, 2*Math.PI);
		ca.fillStyle = color;
		ca.fill();
		ca.closePath();
	},
	text: function(x, y, text, col = "#000", size = 20, align = "left", base = "top") {
		ca.font = Math.round(size*gameScale)+"px Verdana";
		ca.textAlign = align;
		ca.textBaseline = base;
		ca.fillStyle = col;
		ca.fillText(text, x, y);
	},
	image: function(x, y, width, height, src) {
		if(src.src != "") {
			ca.drawImage(src, x, y, width, height);
		}
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
	},
	tile: function(x, y, width, height, src) {
		let tile = getTile(src);
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
		//console.log(tile);
	},
	textSize: function(txt, size) {
		ca.font = Math.round(size*gameScale)+"px Verdana";
		return ca.measureText(txt).width;
	},
	clear: function() {
		ca.clearRect(0, 0, 1600, 800);
	}
}
mwl += 13;
function ticker() {
	if(running) {
		if(game.actionSwitch) {
			game.player.x = game.level.player.x;
			game.player.y = game.level.player.y;
			game.player.xm = 0;
			game.player.ym = 0;
			if(game.actions.length == 0) {
				if(!winAnimation.on) {
					stop();
				}
			} else {
				game.actions[0].f();
				game.actionSwitch = false;
			}
		}
		if(game.actionType == "playerposition") {
			if((game.level.maps.map[game.level.player.y][game.level.player.x].src == "wall") || (game.level.maps.map[game.level.player.y][game.level.player.x].src == "floor1.stone") || (game.level.maps.imap[game.level.player.y][game.level.player.x].src == "lock")) {
				addEffect("question", game.level.player.x, game.level.player.y);
				game.level.player.x = game.player.x;
				game.level.player.y = game.player.y;
				game.actionType = "wait";
				game.waitingTimer = 70;
				//nextFunc();
			} else {
				if(m.dist(
					{x: game.player.x, y: game.player.y},
					{x: game.level.player.x, y: game.level.player.y}
				) < game.speed*(level+1)*0.4) {
					if(game.level.maps.map[Math.round(game.player.y)][Math.round(game.player.x)].src == "water") {
						game.player.alive = false;
						game.actions.splice(0, game.actions.length);
						game.waitingTimer = 80;
						game.actionType = "death";
						lives--;
						getAnimation("drown").frame = 0;
						getAnimation("death").frame = 0;
						game.actions.push(function() {
							resetGame();
						});
						game.player.xm = 0;
						game.player.ym = 0;
						if(lives <= 0) {
							gameover();
							resetGame();
							if(codemode != "blocks") {
								document.getElementById("inp").value = "";
							} else {
								document.getElementById("code").innerHTML = "";
							}
						}
					} else {
						nextFunc();
					}
				} else {
					let mod = (level+1)*0.4;
					if(game.player.x > game.level.player.x) {
						game.player.xm = -game.speed*mod;
					} else if(game.player.x < game.level.player.x) {
						game.player.xm = game.speed*mod;
					}
					if(game.player.y > game.level.player.y) {
						game.player.ym = -game.speed*mod;
					} else if(game.player.y < game.level.player.y) {
						game.player.ym = game.speed*mod;
					} 
				}
				game.player.x += game.player.xm;
				game.player.y += game.player.ym;
			}
		} else {
			if(game.waitingTimer <= 0) {
				nextFunc();
			} else {
				game.waitingTimer--;
			}
		}
	}
	drawAll();
}
var bg = document.createElement("IMG");
function saveBG() {
	draw.clear();
	drawBG();
	bg.src = canvas.toDataURL("image/png");
}
function rest() {
	draw.image(0, 0, size.width, size.height+scale, bg);
}
mwl += 7;
function drawBG() {
	for(let y in game.level.maps.rmap) {
		for(let x in game.level.maps.rmap[y]) {
			if(game.level.maps.rmap[y][x].aniType == "t") {
				draw.tile(x*scale, y*scale, scale, scale, game.level.maps.rmap[y][x].src);
				if(game.level.maps.rmap[y][x].src == "floor1") {
					draw.tile(x*scale, y*scale, scale, scale, "dirt"+Math.ceil(Math.random()*11));
				}
			} else if(game.level.maps.rmap[y][x].aniType == "a") {
				draw.ani(x*scale, y*scale, scale, scale, game.level.maps.rmap[y][x].src);
			}
		}
	}
	if(gameCompleted) {
		let finishText = "Du har klarat ut spelet med ett rekord på "+finalScore+" poäng!";
		draw.filledRect((size.width/2)-(draw.textSize(finishText, 16)/2)-5, 0, draw.textSize(finishText, 14)+30, 16+10, "rgba(0,0,0,0.5)");
		draw.text(size.width/2, 5, finishText, "rgba(255,255,255,1)", 14, "center", "top");
	}
}
var firstDraw = true;
function drawAll() {
	draw.clear();
	draw.filledRect(0,0,size.width,size.height,"#222");
	rest();
	for(let y in game.level.maps.rmap) {
		for(let x in game.level.maps.rmap[y]) {
			/*
			if(game.level.maps.rmap[y][x].aniType == "t") {
				draw.tile(x*scale, y*scale, scale, scale, game.level.maps.rmap[y][x].src);
			} else if(game.level.maps.rmap[y][x].aniType == "a") {
				draw.ani(x*scale, y*scale, scale, scale, game.level.maps.rmap[y][x].src);
			}*/
			if(game.level.maps.imap[y][x].aniType == "t") {
				draw.tile(x*scale, y*scale, scale, scale, game.level.maps.imap[y][x].src);
			} else if(game.level.maps.imap[y][x].aniType == "a") {
				draw.ani(x*scale, y*scale, scale, scale, game.level.maps.imap[y][x].src);
			}
		}
	}
	for(let y in game.level.maps.rmap) {
		for(let x in game.level.maps.rmap[y]) {
			draw.rect(x*scale, y*scale, scale, scale, "rgba(0,0,0,0.1)");
		}
	}
	if(game.player.alive) {
		if((game.player.xm == 0) && (game.player.ym == 0)) {
			draw.ani(game.player.x*game.scale, game.player.y*game.scale, game.scale, game.scale, "player.down");
		} else if(game.player.xm > 0) {
			draw.ani(game.player.x*game.scale, game.player.y*game.scale, game.scale, game.scale, "player.run.right");
		} else if(game.player.xm < 0) {
			draw.ani(game.player.x*game.scale, game.player.y*game.scale, game.scale, game.scale, "player.run.left");
		} else if(game.player.ym > 0) {
			draw.ani(game.player.x*game.scale, game.player.y*game.scale, game.scale, game.scale, "player.run.down");
		} else if(game.player.ym < 0) {
			draw.ani(game.player.x*game.scale, game.player.y*game.scale, game.scale, game.scale, "player.run.up");
		}
	} else {
		draw.ani((game.player.x)*game.scale, (game.player.y)*game.scale, game.scale, game.scale, "drown");
		draw.ani((game.player.x)*game.scale, (game.player.y-0.5)*game.scale, game.scale, game.scale, "death");
	}
	for(let c in effects) {
		if(typeof effects[c].f != "undefined") {
			effects[c].f();
		}
		if(typeof effects[c] != "undefined") {
			if(effects[c].type == "tile") {
				draw.tile(effects[c].x*game.scale, (effects[c].y*game.scale), game.scale, game.scale, effects[c].src);
			} else if(effects[c].type == "text") {
				let textSize = 30;
				let textWidth = draw.textSize(effects[c].src, textSize);
				draw.filledRect(
					((size.width/2)-(textWidth/2))-25, 
					((size.height/2)-(textSize/2))-25, 
					textWidth+50, 
					textSize+50, 
					"#000"
				);
				draw.rect(
					((size.width/2)-(textWidth/2))-25, 
					((size.height/2)-(textSize/2))-25, 
					textWidth+50, 
					textSize+50, 
					"#fff"
				);
				draw.text(size.width/2, size.height/2, effects[c].src, "#fff", textSize, "center", "middle");
			} else {
				draw.ani(effects[c].x*game.scale, (effects[c].y*game.scale), game.scale, game.scale, effects[c].src);
			}
		}
	}
	if(firstDraw) {
		saveBG();
		firstDraw = false;
	} else {
		ca.clearRect(0, size.height, size.width, scale);
		for(let q = 0; q < lives+tlives; q++) {
			draw.ani(scale*q, size.height, scale, scale, "life1");
		}
		let scoreText = "";
		if(game.score == 0) {
			scoreText = Math.floor(score);
		} else {
			scoreText = Math.floor(score)+" + "+Math.floor(game.score);
		}
		draw.text((scale*(lives+tlives))+(scale*0.5), size.height+(scale*0.2), "Poäng: "+scoreText, "#fff", 14);
		if(game.inventory.indexOf("key") != -1) {
			draw.tile(size.width-scale, size.height, scale, scale, "key");
		}
	}
	if(mouse.distance.down) {
		draw.rect(
			(mouse.distance.from.x*scale), 
			(mouse.distance.from.y*scale), 
			scale, 
			scale, 
		"#0f0");
		draw.rect(
			(mouse.distance.to.x*scale), 
			(mouse.distance.to.y*scale), 
			scale, 
			scale, 
		"#0f0");
		draw.line(
			((mouse.distance.from.x*scale)+(scale/2)), 
			((mouse.distance.from.y*scale)+(scale/2)), 
			((mouse.distance.to.x*scale)+(scale/2)), 
			((mouse.distance.to.y*scale)+(scale/2)), 
		"#0f0");
		let tpos = {
			x: (((mouse.distance.from.x+mouse.distance.to.x)/2)*scale),
			y: (((mouse.distance.from.y+mouse.distance.to.y)/2)*scale)
		};
		//draw.filledRect(tpos.x, tpos.y, scale, scale, "rgba(0,0,0,0.4)");
		//draw.rect(tpos.x, tpos.y, scale, scale, "rgba(0,255,0,0.5)");
		draw.filledCircle(tpos.x+(scale/2), tpos.y+(scale/2), scale/2, "rgba(0,0,0,0.4)");
		draw.circle(tpos.x+(scale/2), tpos.y+(scale/2), scale/2, 1, "rgba(0,255,0,0.5)");
		draw.text(
			(((mouse.distance.from.x*scale)+(scale/2))+((((mouse.distance.to.x*scale)+(scale/2))-((mouse.distance.from.x*scale)+(scale/2)))/2)), 
			(((mouse.distance.from.y*scale)+(scale/2))+((((mouse.distance.to.y*scale)+(scale/2))-((mouse.distance.from.y*scale)+(scale/2)))/2)), 
			(mouse.distance.to.x-mouse.distance.from.x)+","+(mouse.distance.to.y-mouse.distance.from.y), 
			"#fff", 
			10, 
			"center",
			"middle"
		);
	} else if(mouse.on) {
		draw.filledRect((mouse.x)*scale, (mouse.y)*scale, scale, scale, "rgba(0,0,0,0.4)");
		draw.rect((mouse.x)*scale, (mouse.y)*scale, scale, scale, "rgba(0,255,0,0.5)");
		draw.text((mouse.x+0.5)*scale, (mouse.y+0.5)*scale, mouse.x+","+mouse.y, "#fff", 10, "center", "middle");
	}
	if((game.startTextOn === true) && (game.level.startMessage !== undefined)) {
		draw.filledRect(0, 0, size.width, 100, "#000");
		draw.rect(0, 0, size.width, 100, "#fff");
		draw.text(size.width/2, 50, game.level.startMessage, "#fff", 14, "center", "middle");
	}
	draw.text(size.width/2, size.height+6, "Bana "+(level+1)+" av "+levels.length, "#fff", 14, "center", "top");
	if(winAnimation.on == true) {
		winAnimation.f();
		draw.image(winAnimation.x, winAnimation.y, 256*3, 128*3, getImage("win"));
		if(winAnimation.y < (-(128*3))+200) {
			draw.filledRect(0,0,size.width,size.height,"rgba(0,0,0,"+(1-Math.abs(((winAnimation.y-((-(128*3))))/200)))+")");
		}
		if(winAnimation.y < (-(128*3))) {
			if(winAnimation.fadeMode == 0) {
				running = false;
				level++;
				score += game.score;
				lives += tlives;
				tlives = 0;
				draw.clear();
				if(typeof levels[level] == "undefined") {
					console.log("FINISH");
					level = 0;
					finalScore = score;
					gameCompleted = true;
					score = 0;
					lives = 3;
				}
				if(saveEnabled === true) {
					cookie.set("level", level);
					cookie.set("score", score);
					cookie.set("lives", lives);
				}
				if(codemode != "blocks") {
					document.getElementById("inp").value = "";
				} else {
					document.getElementById("code").innerHTML = "";
				}
				game.startTextOn = false;
				clearTimeout(textTimeout);
				loadLevel(level);
				resetGame();
				saveBG();
				winAnimation.fadeMode = 1;
				if((cheating) && (!gameCompleted)) {
					//setTimeout(start, 1000);
				}
			}
		}
		if(winAnimation.y < (-(128*3))-200) {
			winAnimation.on = false;
			stop();
			resetWin();
		}
	}
}
mwl++;

/////////////////////////////////////////
//
//	CHEATS
//
/////////////////////////////////////////
var cheats = [
	"höger(2);\nta();\nhöger(2);\nvinn();",
	"höger(2);\nner(2);\nta();\nupp(4);\nta();\nner(2);\nhöger(2);\nvinn();",
	"upp(3);\nta();\nhöger(4);\nta();\nner(6);\nta();\nvänster(4);\nvinn();",
	"höger();\nupp();\nta();\nner(2);\nta();\nupp();\nhöger(3);\nupp();\nta();\nner(2);\nta();\nupp();\nhöger(3);\nupp();\nta();\nner(2);\nta();\nupp();\nhöger();\nvinn();",
	"upp(2);\nvänster();\nupp(2);\nta();\nner(2);\nhöger(6);\nupp(2);\nta();\nner(2);\nvänster();\nner(4);\nhöger();\nner(2);\nta();\nupp(2);\nvänster(6);\nner(2);\nvinn();",
	"höger();\nupp(2);\nta();\nhöger(6);\nta();\nner(4);\nta();\nvänster(6);\nta();\nhöger(3);\nupp(2);\nta();\nupp(2);\nhöger(3);\nner(2);\nhöger();\nvinn();",
	"upp(4);\nta();\nner(2);\nvänster(3);\nta();\nhöger(3);\nteleportera(21,14);\nhöger(3);\nta();\nvänster(3);\nner(2);\nta();\nupp(4);\nvinn();",
	"höger(3);\nner(3);\nta();\nupp(10);\nläs();\nner(7);\nhöger(3);\nvinn();",
	"höger(2);\nta();\nöppna(18,11,2);\nhöger(3);\nta();\nhöger(3);\nvinn();",
	"upp(8);\nvänster(5);\nta();\nhöger(5);\nner(16);\nvänster(5);\nta();\nhöger(14);\nta();\nvänster(5);\nupp(16);\nhöger(5);\nta();\nvänster(5);\nner(8);\nvänster(2);\nteleportera(13,7);\nner();\nvänster();\nta();\nhöger();\nupp();\nteleportera(13,15);\nupp();\nvänster();\nta();\nhöger();\nner();\nteleportera(25,7);\nner();\nhöger();\nta();\nvänster();\nupp();\nteleportera(25,15);\nupp();\nhöger();\nläs();\nvänster();\nner();\nteleportera(19,11);\nhöger(2);\nner(8);\nvänster(2);\nner(2);\nvinn();",
	"höger(2);\nupp(2);\nta();\nner(6);\nhöger(3);\nta();\nvänster(3);\nner(4);\nvänster(2);\nta();\nhöger(7);\nner();\nta();\nupp();\nhöger(3);\nupp(3);\nvänster(1);\nta();\nhöger();\nner(3);\nhöger(5);\nupp(8);\nvänster(2);\nupp(2);\nvänster();\nöppna(14,10,\"15\");\nvänster(2);\nner(3);\nvänster();\nta();\nhöger();\nupp(3);\nvänster();\nupp(2);\nvänster(3);\nta();\nhöger(3);\nupp(5);\nhöger(2);\nta();\nvänster(2);\nner();\nvänster(6);\nupp();\nta();\nner();\nvänster();\nner(2);\nta();\nupp();\nvänster(2);\nteleportera(20,3);\nhöger(8);\nner(5);\nhöger(2);\nta();\nvänster(2);\nupp();\nvänster(2);\nta();\nhöger(2);\nupp(3);\nhöger(3);\nupp();\nhöger(4);\nner(4);\nhöger();\nta();\nvänster();\nner(4);\nvänster(2);\nta();\nhöger(2);\nupp(8);\nvänster(4);\nner();\nvänster(3);\nupp();\nvänster(8);\nteleportera(31,15);\nvänster(5);\nta();\nhöger(4);\nner(5);\nhöger(2);\nner();\nta();\nupp();\nhöger();\nupp();\nläs();\nner();\nvänster(7);\nupp();\nvänster(4);\nner(3);\nvinn();",
	"vänster(4);\nner();\nta();\nupp();\nvänster(3);\nupp();\nta();\nner();\nvänster(5);\nner();\nta();\nupp();\nvänster(2);\nupp(9);\nhöger(4);\nta();\nvänster(4);\nner(9);\nhöger(14);\nupp(5);\nvänster(6);\nupp(4);\nhöger(3);\nta();\nvänster(3);\nner(4);\nhöger(17);\nupp(4);\nvänster(3);\nta();\nhöger(3);\nner(4);\nhöger(2);\nner();\nteleportera(36,7);\nupp(4);\nvänster(4);\nta();\nhöger(4);\nner(4);\nteleportera(9,16);\nner();\nhöger(2);\nner(4);\nhöger(3);\nta();\nvänster(3);\nupp(4);\nvänster(2);\nupp();\nteleportera(3,17);\nner(4);\nhöger(4);\nläs();\nvänster(4);\nupp(4);\nteleportera(18,3);\nhöger();\nta();\nhöger();\nta();\nhöger();\nta();\nvänster(3);\nteleportera(21,21);\nvänster();\nta();\nvänster();\nta();\nvänster();\nta();\nhöger(3);\nteleportera(30,8);\nupp();\nvänster(8);\nner(5);\nöppna(23,12,\"3.5\");\nhöger(4);\nupp();\nta();\nner();\nhöger(3);\nner();\nta();\nupp();\nhöger(5);\nupp();\nta();\nner();\nhöger(2);\nner(9);\nvänster(4);\nta();\nhöger(4);\nupp(9);\nvänster(14);\nvinn();",
	"höger(6);\nner(3);\nhöger();\nta();\nhöger(2);\nupp();\nhöger(4);\nupp(2);\nhöger();\nteleportera(18,2);\nner();\nta();\nner();\nta();\nner();\nta();\nner();\nta();\nner();\nta();\nupp(5);\nteleportera(15,8);\nvänster(2);\nner(2);\nvänster(3);\nupp(2);\nvänster(5);\nupp(3);\nvänster(3);\nner(6);\nhöger(5);\nner(2);\nhöger(8);\nner(3);\nvänster(7);\nner(3);\nta();\nner();\nvänster(6);\nfor(y=0;y<3;y++){ta();for(x=0;x<11;x++){höger();ta();}if(y!=2){vänster(11);ner();}}\nvänster(5);\nupp(6);\nhöger(7);\nupp(3);\nvänster(8);\nupp(2);\nvänster(5);\nupp(6);\nhöger(3);\nner(3);\nhöger(5);\nner(2);\nhöger(3);\nupp(2);\nhöger(2);\nteleportera(18,13);\nhöger();\nöppna(20,13,\"12\");\nhöger(3);\nta();\nvänster(4);\nteleportera(18,10);\nhöger(3);\nupp(8);\nhöger(16);\nner();\nta();\nner(2);\nvänster(8);\nta();\nvänster();\nner(5);\nta();\nner(6);\nta();\nupp(8);\nhöger(5);\nta();\nhöger();\nta();\nvänster(6);\nupp(3);\nhöger(9);\nner(8);\nta();\nner(7);\nta();\nner(2);\nta();\nupp(20);\nvänster(13);\nner(8);\nhöger();\nner(6);\nvänster(4);\nner(3);\nhöger(10);\nta();\nvänster(10);\nupp(3);\nvänster(3);\nner(3);\nvänster();\nner(3);\nhöger(17);\nupp(11);\nvänster(3);\nta();\nhöger(3);\nner(5);\nvänster(3);\nupp(2);\nvinn();"
];
var cheating = false;
var fuska = function(code = false) {
	if(code == "grymProgr@mmering") {
		if(prompt("Vill du fuska? Gissa vilket nummer jag tänker på?") == mwl) {
			//cheating = true; 
			//document.getElementById("inp").value = cheats[level];
			//throw "Du har aktiverat fusket!";
			throw "Du får inte fuska ändå!";
		} else {
			throw "Du verkar inte ha koll på meningen med livet.";
		}
	} else {
		return "Här fuskar vi inte va?";
	}
}

// BASE
mwl -= 0.5;
var ca = null;
var gameScale = 0.85;
var timer = null;
var svg = [0,1,2,3,4];
var score = 0;
var lives = 3;
var tlives = 0;
var gameCompleted = false;
var saveEnabled = true;
var finalScore = 0;
var notes = [];
var backupGame = {
	speed: 5 * 0.01,
	scale: gameScale*30,
	score: 0,
	textScore: 0,
	player: {
		x: 0,
		y: 0,
		xm: 0,
		ym: 0,
		alive: true
	},
	teleportation: {
		x: 0,
		y: 0
	},
	inventory: [],
	currentAction: 0,
	waitingTimer: 0,
	actionSwitch: true,
	actionType: false,
	actions: [],
	level: false
};
var effects = [];
var level = 0;
var textTimeout = null;
mwl++;
function loadLevel(lvl) {
	game = backupGame;
	game.level = levels[level];
	updNotes();
	if(game.level.startMessage !== undefined) {
		game.startTextOn = true;
		textTimeout = setTimeout(function() {
			game.startTextOn = false;
		}, 4000+(game.level.startMessage.length*25)+((lvl==0)?2000:0));
	}
	//console.log(4000+(game.level.startMessage.length*25)+((lvl==0)?2000:0));
	game.player.x = game.level.player.x;
	game.player.y = game.level.player.y;
	if((cheating) && (cheats[level] != undefined)) {
		document.getElementById("inp").value = cheats[level];
	} else {
		if(cookie.get("inp"+level) !== false) {
			document.getElementById("inp").value = decodeURIComponent(cookie.get("inp"+level));
		}
	}
	updLinecount(document.getElementById("inp"));
}
// Start variables
var size = {
	width: 40 * backupGame.scale,
	height: 25 * backupGame.scale
};
function updSize() {
	size = {
		width: 40 * backupGame.scale,
		height: 25 * backupGame.scale
	};
	size.width = Math.floor(size.width/scale)*scale;
	size.height = Math.floor(size.height/scale)*scale;
}
var game = {};
var scale = backupGame.scale;
updSize();
var winAnimation;
function resetWin() {
	winAnimation = {
		x: ((size.width-(256*3))/2),
		y: size.height+5,
		on: false,
		fadeMode: 0,
		f: function() {
			this.y -= 5;
		}
	};
}
function deleteGame() {
	if(confirm("Är du säker på att du vill börja från början igen?") === true) {
		resetCookie();
		location.reload();
	}
}
function updNotes() {
	nText = [];
	for(let c in notes) {
		if(notes[c].lvl == level) {
			nText.push(notes[c].txt);
		}
	}
	if(nText.length > 0) {
		document.getElementById("notesBut").style.display = "inline-block";
		document.getElementById("notes").innerHTML = nText.join("<br>");
	} else {
		document.getElementById("notesBut").style.display = "none";
		document.getElementById("notes").innerHTML = "";
	}
}
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
mwl += 31;
var mouse = {
	scale: 1,
	x: 0,
	y: 0,
	p: function() {
		return {
			x: mouse.x/mouse.scale, 
			y: mouse.y/mouse.scale
		};
	},
	distance: {
		from: {
			x: 0,
			y: 0
		}, to: {
			x: 0,
			y: 0
		},
		down: false
	},
	on: false
};
var lno = null;
function elog(msg) {
	this.document.querySelector("#err").innerText = JSON.stringify(msg);
}
function elog2(msg) {
	this.document.querySelector("#errlog").innerText = msg;
}
var size1 = null;
function updMScale() {
	let c = document.querySelector("#c");
	setTimeout(function() {
		if(size1 === null) {
			size1 = c.getBoundingClientRect().width;
		}
		c.style.width = "65vw";
		setTimeout(function() {
			let size2 = c.getBoundingClientRect().width;
			mouse.scale = size2/size1;
		}, 1);
	}, 1);
}
var updMScaleTimer = null;
window.addEventListener("resize", function() {
	clearTimeout(updMScaleTimer);
	updMScaleTimer = setTimeout(updMScale, );
});
function loaded() {
	document.querySelector("#inp").addEventListener("input", function() {
		let toSave = document.querySelector("#inp").value;
		cookie.set("inp"+level, encodeURIComponent(toSave));
	});
	try {
		updMScale();
		if(codemode != "blocks") {
			lno = document.createElement("DIV");
			lno.id = "lno";
			document.getElementById("inpFrame").insertBefore(lno, document.getElementById("inpFrame").children[0]);
			lno = document.getElementById("lno");
			updLinecount(document.getElementById("inp"));
		}
		for(let c in functions) {
			window[c] = functions[c];
		}
		if(cookie.get("level") != false) {
			level = parseInt(cookie.get("level"));
			score = parseInt(cookie.get("score"));
			lives = parseInt(cookie.get("lives"));
		}
		loadLevel(level);
		resetWin();
		document.getElementById("c").width = size.width;
		document.getElementById("c").height = size.height+scale;
		ca = getCanvas();
		canvas.addEventListener("mousemove", function(e) {
			mouse.x = Math.floor(e.offsetX/mouse.scale/scale);
			mouse.y = Math.floor(e.offsetY/mouse.scale/scale);
			if((mouse.x >= 0) && (mouse.x <= size.width/scale) && (mouse.y >= 0) && (mouse.y < size.height/scale)) {
				mouse.on = true;
			} else {
				mouse.on = false;
			}
			if(mouse.distance.down) {
				mouse.distance.to.x = mouse.x;
				mouse.distance.to.y = mouse.y;
			}
		});
		canvas.addEventListener("mouseout", function() {
			mouse.on = false;
		});
		canvas.addEventListener("mousedown", function(e) {
			mouse.distance.down = true;
			mouse.distance.from.x = Math.floor(e.offsetX/mouse.scale/scale);
			mouse.distance.from.y = Math.floor(e.offsetY/mouse.scale/scale);
			mouse.distance.to.x = Math.floor(e.offsetX/mouse.scale/scale);
			mouse.distance.to.y = Math.floor(e.offsetY/mouse.scale/scale);
		});
		window.addEventListener("mouseup", function() {
			mouse.distance.down = false;
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
					interval: 5,
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
		running = false;
		(function animLoop(){
			requestAnimFrame(animLoop);
			ticker();
		})();
	} catch(err) {
		elog(err);
	}
}

function nextFunc() {
	game.actionSwitch = true;
	game.actions.splice(0, 1);
}
var funcClass = {
	player: {
		up: {
			f: function() {
				game.level.player.y--;
				game.actionType = "playerposition";
				return this;
			}
		},
		down: {
			f: function() {
				game.level.player.y++;
				game.actionType = "playerposition";
				return this;
			}
		},
		right: {
			f: function() {
				game.level.player.x++;
				game.actionType = "playerposition";
				return this;
			}
		},
		left: {
			f: function() {
				game.level.player.x--;
				game.actionType = "playerposition";
				return this;
			}
		},
		pickup: {
			f: function() {
				game.actionType = "wait";
				if(
					(game.level.maps.imap[game.level.player.y][game.level.player.x].src == "chest") || 
					(game.level.maps.imap[game.level.player.y][game.level.player.x].src == "key") || 
					(game.level.maps.imap[game.level.player.y][game.level.player.x].src == "life1") || 
					(game.level.maps.imap[game.level.player.y][game.level.player.x].src == "treasure")
				) {
					if(game.level.maps.imap[game.level.player.y][game.level.player.x].src == "chest") {
						game.score += 200;
						game.level.maps.imap[game.level.player.y][game.level.player.x] = false;
						effects.push({
							x: game.level.player.x,
							y: game.level.player.y,
							type: "animation",
							src: "powerup",
							f: function() {
								if(this.first) {
									getAnimation("powerup").frame = 1;
									this.first = false;
								}
								if(getAnimation("powerup").frame <= 0) {
									for(let c in effects) {
										if(effects[c].id == this.id) {
											effects.splice(c, 1);
										}
									}
								}
							},
							id: Math.random(),
							first: true
						});
					} else if(game.level.maps.imap[game.level.player.y][game.level.player.x].src == "treasure") {
						game.score += 500;
						game.level.maps.imap[game.level.player.y][game.level.player.x] = false;
						effects.push({
							x: game.level.player.x,
							y: game.level.player.y,
							type: "animation",
							src: "powerup",
							f: function() {
								if(this.first) {
									getAnimation("powerup").frame = 1;
									this.first = false;
								}
								if(getAnimation("powerup").frame <= 0) {
									for(let c in effects) {
										if(effects[c].id == this.id) {
											effects.splice(c, 1);
										}
									}
								}
							},
							id: Math.random(),
							first: true
						});
					} else if(game.level.maps.imap[game.level.player.y][game.level.player.x].src == "key") {
						game.level.maps.imap[game.level.player.y][game.level.player.x] = false;
						effects.push({
							x: game.level.player.x,
							y: game.level.player.y,
							type: "animation",
							src: "powerup",
							f: function() {
								if(this.first) {
									getAnimation("powerup").frame = 1;
									this.first = false;
								}
								if(getAnimation("powerup").frame <= 0) {
									for(let c in effects) {
										if(effects[c].id == this.id) {
											effects.splice(c, 1);
										}
									}
								}
							},
							id: Math.random(),
							first: true
						});
						game.inventory.push("key");
					} else if(game.level.maps.imap[game.level.player.y][game.level.player.x].src == "life1") {
						game.level.maps.imap[game.level.player.y][game.level.player.x] = false;
						effects.push({
							x: game.level.player.x,
							y: game.level.player.y,
							type: "animation",
							src: "powerup",
							f: function() {
								if(this.first) {
									getAnimation("powerup").frame = 1;
									this.first = false;
								}
								if(getAnimation("powerup").frame <= 0) {
									for(let c in effects) {
										if(effects[c].id == this.id) {
											effects.splice(c, 1);
										}
									}
								}
							},
							id: Math.random(),
							first: true
						});
						tlives++;
					}
				} else {
					addEffect("question");
				}
				game.waitingTimer = 150;//55;
				return this;
			}
		},
		read: {
			f: function() {
				game.actionType = "read";
				if(game.level.maps.imap[game.player.y][game.player.x].type == "note") {
					effects.push({
						x: game.level.player.x,
						y: game.level.player.y,
						type: "animation",
						src: "powerup",
						f: function() {
							if(this.first) {
								getAnimation("powerup").frame = 1;
								this.first = false;
							}
							if(getAnimation("powerup").frame <= 0) {
								for(let c in effects) {
									if(effects[c].id == this.id) {
										effects.splice(c, 1);
									}
								}
							}
						},
						id: Math.random(),
						first: true
					});
					addEffect("text", undefined, undefined, game.level.maps.imap[game.player.y][game.player.x].value);
					game.waitingTimer = 80+(game.level.maps.imap[game.player.y][game.player.x].value.length*15);
					notes.push({"lvl": level+1, txt: game.level.maps.imap[game.player.y][game.player.x].value});
					updNotes();
					game.level.maps.imap[game.player.y][game.player.x] = false;
				} else {
					addEffect("question");
					game.waitingTimer = 70;
				}
				return this;
			}
		},
		unlock: {
			f: function() {
				game.actionType = "open";
				let me = funcClass.player.teleport;
				let pos = this.pos[0];
				if((pos.x !== false) && (pos.y !== false) && (pos.pass !== false)) {
					if(pos.pass == game.level.maps.imap[pos.y][pos.x].value) {
						if(game.level.maps.imap[pos.y][pos.x].src == "lock") {
							effects.push({
								x: pos.x,
								y: pos.y,
								type: "animation",
								src: "unlock",
								f: function() {
									if(this.first) {
										getAnimation("unlock").frame = 1;
										this.first = false;
									}
									if(getAnimation("unlock").frame == 5) {
										game.level.maps.imap[pos.y][pos.x] = false;
									}
									if(getAnimation("unlock").frame <= 0) {
										for(let c in effects) {
											if(effects[c].id == this.id) {
												effects.splice(c, 1);
											}
										}
									}
								},
								id: Math.random(),
								first: true
							});
							game.waitingTimer = 100;
						} else {
							game.waitingTimer = 70;
							addEffect("question", pos.x, pos.y);
						}
					} else {
						game.waitingTimer = 70;
						addEffect("question", pos.x, pos.y);
					}
				} else {
					game.waitingTimer = 70;
					addEffect("question", game.level.player.x, game.level.player.y);
				}
				this.pos.splice(0, 1);
				return this;
			},
			pos: []
		},
		teleport: {
			f: function() {
				let me = funcClass.player.teleport;
				let pos = this.pos[0];
				if((game.level.maps.imap[game.level.player.y][game.level.player.x].src == "portal") && (game.level.maps.imap[pos.y][pos.x].src == "portal")) {
					addEffect("teleport2");
					game.actionType = "teleport";
					game.waitingTimer = 20;
					game.actions.splice(1, 0, {
						f: function() {
							game.level.player.x = -2;
							game.level.player.y = -2;
							game.actionType = "teleport";
							game.waitingTimer = 6;
						}
					}, {
						f: function() {
							addEffect("teleport1", pos.x, pos.y);
							game.level.player.x = pos.x;
							game.level.player.y = pos.y;
							game.actionType = "teleport";
							game.waitingTimer = 20;
						}
					},{
						f: function() {
							game.actionType = "teleport";
							game.waitingTimer = 50;
						}
					});
				} else {
					game.waitingTimer = 70;
					addEffect("question", game.level.player.x, game.level.player.y);
					addEffect("question", pos.x, pos.y);
				}
				this.pos.splice(0, 1);
				return this;
			},
			pos: []
		}
	},
	finish: {
		f: function() {
			game.actionType = "wait";
			if((game.level.maps.imap[game.level.player.y][game.level.player.x].src == "door") && (game.inventory.indexOf("key") != -1)) {
				winAnimation.on = true;
			} else {
				addEffect("question");
			}
			game.waitingTimer = 70;
			return this;
		}
	},
	reset: function() {
		funcClass.player.teleport.pos = [];
		funcClass.player.unlock.pos = [];
	}
};
var functions = {
	upp: function(c = 1) {		for(let q = 0; q < c; q++) { game.actions.push(funcClass.player.up)	}},
	ner: function(c = 1) {		for(let q = 0; q < c; q++) { game.actions.push(funcClass.player.down)}},
	vänster: function(c = 1) {	for(let q = 0; q < c; q++) { game.actions.push(funcClass.player.left)}},
	höger: function(c = 1) {	for(let q = 0; q < c; q++) { game.actions.push(funcClass.player.right)}},
	ta: function() {game.actions.push(funcClass.player.pickup)},
	läs: function() {game.actions.push(funcClass.player.read)},
	teleportera: function(x = false, y = false) {	if((x===false)||(y===false)) { throw "X och Y saknas i functionen teleport"; return false; }	funcClass.player.teleport.pos.push({x: x, y: y}); game.actions.push(funcClass.player.teleport);},
	öppna: function(x = false, y = false, pass = false) {	funcClass.player.unlock.pos.push({x: x, y: y, pass: pass}); game.actions.push(funcClass.player.unlock);},
	vinn: function() {game.actions.push(funcClass.finish)}
};
mwl ++;
function addEffect(type, x = game.level.player.x, y = game.level.player.y, args = ["Ingen text"]) {
	if(type == "teleport1") {
		effects.push({
			x: x,
			y: y,
			type: "animation",
			src: "teleport1",
			f: function() {
				if(this.first) {
					getAnimation("teleport1").frame = 1;
					this.first = false;
				}
				if(getAnimation("teleport1").frame <= 0) {
					for(let c in effects) {
						if(effects[c].id == this.id) {
							effects.splice(c, 1);
						}
					}
				}
			},
			id: Math.random(),
			first: true
			
		});
	} else if(type == "teleport2") {
		effects.push({
			x: x,
			y: y,
			type: "animation",
			src: "teleport2",
			f: function() {
				if(this.first) {
					getAnimation("teleport2").frame = 1;
					this.first = false;
				}
				if(getAnimation("teleport2").frame <= 0) {
					for(let c in effects) {
						if(effects[c].id == this.id) {
							effects.splice(c, 1);
						}
					}
				}
			},
			id: Math.random(),
			first: true
			
		});
	} else if(type == "text") {
		effects.push({
			x: x,
			y: y,
			type: "text",
			src: args,
			f: function() {
				if(this.time <= 0) {
					for(let c in effects) {
						if(effects[c].id == this.id) {
							effects.splice(c, 1);
						}
					}
				} else {
					this.time--;	
				}
			},
			id: Math.random(),
			time: 80+(args.length*15)
		});
	} else {
		effects.push({
			x: x,
			y: y,
			type: "animation",
			src: "question",
			f: function() {
				if(this.first) {
					getAnimation("question").frame = 1;
					this.first = false;
				}
				if(getAnimation("question").frame <= 0) {
					for(let c in effects) {
						if(effects[c].id == this.id) {
							effects.splice(c, 1);
						}
					}
				}
			},
			id: Math.random(),
			first: true
			
		});
	}
}
function resetGame() {
	game = JSON.parse(JSON.stringify(backupGame));
	game.actions = [];
	funcClass.reset();
	tlives = 0;
	document.getElementById("err").innerHTML = "Inga fel";
	//saveBg();
}
var running = false;
function stop() {
	document.getElementById("inp").disabled = false;
	document.getElementById("but").disabled = false;
	document.getElementById("but2").disabled = false;
	running = false;
	game.player.xm = 0;
	game.player.ym = 0;
	game.actions = [];
	funcClass.reset();
	//resetGame();
	//clearInterval(timer);
}
var taboo = [
	"eval",
	"XMLHttpRequest",
	"xhr",
	"taboo",
//	"funcClass",
	"canvas",
	"getCanvas",
	"requestAnimFrame",
	"draw",
	"ticker",
	"saveBG",
	"bg",
	"rest",
	"drawBG",
	"firstDraw",
	"drawAll",
	"ca",
	"timer",
	"svg",
	"score",
//	"live",
	"gameCompleted",
	"finalScore",
	"backupGame",
	"effects",
//	"level",
	"loadLevel",
	"size",
	"scale",
	"winAnimation",
	"resetWin",
	"images",
	"animations",
	"tiles",
	"getImage",
	"getAnimation",
	"getTile",
	"mouse",
	"loaded",
	"nextFunc",
	"functions",
	"addEffect",
	"resetGame",
	"running",
	"stop",
	"run",
	"start",
	"window",
	"document",
	"cheating",
	"cheats"
];
var run = function(cmd) {
	let ok = true;
	for(let c in taboo) {
		if(cmd.indexOf(taboo[c]) != -1) {
			ok = taboo[c];
		}
	}
	if(ok === true) {
		game.textScore = cmd.length;
		if(score > 500) {
			score -= 500;
		}
		let tScore = 2500-(game.textScore*5);
		if(tScore < 0) {
			tScore = 0;
		}
		game.score += tScore;
		document.getElementById('help').style.display = 'none';
		document.getElementById('helpbut').style.backgroundColor = '';
		document.getElementById('helpbut').style.color = '';
		let allcommands = cmd;
		let cmds = cmd.split("\n");
		document.getElementById("inp").innerHTML = "";
		for(let c in cmds) {
			try {
				eval(cmds[c]);
				document.getElementById("inp").innerHTML += "<span>"+cmds[c]+"</span>\n";
			} catch(e) {
				let line = (parseInt(c)+1);
				msg = e.toString();
				if(msg.substr(0, "ReferenceError".length) == "ReferenceError") {
					if(msg.substr(-"is not defined".length) == "is not defined") {
						let pos1 = msg.indexOf("ReferenceError");
						let pos2 = msg.indexOf("is not defined");
						document.getElementById("err").innerHTML = "Koden <b>"+msg.substr(pos1+"ReferenceError".length+2, pos2-"ReferenceError".length-3)+"</b> på rad <b>"+line+"</b> finns inte.";
					} else {
						document.getElementById("err").innerHTML = msg;
					}
				} else if(msg.substr(0, "SyntaxError: Unexpected token".length) == "SyntaxError: Unexpected token") {
					let ch = msg.substr("SyntaxError: Unexpected token".length+1);
					document.getElementById("err").innerHTML = "Du har skrivit ett <b>"+ch+"</b> på rad "+line+" som koden inte förväntar sig.";
				} else if(msg.substr(0, "SyntaxError: missing".length) == "SyntaxError: missing") {
					let ch = msg.substr("SyntaxError: missing".length+1);
					ch = ch.replace(/after argument list/g, "");
					document.getElementById("err").innerHTML = "Du saknar ett <b>"+ch+"</b> på rad "+line+".";
				} else {
					document.getElementById("err").innerHTML = msg;
				}
				stop();
				document.getElementById("inp").innerText = allcommands;
				break;
			}
		}
		game.startTextOn = false;
		clearTimeout(textTimeout);
	} else {
		throw new Error("koden '"+ok+"' är blockerad.");
	}
};
mwl -= 23.5;
function start() {
	resetGame();
	game.score = 0;
	running = true;
	document.getElementById("inp").disabled = true;
	document.getElementById("but").disabled = true;
	//document.getElementById("but2").disabled = true;
	if(codemode !== "blocks") {
		let ok = window.run(document.getElementById("inp").value);
	} else {
		let ok = window.run(generateCode());
	}
	updLinecount(document.getElementById("inp"));
}
function gameover() {
	alert("Du har tyvärr förlorat alla extraliv så du får börja om denna banan.");
	window.location.reload();
}
