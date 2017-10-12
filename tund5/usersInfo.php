<?php
	//et pääseks ligi sessioonile ja funktsioonidele
	require("functions.php");
	
	//kui pole sisseloginud, liigume login lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: login.php");
		exit();
	}

	require("../../usersinfotable.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
	<?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?> veebiproge
	</title>
</head>
<body>
	<h1><?php echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?>, veebiprogrammeerimine</h1>
	<p>See veebileht on loodud õppetöö raames ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p><a href="?logout=1">Logi välja!</a></p>
	<p><a href="main.php">Pealeht</a></p>
	<hr>
	<h2>Kõik süsteemi kasutajad</h2>
	<?php echo createUsersTable(); ?>
	
	<hr>
</body>
</html>