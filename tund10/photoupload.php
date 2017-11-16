<?php
	require("functions.php");
	require("classes/Photoupload.class.php");
	$notice = "";
	
	//kui pole sisseloginud, siis sisselogimise lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//kui logib välja
	if (isset($_GET["logout"])){
		//lõpetame sessiooni
		session_destroy();
		header("Location: login.php");
	}
	
	//klassi esimene näide
	/*$esimene = new Photoupload("Kaval trikk. ");
	echo $esimene->testPublic;
	//echo $esimene->testPrivate;
	$teine = new Photoupload("Ja nii juba mitu korda! ");*/
	
	//Algab foto laadimise osa
	$target_dir = "../../pics/";
	$target_file = "";
	$uploadOk = 1;
	$maxWidth = 600;
	$maxHeight = 400;
	$marginHor = 10;
	$marginVer = 10;
	//kas vajutati laadimise nuppu
	if(isset($_POST["submit"])) {
		//kas fail on valitud, failinimi olemas
		if(!empty($_FILES["fileToUpload"]["name"])){
			//fikseerin failinime
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			//$target_file = $target_dir .pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] ."_" .(microtime(1) * 10000) ."." .$imageFileType;
			$target_file = "hmv_" .(microtime(1) * 10000) ."." .$imageFileType;
			//Kas on pildi failitüüp
			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if($check !== false) {
				$notice .= "Fail on pilt - " . $check["mime"] . ". ";
				$uploadOk = 1;
			} else {
				$notice .= "See pole pildifail. ";
				$uploadOk = 0;
			}
			//Piirame faili suuruse
			if ($_FILES["fileToUpload"]["size"] > 1000000) {
				$notice .= "Pilt on liiga suur! ";
				$uploadOk = 0;
			}
			//Piirame failitüüpe
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
				$notice .= "Vabandust, vaid jpg, jpeg, png ja gif failid on lubatud! ";
				$uploadOk = 0;
			}
			//Kas saab laadida?
			if ($uploadOk == 0) {
				$notice .= "Vabandust, pilti ei laetud üles! ";
			//Kui saab üles laadida
			} else {
				//pildi laadimine klassi abil
				$myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
				$myPhoto->resizePhoto($maxWidth, $maxHeight);
				$myPhoto->addWatermark("../../graphics/hmv_logo.png", $marginHor, $marginVer);
				$myPhoto->addTextWatermark("Heade mõtete veeb");
				$notice = $myPhoto->savePhoto($target_dir, $target_file);
				//$myPhoto->saveOriginal($directory, $target_file);
				$myPhoto->clearImages();
				unset($myPhoto);
			}//saab salvestada lõppeb
		} else {
			$notice = "Palun valige kõigepealt pildifail!";
		}
	}//if submit lõppeb
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
	<p><a href="?logout=1">Logi välja</a>!</p>
	<p><a href="main.php">Pealeht</a></p>
	<hr>
	<h2>Pildi üleslaadimine</h2>
	<form action="photoupload.php" method="post" enctype="multipart/form-data">
		<label>Valige pildifail:</label>
		<input type="file" name="fileToUpload" id="fileToUpload">
		<br>
		<br>
		<input type="submit" value="Lae üles" name="submit">
	</form>
	<span><?php echo $notice; ?></span>
	<hr>
</body>
</html>