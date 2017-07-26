<html>
<head><title>Message Board</title></head>
<body>
<?php
session_start();
$username = $_SESSION['username'];
print $username;
echo '<form method="POST"> ';
if($username == '')
{
	session_destroy();
	header('location: login.php');
}
else{
	boardscreen($username);
}

function boardscreen($username){
echo '<input type="submit" name="logout" value="Logout"/><br>';
if(isset($_POST['logout']))
	{
		session_destroy();
		header('location: login.php');
	}
typenewpost($username);
echo "All Messages in forum: ";
printpostsheader();
if(isset($_POST['reply']))
	{	
		$Message = $_POST['textarea'];
		$mid = $_GET['mid'];
		typereply($username,$mid,$Message);
	}
error_reporting(E_ALL);
ini_set('display_errors','On');
printposts();
echo "</form>";
}
function typenewpost($username){
	echo '<textarea name = "textarea"></textarea><br>';
	echo '<input type="submit" name="post" value="New Post"/><br>';
	if(isset($_POST['post']))
	{
		$msg=$_POST['textarea'];
		try
		{
			$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			$dbh->beginTransaction();
			$dbh->exec('INSERT INTO `posts` (`id`, `postedby`, `datetime`, `message`) VALUES ("'. uniqid() . '"' .','. '"'.$username.'"' .', now(),"' . $msg . '")') or die(print_r($dbh->errorInfo(), true)) ;
			$dbh->commit();
		}
		catch (PDOException $e)
		{
		  print "Error!: " . $e->getMessage() . "<br/>";
		  die();
		}
	}
}
function printpostsheader(){
	echo "<table border=1 bgcolor=#DCDCDC>";
	echo '<tr>	<th> Message ID </th>	<th> User Name </th>	<th> Full Name </th>	<th> Date and Time </th>	<th> Reply To </th>	<th> Message Text </th>	<th> Reply Messages </th>	</tr>';
	echo "</table>";
}
function typereply($username,$mid,$Message){
	try
	{
		$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$dbh->beginTransaction();
		$dbh->exec('INSERT INTO `posts` VALUES ("'. uniqid() . '"' .',"'.$mid.'","'.$username.'"' .', now(),"' . $Message . '")') or die(print_r($dbh->errorInfo(), true)) ;
		$dbh->commit();
	}
	catch (PDOException $e)
	{
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
}
function printposts(){
try {
	$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	$dbh->beginTransaction();
	$stmt = $dbh->prepare('select p.id, u.username, u.fullname, p.datetime, p.replyto, p.message from users u, posts p where u.username = p.postedby order by p.datetime desc');
	$stmt->execute();
	echo "<table border=1 bgcolor=#DCDCDC>";
	while ($entry = $stmt->fetch(PDO::FETCH_ASSOC)) {
			echo "<tr> <td>". $entry['id']. "</td>";
			echo "<td>". $entry['username']. "</td>";
			echo "<td>". $entry['fullname']. "</td>";
			echo "<td>". $entry['datetime']. "</td>";
			echo "<td>". $entry['replyto']. "</td>";
			echo "<td>". $entry['message']. "</td>";
			$rply=$entry['id'];
		echo '<td><button type=submit name=reply formmethod=POST formaction="board.php?mid='.$rply. '">Reply</button></td></tr>';			
	}
	echo "</table>";
}
catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
}
?>
</body>
</html>
