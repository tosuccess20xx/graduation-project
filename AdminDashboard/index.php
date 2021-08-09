<?php
	session_start();
	$noNavbar = '';
	$pageTitle = 'Login';

	if (isset($_SESSION['Username'])) {
		header('Location: dashboard.php'); // Redirect To Dashboard Page
	}

	include 'initialization.php';

	// Check If User Coming From HTTP Post Request

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hashedPass = md5($password);

		// Check If The User Exist In Database

		$stmt = $con->prepare("SELECT 
									UserID, Username, Password 
								FROM 
									users 
								WHERE 
									Username = ? 
								AND 
									Password = ? 
								AND 
									GroupID = 1
								LIMIT 1");

		$stmt->execute(array($username, $hashedPass));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();

		// If Count > 0 This Mean The Database Contain Record About This Username

		if ($count > 0) {
			$_SESSION['Username'] = $username; // Register Session Name
			$_SESSION['ID'] = $row['UserID']; // Register Session ID
			$_SESSION['Image'] = $row['Image']; // Register Session Image
			header('Location: dashboard.php'); // Redirect To Dashboard Page
			exit();
		}

	}

?>


		<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<i class="fab fa-cpanel fa-fw fa-lg"></i>
			<h4 class="text-center">لوحة التحكم</h4>
			<input class="form-control" type="text" name="user" placeholder="اسم المستخدم" autocomplete="off" />
			<input class="form-control" type="password" name="pass" placeholder="كلمة المرور" autocomplete="new-password" />
			<input class="btn btn-primary btn-block" type="submit" value="تسجيل الدخول" />
		</form>

<?php include $tpl . 'footer.php'; ?>