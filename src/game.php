<?php
//關閉系統提示
error_reporting(0);
session_start();

$mysqli = new mysqli("student_mysql", "204410178", "karta81638", "204410178") or die("連線失敗") .mysqli_connect_error();
mysqli_query($mysqli, "SET NAMES UTF8");
$result = $mysqli->query("SELECT * FROM member");

// 檢査是否有登入（Session 有被設定）
if(isset($_SESSION["email"])==FALSE) {
 //如果沒有，轉址到登入頁面
 header('Location: ../index.php');
}
?>
<?php
/*$mysqli = new mysqli("student_mysql", "204410178", "karta81638", "204410178") or die("連線失敗") .mysqli_connect_error();
mysqli_query($mysqli, "SET NAMES UTF8");
$result = $mysqli->query("SELECT * FROM member");
$row = $result->fetch_array(MYSQLI_ASSOC);
$_SESSION["nick"]=$row["nick"];
*/
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="initial-scale=1,user-scalable=no">
	<meta charset=utf-8>
	<title><?php echo $_SESSION["nick"];echo "打磚塊"?></title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	
<style>
body {
	color: #ffffff;
	background-color: #000000;
	font-family: sans-serif;
	margin: 0px;
	padding: 0px;
}

#screen {
	width: 100px;
	height: 100px;
	text-align:center;
	margin: 0px;
	padding: 0px;
	background-color: #002200;
	float: left;
}

#canvas{
	margin: 0px;
	width: 500px;
	height: 500px;
	position: absolute;
	left: 0px;
	top: 0px;
	padding: 0px;
}

#blocks {
	margin: 0px;
	margin-top: 3%;
	width: 100%;
}

.score {
	font-size: 25px;
	color: #888888;
	font-weight: bold;
}

#gameover a {
	color: #0088ff;
	text-decoration: none;
}

</style>
</head>
<body>

<div id='screen'>

	<form name=fm>
		時間：<input type="text" name="displayBox" value="0" size=4 >
	</form>
	<img src='../img/blocks.svg' id='blocks' />
	<div id='description'>
		<h1>Hi <?php echo $_SESSION["name"]; ?></h1><br>
		<h1>Tap [space] to start</h1><br>
		<h1>Good luck</h1>
	</div>
	<div id='gameover' style='display:none'>
		<h1>Game Over</h1>
		<h1><a href='javascript:gameStart()'>Replay</a> | 
		<a href='javascript:tweet()'>Tweet</a></h1><br>
		<h1><a href='javascript:f()'>上傳分數並查看排行榜</a></h1><br>
		<h1><a href='javascript:logout()'>登出</a></h1>
	</div>
</div>
<canvas id='canvas' width=500 height=500></canvas>

<div>
<div id='ads'>

<script>
	var logout = function() {
	var url = "logout.php";
	location.href = url;
}
</script>
<script>
   /* var c = 0;
	var t;
	var timer_is_on = 0;

	function timedCount() {
    	document.getElementById("displayBox").value = c;
    	c = c + 1;
    	t = setTimeout(function(){timedCount()}, 1000);
	}

	function startCount() {
    	if (!timer_is_on) {
       		timer_is_on = 1;
        	timedCount();
    	}
	}

	function stopCount() {
    	clearTimeout(t);
    	timer_is_on = 0;
	}
	
	x = 0
	function countSecond(){　
		x = x+1
		document.fm.displayBox.value=x
		Time = setTimeout("countSecond()", 1000) 
		clearTimeout(x);
	}*/
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

var score;
var c = 0;
var t;
var timer_is_on = 0;

	function timedCount() {
    	//document.getElementById("displayBox").value = c;
    	document.fm.displayBox.value=c;
    	c = c + 1;
    	t = setTimeout(function(){timedCount()}, 1000);
	}

	function startCount() {
    	if (!timer_is_on) {
       		timer_is_on = 1;
        	timedCount();
    	}
	}

	function stopCount() {
    	clearTimeout(t);
    	timer_is_on = 0;
	}



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

	startCount();

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
	stopCount();
	$("#canvas").fadeOut(); // not to prevent click replay
	$("#gameover").fadeIn();

	clearInterval(interval);

	$("#canvas").off("touchstart");
	$("#canvas").off("touchmove");
	$("#canvas").off("touchend");
	$(document).off("keydown");
	$(document).off("keyup");

	alert("存活" + (c-1) + "秒");  
	score = c-1;
	c=0;
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
	if (score < 10){
		var content = "你存活了"+ score +"秒，是否分享給其他人嘲笑啊？" + document.title +"ㄏㄏㄏ";
		var encoded = encodeURIComponent(content);
		var url = "https://twitter.com/intent/tweet?text=" + encoded;
		window.open(url);	
	}else if(score < 100){
		var content = "你存活了"+ score +"秒，好像有點厲害，但還是要分享給其他人嘲笑啊？" + document.title;
		var encoded = encodeURIComponent(content);
		var url = "https://twitter.com/intent/tweet?text=" + encoded;// + "&url="+ document.location;
		window.open(url);	
	}else{
		var content = "你存活了"+ score +"秒，時間很多逆？" + document.title;
		var encoded = encodeURIComponent(content);
		var url = "https://twitter.com/intent/tweet?text=" + encoded;
		window.open(url);	
	}
}
</script>
<Script>
	function f(){
		if(confirm("確認上傳成績？")){
			location.href="update.php?score=" +score;
		}else{
			
		}
	}
</script>
<script>
     function keyevent(){
     	if(event.keyCode==32)
     	countSecond();
     }
     document.onkeydown = keyevent;
</script>
<?php 
$_SESSION["time"] = score;
$SaveNewData=$mysqli->query("INSERT INTO member (time) VALUES('$_GET[time]");
?>

<?php
	if($_GET["time"] > $row["time"]) $mysqli->query($SaveNewData);
?>	
<!--
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-5819668657787008"
     data-ad-slot="6424531548"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
-->
</body>
</html>

