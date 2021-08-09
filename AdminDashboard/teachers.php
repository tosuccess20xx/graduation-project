<?php

	/*
	================================================
	== Manage Teachers Page
	== You Can Add | Edit | Delete Teachers From Here
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Teachers';

	if (isset($_SESSION['Username'])) {

		include 'initialization.php';

		$action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

		// Start Manage Page

		if ($action == 'Manage') { // Manage Teachers Page

			$query = '';

			if (isset($_GET['page']) && $_GET['page'] == 'Pending') {

				$query = 'AND RegStatus = 0';

			}

			// Select All Teachers

			$stmt = $con->prepare("SELECT 
										users.*, 
										users_groups.GroupName 
									FROM 
										users 
									INNER JOIN 
										users_groups 
									ON 
										users_groups.GroupID = users.GroupID 
									WHERE 
										users.GroupID != 3 $query 
									ORDER BY 
										UserID DESC");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$rows = $stmt->fetchAll();

			if (! empty($rows)) {

			?>

			<h1 class="text-center"><i class="fas fa-chalkboard-teacher fa-fw fa-lg"></i> إدارة المدربين</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table manage-members text-center table table-bordered">
						<tr>
							<td>ID</td>
							<td>الصورة</td>
							<td>أسم المستخدم</td>
							<td>البريد الإلكترونى</td>
							<td>الاسم الكامل</td>
							<td>العضوية</td>
							<td>تاريخ التسجيل</td>
							<td>التحكم</td>
						</tr>
						<?php
							foreach($rows as $row) {
								echo "<tr>";
									echo "<td>" . $row['UserID'] . "</td>";
									echo "<td>";
									if (empty($row['Image'])) {
										echo 'لا توجد صورة';
									} else {
										echo "<img src='uploads/avatars/" . $row['Image'] . "' alt='' />";
									}
									echo "</td>";

									echo "<td>" . $row['Username'] . "</td>";
									echo "<td>" . $row['Email'] . "</td>";
									echo "<td>" . $row['FullName'] . "</td>";
									echo "<td>" . $row['GroupName'] . "</td>";
									echo "<td>" . $row['RegDate'] ."</td>";
									echo "<td>
										<a href='teachers.php?action=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> تحديث</a>
										<a href='teachers.php?action=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fas fa-user-minus'></i> حذف</a>";
										if ($row['RegStatus'] == 0) {
											echo "<a 
													href='teachers.php?action=Activate&userid=" . $row['UserID'] . "' 
													class='btn btn-info activate confirm'>
													<i class='fa fa-user-check'></i> تفعيل</a>";
										} else {
											echo "<a 
													href='teachers.php?action=UnActivate&userid=" . $row['UserID'] . "' 
													class='btn btn-danger activate confirm'>
													<i class='fas fa-user-times'></i> إلغاء التفعيل</a>";
										}
									echo "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
				<?php if (! isset($_GET['page'])) { ?>
				<a href="teachers.php?action=Add" class="btn btn-primary btn-lg">
					<i class="fas fa-user-plus fa-fw fa-lg"></i> إضافة مدرب جديد
				</a>
				<?php } ?>
			</div>

			<?php } else {

				echo '<div class="container">';
                if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
					echo '<div class="nice-message">لا يوجد مدربين غير مفعلين لعرض بياناتهم</div>';
                } else {
                    echo '<div class="nice-message">لا يوجد مدربين لعرض بياناتهم</div>';
					echo '<a href="teachers.php?action=Add" class="btn btn-primary btn-lg">
							<i class="fas fa-user-plus fa-fw fa-lg"></i> إضافة مدرب جديد
						</a>';
                }
				echo '</div>';

			} ?>

		<?php 

		} elseif ($action == 'Add') { // Add Page ?>

			<h1 class="text-center"><i class="fas fa-chalkboard-teacher fa-fw fa-lg"></i> إضافة مدرب جديد</h1>
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading">إضافة مدرب جديد</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-8">
								<form class="form-horizontal" action="?action=Insert" method="POST" enctype="multipart/form-data">
									<!-- Start Username Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">اسم المستخدم</label>
										<div class="col-sm-10 col-md-9">
											<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder="اسم المستخدم لتسجيل الدخول للموقع" />
										</div>
									</div>
									<!-- End Username Field -->
									<!-- Start Password Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">كلمة المرور</label>
										<div class="col-sm-10 col-md-9">
											<input type="password" name="password" class="password form-control" required="required" autocomplete="new-password" placeholder="يجب أن تكون كلمة المرور قوية ومعقدة" />
											<i class="show-pass fa fa-eye fa-2x"></i>
										</div>
									</div>
									<!-- End Password Field -->
									<!-- Start Email Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">البريد الإلكترونى</label>
										<div class="col-sm-10 col-md-9">
											<input type="email" name="email" class="form-control" required="required" placeholder="البريد الإلكترونى يجب أن يكون صالحاً" />
										</div>
									</div>
									<!-- End Email Field -->
									<!-- Start Full Name Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">الاسم الكامل</label>
										<div class="col-sm-10 col-md-9">
											<input type="text" name="full" data-class=".live-fullname" class="form-control live" required="required" placeholder="الاسم الكامل يظهر فى صفحة ملفك الشخصى" />
										</div>
									</div>
									<!-- End Full Name Field -->
									<!-- Start Avatar Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">الصورة</label>
										<div class="col-sm-10 col-md-9">
											<input type="file" name="avatar" id="image-upload" class="form-control" required="required" />
										</div>
									</div>
									<!-- End Avatar Field -->
									<!-- Start Sections Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">القسم</label>
										<div class="col-sm-10 col-md-9">
											<select name="section">
												<option value="0">...</option>
												<?php
													$stmt = $con->prepare("SELECT * FROM sections");
													$stmt->execute();
													$sections = $stmt->fetchAll();
													foreach ($sections as $section) {
														echo "<option value='" . $section['ID'] . "'>" . $section['Name'] . "</option>";
													}
												?>
											</select>
										</div>
									</div>
									<!-- End Sections Field -->
									<!-- Start Band Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">الفرقة</label>
										<div class="col-sm-10 col-md-9">
											<select name="band">
												<option value="0">...</option>
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
									</div>
									<!-- End Band Field -->
									<!-- Start Group Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">العضوية</label>
										<div class="col-sm-10 col-md-9">
											<select name="group">
												<option value="0">...</option>
												<?php
													$stmt = $con->prepare("SELECT * FROM users_groups WHERE GroupID != 3 ORDER BY GroupID ASC");
													$stmt->execute();
													$groups = $stmt->fetchAll();
													foreach ($groups as $group) {
														echo "<option value='" . $group['GroupID'] . "'>" . ucfirst($group['GroupName']) . "</option>";
													}
												?>
											</select>
										</div>
									</div>
									<!-- End Group Field -->
									<!-- Start Submit Field -->
									<div class="form-group form-group-lg">
										<div class="col-sm-offset-3 col-md-9">
											<input type="submit" value="إضافة مدرب" class="btn btn-primary btn-lg form-control" />
										</div>
									</div>
									<!-- End Submit Field -->
								</form>
							</div>
							<div class="col-md-4">
								<div class="thumbnail item-box live-preview">
									<img id="image-preview" class="img-responsive" src="<?php echo $images; ?>img.png" alt="" />
									<div class="caption text-center">
										<h3 class="live-fullname">الاسم الكامل</h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php 

		} elseif ($action == 'Insert') {

			// Insert Member Page

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'><i class='fas fa-chalkboard-teacher fa-fw fa-lg'></i> إضافة مدرب</h1>";
				echo "<div class='container'>";

				// Upload Variables

				$avatarName = $_FILES['avatar']['name'];
				$avatarSize = $_FILES['avatar']['size'];
				$avatarTmp	= $_FILES['avatar']['tmp_name'];
				$avatarType = $_FILES['avatar']['type'];

				// List Of Allowed File Typed To Upload

				$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

				// Get Avatar Extension

				$tmp = explode('.', $avatarName);

				$avatarExtension = strtolower(end($tmp));

				// Get Variables From The Form

				$user 		= $_POST['username'];
				$pass 		= $_POST['password'];
				$email 		= $_POST['email'];
				$name 		= $_POST['full'];
				$group 		= $_POST['group'];
				$section 	= $_POST['section'];
				$band 		= $_POST['band'];

				$hashPass = md5($_POST['password']);

				// Validate The Form

				$formErrors = array();

				if (strlen($user) < 4) {
					$formErrors[] = 'اسم المستخدم يجب ألا يكون <strong>أقل من 4 أحرف</strong>';
				}

				if (strlen($user) > 20) {
					$formErrors[] = 'اسم المستخدم يجب ألا يكون <strong>أكبر من 20 حرف</strong>';
				}

				if (empty($user)) {
					$formErrors[] = 'اسم المستخدم يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (empty($pass)) {
					$formErrors[] = 'كلمة المرور يجب ألا تكون <strong>فارغة</strong>';
				}

				if (empty($name)) {
					$formErrors[] = 'الاسم الكامل يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (empty($email)) {
					$formErrors[] = 'البريد الإلكترونى يجب ألا يكون <strong>فارغاً</strong>';
				}

				if ($group == 0) {
					$formErrors[] = 'يجب عليك أن تختار <strong>العضوية</strong>';
				}

				if ($section == 0) {
					$formErrors[] = 'يجب عليك أن تختار <strong>القسم</strong>';
				}

				if ($band == 0) {
					$formErrors[] = 'يجب عليك أن تختار <strong>الفرقة</strong>';
				}

				if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
					$formErrors[] = 'إمتداد الصورة هذا <strong>غير مسموح به</strong>';
				}

				if (empty($avatarName)) {
					$formErrors[] = 'الصورة <strong>مطلوبة</strong>';
				}

				if ($avatarSize > 4194304) {
					$formErrors[] = 'يجب ألا يكون حجم الصورة <strong>أكبر من 4 ميغابايت</strong>';
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger text-center">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Insert Operation

				if (empty($formErrors)) {

					$avatar = rand(0, 1000000) . '_' . $avatarName;

					move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);

					// Check If User Exist in Database

					$check = checkItem("Username", "users", $user);

					if ($check == 1) {

						$theMsg = '<div class="alert alert-danger text-center">نآسف هذا المستخدم موجود</div>';

						redirectHome($theMsg, 'back');

					} else {

						// Insert Userinfo In Database

						$stmt = $con->prepare("INSERT INTO 
													users(Username, Password, Email, FullName, GroupID, SectionID, BandID, Image, RegStatus, RegDate)
												VALUES(:zuser, :zpass, :zmail, :zname, :zgroup, :zsection, :zband, :zavatar, 1, now())");
						$stmt->execute(array(

							'zuser' 	=> $user,
							'zpass' 	=> $hashPass,
							'zmail' 	=> $email,
							'zname' 	=> $name,
							'zgroup' 	=> $group,
							'zsection' 	=> $section,
							'zband' 	=> $band,
							'zavatar'	=> $avatar

						));

						// Echo Success Message

						$theMsg = '<div class="alert alert-success text-center">تم إضافة المدرب بنجاح !</div>';

						redirectHome($theMsg, 'back');

					}

				}


			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger text-center">نآسف لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg);

				echo "</div>";

			}

			echo "</div>";

		} elseif ($action == 'Edit') {

			// Check If Get Request userid Is Numeric & Get Its Integer Value

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

			// Select All Data Depend On This ID

			$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");

			// Execute Query

			$stmt->execute(array($userid));

			// Fetch The Data

			$row = $stmt->fetch();

			// The Row Count

			$count = $stmt->rowCount();

			// If There's Such ID Show The Form

			if ($count > 0) { ?>

				<h1 class="text-center"><i class="fas fa-chalkboard-teacher fa-fw fa-lg"></i> تحديث المدرب</h1>
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading">تحديث المدرب</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-8">
									<form class="form-horizontal" action="?action=Update" method="POST">
										<input type="hidden" name="userid" value="<?php echo $userid ?>" />
										<!-- Start Username Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">اسم المستخدم</label>
											<div class="col-sm-10 col-md-9">
												<input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" />
											</div>
										</div>
										<!-- End Username Field -->
										<!-- Start Password Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">كلمة المرور</label>
											<div class="col-sm-10 col-md-9">
												<input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
												<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="اترك الحقل فارغاً إذا كنت لا تريد تغييره" />
											</div>
										</div>
										<!-- End Password Field -->
										<!-- Start Email Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">البريد الإلكترونى</label>
											<div class="col-sm-10 col-md-9">
												<input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
											</div>
										</div>
										<!-- End Email Field -->
										<!-- Start Full Name Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">الاسم الكامل</label>
											<div class="col-sm-10 col-md-9">
												<input type="text" name="full" value="<?php echo $row['FullName'] ?>" data-class=".live-fullname" class="form-control live" required="required" />
											</div>
										</div>
										<!-- End Full Name Field -->
										<!-- Start Avatar Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">الصورة</label>
											<div class="col-sm-10 col-md-9">
												<input type="hidden" name="oldavatar" value="<?php echo $row['Image'] ?>" />
												<input type="file" name="newavatar" id="image-upload" class="form-control" />
											</div>
										</div>
										<!-- End Avatar Field -->
										<!-- Start Sections Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">القسم</label>
											<div class="col-sm-10 col-md-9">
												<select name="section">
													<?php
														$stmt = $con->prepare("SELECT * FROM sections");
														$stmt->execute();
														$sections = $stmt->fetchAll();
														foreach ($sections as $section) {
															echo "<option value='" . $section['ID'] . "'"; 
															if ($row['SectionID'] == $section['ID']) { echo 'selected'; } 
															echo ">" . $section['Name'] . "</option>";
														}
													?>
												</select>
											</div>
										</div>
										<!-- End Sections Field -->
										<!-- Start Band Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">الفرقة</label>
											<div class="col-sm-10 col-md-9">
												<select name="band">
													<?php
														$stmt = $con->prepare("SELECT * FROM band ORDER BY Band_ID ASC");
														$stmt->execute();
														$bands = $stmt->fetchAll();
														foreach ($bands as $band) {
															echo "<option value='" . $band['Band_ID'] . "'"; 
															if ($row['BandID'] == $band['Band_ID']) { echo 'selected'; } 
															echo ">" . $band['Band_Name'] . "</option>";
														}
													?>
												</select>
											</div>
										</div>
										<!-- End Band Field -->
										<!-- Start Group Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">العضوية</label>
											<div class="col-sm-10 col-md-9">
												<select name="group">
													<?php
														$stmt = $con->prepare("SELECT * FROM users_groups WHERE GroupID != 3 ORDER BY GroupID ASC");
														$stmt->execute();
														$groups = $stmt->fetchAll();
														foreach ($groups as $group) {
															echo "<option value='" . $group['GroupID'] . "'"; 
															if ($row['GroupID'] == $group['GroupID']) { echo 'selected'; } 
															echo ">" . $group['GroupName'] . "</option>";
														}
													?>
												</select>
											</div>
										</div>
										<!-- End Group Field -->
										<!-- Start Submit Field -->
										<div class="form-group form-group-lg">
											<div class="col-sm-offset-3 col-md-9">
												<input type="submit" value="تحديث بيانات المدرب" class="btn btn-primary btn-lg form-control" />
											</div>
										</div>
										<!-- End Submit Field -->
									</form>
								</div>
								<div class="col-md-4">
									<div class="thumbnail item-box live-preview">
										<img id="image-preview" class="img-responsive" src="<?php if (! empty($row['Image'])) {echo 'uploads\avatars\\' . $row['Image'];} else {echo $images . 'img.png';} ?>" alt="" />
										<div class="caption text-center">
											<h3 class="live-fullname"><?php echo $row['FullName'] ?></h3>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			<?php

			// If There's No Such ID Show Error Message

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger text-center">هذا المُعرف غير موجود</div>';

				redirectHome($theMsg);

				echo "</div>";

			}

		} elseif ($action == 'Update') { // Update Page

			echo "<h1 class='text-center'><i class='fas fa-chalkboard-teacher fa-fw fa-lg'></i> تحديث المدرب</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				if (! empty($_FILES['newavatar']['name'])) {

					// Upload Variables

					$avatarName = $_FILES['newavatar']['name'];
					$avatarSize = $_FILES['newavatar']['size'];
					$avatarTmp	= $_FILES['newavatar']['tmp_name'];
					$avatarType = $_FILES['newavatar']['type'];

					// List Of Allowed File Typed To Upload

					$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

					// Get Avatar Extension

					$tmp = explode('.', $avatarName);

					$avatarExtension = strtolower(end($tmp));

				} else {

					// Get Variable From The Form

					$avatar = $_POST['oldavatar'];
				}

				// Get Variables From The Form

				$id 		= $_POST['userid'];
				$user 		= $_POST['username'];
				$email 		= $_POST['email'];
				$name 		= $_POST['full'];
				$group 		= $_POST['group'];
				$section 	= $_POST['section'];
				$band 		= $_POST['band'];

				// Password Trick

				$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : md5($_POST['newpassword']);

				// Validate The Form

				$formErrors = array();

				if (strlen($user) < 4) {
					$formErrors[] = 'اسم المستخدم يجب ألا يكون <strong>أقل من 4 أحرف</strong>';
				}

				if (strlen($user) > 20) {
					$formErrors[] = 'اسم المستخدم يجب ألا يكون <strong>أكبر من 20 حرف</strong>';
				}

				if (empty($user)) {
					$formErrors[] = 'اسم المستخدم يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (empty($name)) {
					$formErrors[] = 'الاسم الكامل يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (empty($email)) {
					$formErrors[] = 'البريد الإلكترونى يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (! empty($_FILES['newavatar']['name'])) {

					if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
						$formErrors[] = 'إمتداد الصورة هذا <strong>غير مسموح به</strong>';
					}

					if ($avatarSize > 4194304) {
						$formErrors[] = 'يجب ألا يكون حجم الصورة <strong>أكبر من 4 ميغابايت</strong>';
					}
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger text-center">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					if (! empty($_FILES['newavatar']['name'])) {

						$avatar = rand(0, 1000000) . '_' . $avatarName;

						move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar);

					}

					$stmt2 = $con->prepare("SELECT 
												*
											FROM 
												users
											WHERE
												Username = ?
											AND 
												UserID != ?");

					$stmt2->execute(array($user, $id));

					$count = $stmt2->rowCount();

					if ($count == 1) {

						$theMsg = '<div class="alert alert-danger text-center">نآسف هذا المستخدم موجود</div>';

						redirectHome($theMsg, 'back');

					} else { 

						// Update The Database With This Info

						$stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ?, GroupID = ?, SectionID = ?, BandID = ?, Image = ? WHERE UserID = ?");

						$stmt->execute(array($user, $email, $name, $pass, $group, $section, $band, $avatar, $id));

						// Echo Success Message

						$theMsg = '<div class="alert alert-success text-center">تم تحديث بيانات المدرب بنجاح !</div>';

						redirectHome($theMsg, 'back');

					}

				}

			} else {

				$theMsg = '<div class="alert alert-danger text-center">نآسف لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($action == 'Delete') { // Delete Teacher Page

			echo "<h1 class='text-center'><i class='fas fa-chalkboard-teacher fa-fw fa-lg'></i> حذف المدرب</h1>";
			echo "<div class='container'>";

				// Check If Get Request userid Is Numeric & Get The Integer Value Of It

				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('userid', 'users', $userid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");

					$stmt->bindParam(":zuser", $userid);

					$stmt->execute();

					$theMsg = '<div class="alert alert-success text-center">تم حذف المدرب بنجاح !</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger text-center">هذا المُعرف غير موجود</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} elseif ($action == 'Activate') {

			echo "<h1 class='text-center'><i class='fas fa-chalkboard-teacher fa-fw fa-lg'></i> تفعيل المدرب</h1>";
			echo "<div class='container'>";

				// Check If Get Request userid Is Numeric & Get The Integer Value Of It

				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('userid', 'users', $userid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

					$stmt->execute(array($userid));

					$theMsg = '<div class="alert alert-success text-center">تم تفعيل المدرب بنجاح !</div>';

					redirectHome($theMsg);

				} else {

					$theMsg = '<div class="alert alert-danger text-center">هذا المُعرف غير موجود</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} elseif ($action == 'UnActivate') {

			echo "<h1 class='text-center'><i class='fas fa-chalkboard-teacher fa-fw fa-lg'></i> إلغاء تفعيل المدرب</h1>";
			echo "<div class='container'>";

				// Check If Get Request userid Is Numeric & Get The Integer Value Of It

				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('userid', 'users', $userid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE users SET RegStatus = 0 WHERE UserID = ?");

					$stmt->execute(array($userid));

					$theMsg = '<div class="alert alert-success text-center">تم إلغاء تفعيل المدرب بنجاح !</div>';

					redirectHome($theMsg);

				} else {

					$theMsg = '<div class="alert alert-danger text-center">هذا المُعرف غير موجود</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		}

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>