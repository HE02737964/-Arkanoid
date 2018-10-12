<?php

$error_flag = FALSE;
$notfound_flag = FALSE;

//對資料庫伺服器進行連線，並選擇對應的會員資料庫
$mysqli = new mysqli("student_mysql", "204410178", "karta81638", "204410178") or die("連線失敗") .mysqli_connect_error();
//$mysqli = new mysqli("student_mysql", "204410178", "karta81638", "204410178");

//解決網頁亂碼問題
mysqli_query($mysqli, "SET NAMES UTF8");

//如果收到 POST 表單送來的登入資料，到資料庫檢?是否有這個人存在
//（使用 mysql_query("SELECT ...... ")，然後把回傳的東西透過 mysql_fetch_array(......) 來檢?）
$result = $mysqli->query("SELECT * FROM member");

//如果有找到，檢?密碼是否相符
while($row = $result->fetch_array(MYSQLI_ASSOC)){

 //先檢?使用者有沒有輸入資料
 if(empty($_POST["email"])==FALSE && empty($_POST["pass"])==FALSE){

 //防範攻擊
 $userEmail=$_POST["email"];
 $userEmail=$mysqli->real_escape_string($userEmail);
 $userPassword=$_POST["pass"];
 $userPassword=$mysqli->real_escape_string($userPassword);

 //有輸入資料的話，再來看輸入的email跟資料庫是否一致
 if($row["email"]==$_POST["email"]){

 if($row["password"]==$_POST["pass"]){
 //如果相符合，則設定 Session（記得要先 session_start()！），並轉址到會員中心（member.php）
 session_start();
 $_SESSION["email"]=$_POST["email"];
 $_SESSION["password"]=$_POST["pass"];
 $_SESSION["name"]=$row["name"];
 $_SESSION["nick"]=$row["nick"];

 //讓網頁轉址的 PHP 寫法：header('Location: member.php');
 //ob_start();
 header('Location: src/game.php');
 //ob_end_flush();
 exit;


 }else{
 //如果不符合，則設定 $error_flag 為 TRUE，繼續顯示網頁?容
 $error_flag = TRUE;
 break;
 }

 }else{
 //如果沒有找到，則設定 $notfound_flag 為 TRUE，繼續顯示網頁?容
 $notfound_flag = TRUE;
 }

 }else{
 //如果沒收到，繼續顯示網頁?容
 }
 
}
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
 <h2 class="text-center">會員登入</h2><br/>
 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"> 
 <input class="form-control input-lg" id="pass" type="text" name="email" required="TRUE" placeholder="E-Mail"/><br/>
 <input class="form-control input-lg" id="pass" type="password" name="pass" required="TRUE" placeholder="密碼"/><br/>
 <input class="btn btn-primary btn-lg btn-block" type="submit" value="登入"/>
 <a class="btn btn-warning btn-lg btn-block" href="src/ranking.php">排行榜</a>
 <a class="btn btn-default btn-lg btn-block" href="src/register.php">會員註冊</a>
 <?php echo $_SESSION["rank"]; ?>
 </form>
 <br/>
 <?php if($error_flag){ ?>
  <div class="alert alert-danger alert-dismissible" role="alert">
  <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 密碼錯誤！
  </div>
 <?php }?>

 <?php if($notfound_flag){ ?>
  <div class="alert alert-danger alert-dismissible" role="alert">
  <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> 未找到本使用者，請重新確認！
  </div>
 <?php }?>
 </div>
 </div>
</div>
</body>
</html>