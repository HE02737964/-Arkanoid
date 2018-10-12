<?php
$mysqli = new mysqli("student_mysql", "204410178", "karta81638", "204410178") or die("連線失敗") .mysqli_connect_error();
mysqli_query($mysqli, "SET NAMES UTF8");
$rank = $mysqli->query("SELECT name, rank FROM member ORDER BY rank DESC");
?>

<!DOCTYPE html>
<html>
<head>
 <title>會員登入</title>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<br><br><br><br>

<div class="container">
 <div class="row jumbotron">
 <div class="col-md-6 col-md-offset-3">
 <h2 class="text-center">排行榜</h2><br/>
 <form>
     <?php
        echo "<table border='2' WIDTH=70% align='center'><tr align='center'>";
        for($i = 0; $i < mysqli_num_fields($rank); $i++)
            echo "<td>" .mysqli_fetch_field_direct($rank, $i)->name. "</td>";
        echo "</tr>";
        while($row = $rank->fetch_array(MYSQLI_ASSOC)){
            echo "<tr align='center'>";
            echo "<td>$row[name]</td>";
            echo "<td>".$row[rank]."秒</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        mysqli_free_result($rank);
        mysqli_close($mysqli);
        echo "<br><h2>排名由上排到下</h2><br>";
        ?>
 <a class="btn btn-warning btn-lg btn-block" href="logout.php">登出帳號</a>
 <a class="btn btn-default btn-lg btn-block" href="game.php">返回遊戲</a>
 </form>
 </div>
 </div>
 </div>


