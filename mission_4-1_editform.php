<!DOCTYPE html>
<head>
<!-- 文字化け防止-->
<meta charset = 'utf-8'>
<htmlv lang='ja'>
</head>
<body>
<?php
// mysql接続・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

// ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・

$editnumber = $_POST["editnumber"];
$editpassword = $_POST["editpassword"];
if(!empty($_POST["editnumber"])){
	$sql = 'SELECT * FROM bbs where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $editnumber, PDO::PARAM_INT);
	$stmt->execute();
	$result = $stmt->fetchAll();
	$editrow = $result[0];
	
	$answer_pw_edit = $editrow["pw"];
	if($editpassword == $answer_pw_edit){
		$editname = $editrow["name"];
		$editcomment = $editrow["comment"];
		$value_pw = $editpassword;
	}
}

if($editpassword != $answer_pw_edit){
	$editflag = 1;
	?> <h3>パスワードが違います</h3> <?php
}else{
	$editflag = 0;
}
	
//・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
?>

<form action="mission_4-1.php" method="post">

	<input type="hidden" name="editnumber" value="<?php echo $editnumber; ?>">
	<input type="hidden" name="editflag" value="<?php echo $editflag; ?>">

編集番号：<?php echo $editnumber;?> <br>
<input type="text" name="editname" value="<?php echo $editname;?>" placeholder="名前"><br>
 <input type="text" name="editcomment" value="<?php echo $editcomment;?>" placeholder="コメント"><br>
 <input type="text" name="editpassword" value= "<?php echo $value_pw;?>" placeholder="パスワード">
 <input type="submit" value="送信">

</form>
</body>
</html>