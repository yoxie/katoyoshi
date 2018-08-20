<?php
// 3-1 データベース接続
$dsn = 'データベース';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

// テーブル作成 3-2
$sql="CREATE TABLE m4tbl"
."("
."id INT auto_increment primary key,"
."name char(32) not null,"
."comment TEXT not null," 
."date datetime ,"
."password char(32)"
.");";
$stmt = $pdo->query($sql);

// 編集機能
if ( !empty ($_POST['edit_execute']) ){
	$id = $_POST['edit_execute'];
	$nm = $_POST['name'];
	$kome = $_POST['comment'];
	$jikan = date("Y/m/d H:i:s");
	$pass = $_POST['pass'];
	$sql = "update m4tbl set name='$nm',comment='$kome',date='$jikan',password='$pass' where id='$id'";
	$result = $pdo->query($sql);
// 通常送信機能
}elseif ( !empty ($_POST['comment']) ){
	$sql=$pdo -> prepare("INSERT INTO m4tbl (name,comment,date,password)
	VALUES (:name,:comment,:date,:password)");
	$sql -> bindParam(':name',$name,PDO::PARAM_STR);
	$sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
	$sql -> bindParam(':date',$date,PDO::PARAM_STR);
	$sql -> bindParam(':password',$password,PDO::PARAM_STR);
	$name = $_POST[name];
	$comment = $_POST['comment'];
	$date = date("Y/m/d H:i:s");
	$password = $_POST['pass'];
	$sql -> execute();
}
// 削除機能
if ( !empty ($_POST['delete']) ){
	$id = $_POST['delete'];
	$sql="SELECT password FROM m4tbl where id = '$id'";
	$results=$pdo -> query($sql);
	foreach($results as $row){
		if( $_POST['delete_pass']==$row['password'] ){
			$id = $_POST['delete'];
			$nm = '';
			$kome = '削除されました';
			$jikan = date("Y/m/d H:i:s");
			$sql = "update m4tbl set name='$nm',comment='$kome',date='$jikan' where id='$id'";
			$result = $pdo->query($sql);
		}else{
			echo "パスワードが違います";
		}
	}
}
// 編集モード切替
if ( !empty ($_POST['edit_choice']) ){
	$id = $_POST['edit_choice'];
	$sql="SELECT password FROM m4tbl where id = '$id'";
	$results=$pdo -> query($sql);
	foreach($results as $row){
		if( $_POST['edit_pass']==$row['password'] ){
			$edit_choice = $_POST['edit_choice'];
			$sql='SELECT * FROM m4tbl';
			$results=$pdo -> query($sql);
			foreach($results as $row){
				if( $edit_choice == $row['id'] ){
					$edit_num = $row['id'];
					$edit_name = $row['name'];
					$edit_comment = $row['comment'];
				}
			}
		}else{
			echo "パスワードが違います";
		}
	}
}
?>

<html>
<head>
<title> mission4-1_D-3 </title>
</head>
<body>
<strong><font size="5" color="#0000ff"> 掲示板 </font></strong>
<!-//フォームの定義 ->
<form action="mission_4-1.php" method="post">
  <!-//名前フォーム ->
  名前<br><input type="text" name="name" value="<?php echo $edit_name;?>"><br>
  <!-//コメントフォーム ->
  コメント<br><input type="text" name="comment" value="<?php echo $edit_comment;?>"><br>
  <!-//パスワードフォーム ->
  パスワード<br>
  <input type="text" name="pass" placeholder="パスワード">
  <!-//送信ボタン ->
  <input type="submit" value="送信"><br>
  <!-//編集番号表示(隠す)フォーム ->
  <input type="hidden" name="edit_execute" value="<?php echo $edit_num;?>"><br>
  <!-//削除番号選択フォーム ->
  削除対象番号<br><input type="text" name="delete" value="">
  <input type="text" name="delete_pass" value="" placeholder="パスワード">
  <!-//送信ボタン ->
  <input type="submit" value="削除"><br>
  <!-//編集番号選択フォーム ->
  編集対象番号<br><input type="text" name="edit_choice" value="">
  <input type="text" name="edit_pass" value="" placeholder="パスワード">
  <!-//送信ボタン ->
  <input type="submit" value="編集"><br>
</form>
</body>
</html>

<?php
$sql='SELECT * FROM m4tbl';
$results=$pdo -> query($sql);
foreach($results as $row){
	// $rowの中にはテーブルのカラムが入る
	echo $row['id'].' ';
	echo $row['name'].' ';
	echo $row['comment'].' ';
	echo $row['date'].'<br>';
}
?>