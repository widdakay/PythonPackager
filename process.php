<?php

define('ROOT', './');

require_once(ROOT.'include/main.php');





$allowedExts = array("gif", "jpeg", "jpg", "png", "zip");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if (($_FILES["file"]["size"] < 20000000)) {
	if ($_FILES["file"]["error"] > 0) {
		echo "Error: " . $_FILES["file"]["error"] . "<br>";
	}
	else {

		if (isset($_SESSION['projectId'])) {
			$id = $_SESSION['projectId'];
		}
		$projectFolder = ROOT . "uploads/" . $id;
		mkdir($projectFolder);
		mkdir($projectFolder."/src");
		mkdir($projectFolder."/mac");
		mkdir($projectFolder."/lin");
		mkdir($projectFolder."/win");



		//echo "Id: " . $id . "<br>";


		//echo "Name: " . $_FILES["file"]["name"] . "<br>";
		//echo "Type: " . $_FILES["file"]["type"] . "<br>";
		//echo "Size: " . ($_FILES["file"]["size"] / 1024 / 1024) . " MB<br>";
		//echo "Stored in: " . $_FILES["file"]["tmp_name"];

		if ($_FILES["file"]["type"] == "application/zip") {
			if(move_uploaded_file($_FILES["file"]["tmp_name"], $projectFolder."/uploaded.zip")) {
				$zip = new ZipArchive();
				$x = $zip->open($projectFolder."/uploaded.zip");
				if ($x === true) {
					$zip->extractTo($projectFolder."/src"); // change this to the correct site path
					$zip->close();
				}

				// Mac app
				mkdir($projectFolder."/mac/Application.app");
				mkdir($projectFolder."/mac/Application.app/Contents");
				mkdir($projectFolder."/mac/Application.app/Contents/MacOS");


				recurse_copy($projectFolder."/src", $projectFolder."/mac/Application.app/Contents/MacOS");

				//Copyright Conner V




				// Keep uploaded file just because
				//unlink($_FILES["file"]["tmp_name"]);

				$message = "Your .zip file was uploaded and unpacked.";
				echo("Uploaded Successfully!");
			}
		}

		else {
			if(move_uploaded_file($_FILES["file"]["tmp_name"], $projectFolder."/src/".
				preg_replace('/[^A-Za-z0-9_.\-]/', '_', $_FILES["file"]["name"])
				)) {
				echo("Uploaded Successfully!");
			}
		}



	}
}



