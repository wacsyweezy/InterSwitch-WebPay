<?php
	@session_start();
	if(!isset($_SESSION['auth'])) {
		include('class.php');
		App::propmtLogin();
	}
	if(isset($_SESSION['auth'])) {
		if(!isset($_GET['page'])) {
			include('class.php');
			App::dashboard();
		}
		elseif(isset($_GET['page'])) {
			include('class.php');
			if($_GET['page']=="aTransaction") {
				App::all();
			}
			if($_GET['page']=="sTransaction") {
				App::successful();
			}
			if($_GET['page']=="uTransaction") {
				App::unsuccessful();
			}
			if($_GET['page']=="pTransaction") {
				App::pending();
			}
			if($_GET['page']=="logout") {
				unset($_SESSION['auth']);
				print '<script>document.location.href="./";</script>';
			}
		}
	}
?>
