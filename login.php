<?php
	ob_start();
	session_start();
	$pageTitle = 'Login';
	if (isset($_SESSION['user'])) {
		header('Location: index.php');
	}
	include 'initialization.php';

	// Check If User Coming From HTTP Post Request

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if (isset($_POST['login'])) {

			$user = $_POST['username'];
			$pass = $_POST['password'];
			$hashedPass = md5($pass);

			// Check If The User Exist In Database

			$stmt = $con->prepare("SELECT 
										UserID, Username, Password, GroupID, Image, RegStatus 
									FROM 
										users 
									WHERE 
										Username = ? 
									AND 
										Password = ?");

			$stmt->execute(array($user, $hashedPass));

			$get = $stmt->fetch();

			$count = $stmt->rowCount();

			// If Count > 0 This Mean The Database Contain Record About This Username

			if ($count > 0) {

				$_SESSION['user'] = $user; // Register Session Name

				$_SESSION['uid'] = $get['UserID']; // Register User ID in Session

				$_SESSION['ugroup'] = $get['GroupID']; // Register Group ID in Session

				$_SESSION['ustatus'] = $get['RegStatus']; // Register User Status in Session

				$_SESSION['image'] = $get['Image']; // Register Image in Session

				header('Location: index.php'); // Redirect To Dashboard Page

				exit();

			} else {

				$formErrors[] = 'نآسف أسم المستخدم أو كلمة المرور غير صحيح';

			}

		} else {

			$formErrors = array();

			$username 	= $_POST['username'];
			$password 	= $_POST['password'];
			$password2 	= $_POST['password2'];
			$email 		= $_POST['email'];
			$type 		= $_POST['type'];
			$section 	= $_POST['section'];
			$band 		= $_POST['band'];

			if (isset($username)) {

				$filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

				if (strlen($filterdUser) < 4) {

					$formErrors[] = 'أسم المستخدم يجب أن يكون أكبر من 4 أحرف';

				}

			}

			if (isset($password) && isset($password2)) {

				if (empty($password)) {

					$formErrors[] = 'نآسف كلمة المرور يجب ألا تكون فارغة';

				}

				if (md5($password) !== md5($password2)) {

					$formErrors[] = 'نآسف كلمة المرور غير متطابقة';

				}

			}

			if (isset($email)) {

				$filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

				if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {

					$formErrors[] = 'البريد الإلكترونى غير صالح';

				}

			}
            
            if ($type == 0) {
                $formErrors[] = 'يجب أن تختار <strong>نوع العضوية</strong>';
            }

            if ($section == 0) {
                $formErrors[] = 'يجب أن تختار <strong>القسم</strong>';
            }

            if ($band == 0) {
                $formErrors[] = 'يجب أن تختار <strong>الفرقة</strong>';
            }

			// Check If There's No Error Proceed The User Add

			if (empty($formErrors)) {

				// Check If User Exist in Database

				$check = checkItem("Username", "users", $username);

				if ($check == 1) {

					$formErrors[] = 'أسم المستخدم هذا موجود بالفعل';

				} else {

					// Insert Userinfo In Database

					$stmt = $con->prepare("INSERT INTO 
											users(Username, Password, Email, GroupID, SectionID, BandID, RegStatus, RegDate)
										VALUES(:zuser, :zpass, :zmail, :ztype, :zsection, :zband, 0, now())");
					$stmt->execute(array(

						'zuser'    => $username,
						'zpass'    => md5($password),
						'zmail'    => $email,
						'ztype'    => $type,
						'zsection' => $section,
						'zband'    => $band

					));

					// Echo Success Message

					$succesMsg = 'تهانينا ! تم تسجيل عضويتك , أدخل اسم المستخدم وكلمة المرور .';

				}

			}

		}

	}

?>

<div class="container login-page">
	<i class="fal fa-user-circle fa-fw fa-lg"></i>
	<h1 class="text-center">
		<span class="selected" data-class="login">تسجيل الدخول</span> | 
		<span data-class="signup">تسجيل</span>
	</h1>
	<!-- Start Login Form -->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-container">
			<input 
				class="form-control" 
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="اسم المستخدم" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="كلمة المرور" 
				required />
		</div>
		<input class="btn btn-primary btn-block" name="login" type="submit" value="تسجيل الدخول" />
	</form>
	<!-- End Login Form -->
	<!-- Start Signup Form -->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-container">
			<input 
				pattern=".{4,}"
				title="اسم المستخدم يجب أن يكون أكثر من 4 أحرف"
				class="form-control" 
				type="text" 
				name="username" 
				autocomplete="off"
				placeholder="اسم المستخدم" 
				required />
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="password form-control" 
				type="password" 
				name="password" 
				autocomplete="new-password"
				placeholder="كلمة مرور قوية" 
				required />
            <i class="show-pass fa fa-eye fa-2x"></i>
		</div>
		<div class="input-container">
			<input 
				minlength="4"
				class="password form-control" 
				type="password" 
				name="password2" 
				autocomplete="new-password"
				placeholder="كلمة المرور مرة أخرى" 
				required />
		</div>
		<div class="input-container">
			<input 
				class="form-control" 
				type="email" 
				name="email" 
				autocomplete="off" 
				placeholder="بريدًا إلكترونيًا صالحًا" />
		</div>
		<div class="input-container">
			<select class="form-control" name="type">
				<option value="0">أختر نوع العضوية ...</option>
				<?php
					$stmt = $con->prepare("SELECT * FROM users_groups WHERE GroupID != 1 ORDER BY GroupID ASC");
					$stmt->execute();
					$groups = $stmt->fetchAll();
					foreach ($groups as $group) {
						echo "<option value='" . $group['GroupID'] . "'>" . ucfirst($group['GroupName']) . "</option>";
					}
				?>
			</select>
		</div>
		<div class="input-container">
			<select class="form-control" name="section">
				<option value="0">أختر القسم ...</option>
				<?php
					$stmt = $con->prepare("SELECT * FROM sections WHERE Visibility = 1");
					$stmt->execute();
					$sections = $stmt->fetchAll();
					foreach ($sections as $section) {
						echo "<option value='" . $section['ID'] . "'>" . $section['Name'] . "</option>";
					}
				?>
			</select>
		</div>
		<div class="input-container">
			<select class="form-control" name="band">
				<option value="0">أختر الفرقة ...</option>
				<?php
					$stmt = $con->prepare("SELECT * FROM band ORDER BY Band_ID ASC");
					$stmt->execute();
					$bands = $stmt->fetchAll();
					foreach ($bands as $band) {
						echo "<option value='" . $band['Band_ID'] . "'>" . $band['Band_Name'] . "</option>";
					}
				?>
			</select>
		</div>
		<input class="btn btn-success btn-block" name="signup" type="submit" value="تسجيل" />
	</form>
	<!-- End Signup Form -->
	<div class="the-errors text-center">
		<?php 

			if (!empty($formErrors)) {

				foreach ($formErrors as $error) {

					echo '<div class="msg error">' . $error . '</div>';

				}

			}

			if (isset($succesMsg)) {

				echo '<div class="msg success">' . $succesMsg . '</div>';

			}

		?>
	</div>
</div>

<?php 
	include $tpl . 'footer.php';
	ob_end_flush();
?>