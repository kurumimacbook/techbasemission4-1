<!DOCTYPE html>
<head>
<!-- 文字化け防止-->
<meta charset = 'utf-8'>
<htmlv lang='ja'>
</head>
<body>


<form method='POST' action='mission_4-1.php'>
<!-- placeholder='フォームの中に浮き出る'-->
<!-- type=password：パスワード入力欄を作成する -->

<input type='text' name='comment' placeholder='名前'></br>
<input type='text' name='name' placeholder='コメント'></br>
<input type='password' name='sendpassword' placeholder='パスワード'>
<!-- 送信ボタン-->
<input type='submit' value='送信'>
</br></br>
<!--削除機能-->
<input type='text' name='delete' placeholder='削除対象番号'></br>
<input type='password' name='deletepassword' placeholder='パスワード'>
<!--削除ボタン-->
<input type='submit' value='削除'></br></br>
</form>

<form action='mission_4-1_editform.php' method='POST'>
<!--編集機能-->
<input type='text' name='editnumber' placeholder='編集対象番号'></br>
<input type='password' name='editpassword' placeholder='パスワード'>
<!--編集ボタン-->
<input type='submit' value='編集'></br>                                                                                                                                                                                                                           
</form>


<?php
// mysql接続・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
// テーブル作成・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
$sql="CREATE TABLE IF NOT EXISTS bbs"
."("
."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."upload_time char(19),"
."pw char(32)"
.");";
$stmt=$pdo->query($sql);

// テーブル一覧を表示するコマンドを使って作成ができたか確認・・・・・・・・・・・
echo "<hr>";
//・・・・・・・・・・・・・・・・・・・・
$time=date('Y/m/d H:i:s'); 
//date関数（日時を出力）

//・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["sendpassword"])){
	$sql = $pdo -> prepare("INSERT INTO bbs (name, comment, upload_time,pw) VALUES
	(:name, :comment, :upload_time, :pw)");
	$name = $_POST["name"];
	$comment = $_POST["comment"];
	$sendpassword = $_POST["sendpassword"];
	
	$sql -> bindParam(':name' , $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':upload_time', $time, PDO::PARAM_STR);
    
    $sql -> bindParam(':pw', $sendpassword, PDO::PARAM_STR);
    $sql -> execute();
   }
   
   //・・・・・・・・・・・・・・・・・・・・・・
   
   $delnumber = $_POST['delete'];
   $deletepassword=$_POST['deletepassword'];
   $sql = 'SELECT pw FROM bbs WHERE id=:id' ;
   $stmt = $pdo->prepare($sql);
   $stmt->bindParam(':id', $delnumber, PDO::PARAM_INT);
   $stmt->execute();
   $delete_row = $stmt->fetchAll();
   
   $answer_pw_delete = $delete_row[0];
   $answer_pw_delete = $answer_pw_delete["pw"];
  
   if(!empty($_POST["delete"]) && $deletepassword == $answer_pw_delete){
   			$sql = 'DELETE FROM bbs WHERE id=:id' ;
   			$stmt = $pdo->prepare($sql);
   			$stmt->bindParam(':id', $delnumber, PDO::PARAM_INT);
   			$stmt->execute();
   			   }
   
   //・・・・・・・・・・・・・・・・・・・・・・・・
   
	$editname = $_POST["editname"];
	$editcomment = $_POST["editcomment"];
	$editnumber = $_POST["editnumber"];
	$editpassword = $_POST["editpassword"];
	$editflag = $_POST["editflag"];
   
   $sql = 'SELECT * FROM bbs where id=:id';
   $stmt = $pdo->prepare($sql);
   $stmt->bindParam(':id', $editnumber, PDO::PARAM_INT);
   $stmt->execute();
   $result = $stmt->fetchAll();
   $edit_row = $result[0];
   $answer_pw_edit = $edit_row["pw"];
   
   if(!empty($_POST["editnumber"]) && $editflag == 0){
   			$sql = 'UPDATE bbs SET id=:id,name=:name, comment=:comment,upload_time=:upload_time,pw=:pw where id=:id';
   			$stmt = $pdo->prepare($sql);
	$stmt -> bindParam(':id', $editnumber, PDO::PARAM_INT);
	$stmt -> bindParam(':name', $editname, PDO::PARAM_STR);
	$stmt -> bindParam(':comment', $editcomment, PDO::PARAM_STR);
	$stmt -> bindParam(':upload_time', $time, PDO::PARAM_STR);
	$stmt -> bindParam(':pw', $editpassword, PDO::PARAM_STR);
	$stmt -> execute();
	}

//・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・

$sql = 'SELECT * FROM bbs ORDER BY id ASC';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
?>

	<?php
	foreach ($results as $row){
		   echo $row['id']." ";
		   echo $row['comment']." ";
		   echo $row['name']." ";
		   echo $row['upload_time']." ";
		   echo "<br>";
		}
		?>
</table>
</body>
</html>