function showPassWord() {
        if($('#checkPassWord').prop('checked')) {
            $('<input class="form-control"  id="pwd" type="text"/>').val($('#pwd').val()).insertAfter('#pwd').prev().remove();
        } else {
            $('<input class="form-control"  id="pwd" type="password"/>').val($('#pwd').val()).insertAfter('#pwd').prev().remove();
        }
    }
"use strict";

// parameters (static)
var screenW;
var canvasW = 500;
var canvasH = 500;
var barW = 100;
var barH = 10;
var barY = canvasH - 50;
var fps = 60;
var barSpeed = 5;
var ballR = 7;
var ballLaunch = -7.4;

// parameters (physics)
var barX = (canvasW - barW) / 2; // position of bar
var barV = 0; // velocity of bar
var ballX = (canvasW - ballR) / 2;
var ballY = barY - ballR;
var ballVx = 0;
var ballVy = 0;

// parameters (game)
var count = 0;

// global
var canvas;
var ctx;
var touch = false;
var interval;
var touchPos;
var offset;

$(document).ready( function() {
	init();
} );

var init = function () {
	// distinguish PC and mobile
	var ua = navigator.userAgent;
	if (ua.search(/iPhone/) == -1 && ua.search(/iPad/) == -1 && ua.search(/Android/) == -1) {
		touch = false;
	} else {
		touch = true;
	}

	if (touch) {
		$("#canvas").on("click", function(e) {
			$("#canvas").off("click");
			gameStart();
		});
	} else {
		$(document).on("keydown", function(e) {
			if (e.which == 32) {
				$(document).off("keydown");
				gameStart();
			}
		});
	}

	canvas = $("#canvas")[0];
	if (! canvas || ! canvas.getContext ) { return false; }
	ctx = canvas.getContext('2d');

	// set Canvas Size
	if ($(window).width() > $(window).height() ) {
		// landscape
		screenW = $(window).height() - 150;
	} else {
		// portlait 
		screenW = $(window).width();
	}
	$("#screen").width(screenW)
	$("#screen").height(screenW)
	$("#canvas").width(screenW)
	$("#canvas").height(screenW)

	screenW = $("#canvas").width();
	offset = $("#canvas").offset().left;

	drawBar();
	drawBall();

	// description 
	if (touch) {
		$("#description").html("<h1>�H?����???????�Y?</h1>?????????");
	}
};

var gameStart = function () {
	$("#canvas").show();
	$("#description").fadeOut();
	$("#gameover").fadeOut();

	// set ini pos of bar
	barX = (canvasW - barW) / 2; // position of bar
	barV = 0;
	touchPos = undefined;

	// set initial position of ball
	ballX = (canvasW - ballR) / 2;
	ballY = barY - ballR;

	// set initial velocity of ball
	ballVx = (Math.random() + 1) * (Math.floor(Math.random()*2)*2-1);
	ballVy = ballLaunch;

	// set key action
	if (touch) {
		touchOperation();
	}else{
		keyOperation();
	}

	// start
	interval = setInterval(frame, 1000/fps);
};

// refresh screen
var clear = function() {

	//var ctx = canvas.getContext("2d");
	ctx.clearRect(0, 0, canvasW, canvasH);

	return;
}

var drawBar = function() {
	ctx.beginPath();
	ctx.rect(barX, barY, barW, barH);
	ctx.fillStyle = "#00ff00";
	ctx.fill();

	return;
};

var drawBall = function() {
	ctx.beginPath();
	ctx.fillStyle = "#ffffff";
	ctx.arc(ballX, ballY, ballR, 0, Math.PI*2, false);
	ctx.fill();
}

var keyOperation = function() {
	var left = 37;
	var right = 39;

	$(document).on("keydown", function(e) {
		if (e.which == left ) barV = -1;
		if (e.which == right) barV = +1;
	} );

	$(document).on("keyup", function(e) {
		// move left 
		if ((e.which == left ) && (barV == -1)) barV = 0;
		if ((e.which == right) && (barV == +1)) barV = 0;
	} );
};

var touchOperation = function () {
	$("#canvas").on("touchstart", function(e) {
		touchPos = event.changedTouches[0].pageX * canvasW / screenW;
		e.preventDefault();
	});
	$("#canvas").on("touchmove", function(e) {
		touchPos = event.changedTouches[0].pageX * canvasW / screenW;
		e.preventDefault();
	});
	$("#canvas").on("touchend", function(e) {
		touchPos = undefined;
		e.preventDefault();
	} );
};

var moveBar = function (){
	if (touch) {
		barV = 0;
		if (touchPos < barX + (barW / 2) - barSpeed) barV = -1;
		if (touchPos > barX + (barW / 2) + barSpeed) barV = +1;
	}

	barX += barV * barSpeed;
	if (barX < 0) barX = 0;
	if (barX + barW > canvasW) barX = canvasW - barW;
};

var moveBall = function() {
	ballX += ballVx;
	ballY += ballVy;

	// gravity
	ballVy += 0.1;

	// bound wall
	if ((ballX - ballR < 0) && (ballVx < 0)) ballVx *= -1;
	if ((ballX + ballR > canvasW) && (ballVx > 0)) ballVx *= -1;

	// bound bar
	if ((ballY + ballR > barY)
		&& (ballY + ballR < barY + barH)
		&& (ballVy > 0)
		&& (ballX > barX)
		&& (ballX < barX + barW)
		) { 
		count ++;
		ballVy = ballLaunch + Math.random() * 0.3;

		// perturbation
		var perturb = Math.pow(1.0 + (Math.random() - 0.3)*0.03, count);
		console.log (count, perturb);
		ballVx *= perturb;

		// accell by bar
		ballVx += barV;
		
	}

	// gameover 
	if (ballY > canvasH) gameOver();

}

var gameOver = function() {
	$("#canvas").fadeOut(); // not to prevent click replay
	$("#gameover").fadeIn();

	clearInterval(interval);

	$("#canvas").off("touchstart");
	$("#canvas").off("touchmove");
	$("#canvas").off("touchend");
	$(document).off("keydown");
	$(document).off("keyup");
};

// game frame
var frame = function() {
	clear();

	moveBar();
	moveBall();

	drawBar();
	drawBall();
};


//tweet score
var tweet = function() {
	var content = "你的分數0分，是否分享給其他人嘲笑？" + document.title;
	var encoded = encodeURIComponent(content);
	var url = "https://twitter.com/intent/tweet?text=" + encoded + "&url="+ document.location;
	window.open(url);
}

