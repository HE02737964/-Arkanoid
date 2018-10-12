<?php
    $mysqli = new mysqli("student_mysql", "204410178", "karta81638", "204410178") or die("連線失敗") .mysqli_connect_error();
    mysqli_query($mysqli, "SET NAMES UTF8");
    session_start();
    $result = $mysqli->query("SELECT * FROM member");
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $E = $_GET['score'];
    $S = $_SESSION['email'];
    $updata = $mysqli->query("UPDATE member SET rank = '$E' WHERE email= '$S'");
    if($E > $row['rank']){
        $updata;
        header('Location: ranking.php');
        exit;
    }else{
        echo "未達到排行榜標準";
    }
    //mysqli_close($mysqli);
    ?>