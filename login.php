<html>
<head><title>Login Message board</title>
</head>
<body>
<?php
echo '<form method="GET"><label> User Name  </label><input type="text" name="username" value=""/><br>';
echo '<label> Password </label> <input type="password" name="password" value=""/> <br>';
echo '<input type="submit" name="Login" value="Login"/>';
try
{
	$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	$dbh->beginTransaction();
	$dbh->exec('delete from users where username="smith"');
	$dbh->exec('insert into users values("smith","' . md5("mypass") . '","John Smith","smith@cse.uta.edu")')
			or die(print_r($dbh->errorInfo(), true));
	$dbh->commit();
}
catch (PDOException $e)
{
	print "Error!: " . $e->getMessage() . "<br/>";
	die();
}
		
try {
		$con1 = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

		if(isset($_GET['Login']))
		{
			$username = $_GET['username'];
			$password = $_GET['password'];
			$userlogin = $con1->prepare('SELECT count(*) FROM  users WHERE username = :username and password=md5(:password)');
			$userlogin->bindParam(':username', $username);
			$userlogin->bindParam(':password', $password);
			$userlogin->execute();
			$login = $userlogin->fetchColumn();
			print $userlogin->fetchColumn();
			if($login > 0)
			{	
				print " Login Successful!!";
				print $login;
				session_start();
				$_SESSION['username']= $username;
				header('location: board.php');
			}
			else
			{

				print " Login Failed!!";
			}
		}
	
} 
catch (PDOException $e)
{
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
echo '</form>';
?>
</body>
</html>
