<?php

$noInfo_flag = false;
$duplicate_flag = false;
$success_flag=false;
$fail_flag=false;

//關閉提示
error_reporting(0);
$mysqli = new mysqli("student_mysql", "204410178", "karta81638", "204410178");
$result = $mysqli->query("SELECT name, email, password, nick FROM member");
mysqli_query($mysqli, "SET NAMES UTF8");

//送出鍵按出後
if(isset($_GET['submit'])==true){
 //檢査所有欄位有沒有輸入
 if(empty($_GET['email'])==true || empty($_GET['password'])==true || empty($_GET['name'])==true || empty($_GET['nick'])==true){
 //有缺的話，叫使用者寫完
 $noInfo_flag = true;
}
}

//送出鍵按出，使用者也有輸入資料的情況
if(isset($_GET['submit'])==true && empty($_GET['email'])==false && empty($_GET['password'])==false && empty($_GET['name'])==false && empty($_GET['nick'])==false){

 //用 WHERE 檢查是否重複註冊
 //mysql_query()裡面要用'' 參考
 //$result=$mysqli->query("SELECT * FROM member <WHE></WHE>RE email='$_GET[email]'");
 $row = $result->fetch_array(MYSQLI_ASSOC);
 if($row["email"]==$_GET["email"]){
 //重覆到的話，退回
 $duplicate_flag = true;
 }else{
 
 //沒有重複到，寫入資料
 $SaveNewData=$mysqli->query("INSERT INTO member (name, email, password, nick, rank) VALUES('$_GET[name]','$_GET[email]','$_GET[password]','$_GET[nick]','')");
 
 //檢查註冊是否成功
 if(!$SaveNewData){
 $fail_flag=true;
 }else{
 $success_flag=true;
 }
 }
}

?>

<!DOCTYPE html>
<html>
<head>
 <title>會員註冊</title>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<br><br><br><br>
<div class="container">
 <div class="row jumbotron">
 <div class="col-md-6 col-md-offset-3"> 
 <h2 class="text-center">會員註冊</h2>
 <hr>
 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="GET">
 <div class="form-group">
 <label for="input-email">Email 帳號 *</label>
 <input type="email" class="form-control" id="input-email" name="email">
 </div>
 <div class="form-group">
 <label for="input-name">真實姓名 *</label>
 <input type="text" class="form-control" id="input-name" name="name">
 </div>
 <div class="form-group">
 <label for="input-nick">匿稱 *</label>
 <input type="text" class="form-control" id="input-nick" name="nick">
 </div>
 <div class="form-group">
 <label for="input-password">密碼 *</label>
 <input type="password" class="form-control" id="input-password" name="password">
 </div>
 </div>
 <br>
 <input type="submit" class="btn btn-primary btn-lg btn-block" value="註冊" name="submit">
 <a class="btn btn-warning btn-lg btn-block" href="Information.html">遊戲說明</a>
 <a class="btn btn-default btn-lg btn-block" href="../index.php">返回登入</a>
 </form>
 <?php if($noInfo_flag){ ?>
 <div class="alert alert-danger alert-dismissible" role="alert">
 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 請輸入所有欄位！
 </div>
 <?php }?>

 <?php if($duplicate_flag){ ?>
 <div class="alert alert-danger alert-dismissible" role="alert">
 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 此 Eamil 帳號已經註冊過！
 </div>
 <?php }?>

 <?php if($success_flag){ ?>
 <div class="alert alert-danger alert-dismissible" role="alert">
 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 註冊成功！
 </div>
 <?php }?>

 <?php if($fail_flag){ ?>
 <div class="alert alert-danger alert-dismissible" role="alert">
 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
 註冊失敗！
 </div>
 <?php }?>
 </div>
 </div>
</div>
</body>
</html>