<?php
	ob_start();
	session_start();
	$pageTitle = 'Profile';
	include 'initialization.php';
	$action = isset($_GET['action']) ? $_GET['action'] : 'Manage';
	if (isset($_SESSION['user'])) {

		if ($action == 'Manage') {

			$getUser = $con->prepare("SELECT 
											users.*, 
											users_groups.GroupName, 
											sections.Name AS section_name, 
											band.Band_Name 
										FROM 
											users 
										INNER JOIN 
											users_groups 
										ON 
											users_groups.GroupID = users.GroupID 
										INNER JOIN 
											sections 
										ON 
											sections.ID = users.SectionID 
										INNER JOIN 
											band 
										ON 
											band.Band_ID = users.BandID 
										WHERE Username = ?");

			$getUser->execute(array($sessionUser));

			$info = $getUser->fetch();

			$userid = $info['UserID'];

?>
			<h1 class="info text-center"><i class="far fa-id-card fa-fw fa-lg"></i> الملف الشخصى</h1>
			<div class="information block">
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading"><i class="fas fa-users-cog fa-fw fa-lg"></i> بيانات الملف الشخصى</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-2 text-center">
									<?php if (! empty($info['Image'])) { ?>
										<img class="img-responsive img-thumbnail" src="AdminDashboard\uploads\avatars\<?php echo $info['Image']; ?>" alt="" />
									<?php } else { ?>
										<img class="img-responsive img-thumbnail" src="<?php echo $images; ?>avatar.png" alt="" />
									<?php } ?>
								</div>
								<div class="col-sm-10">
									<ul class="list-unstyled">
										<li>
											<i class="fas fa-unlock-alt fa-fw fa-lg"></i>
											<span>اسم المستخدم</span><span> : <?php echo $info['Username'] ?></span>
										</li>
										<li>
											<i class="fas fa-envelope fa-fw fa-lg"></i>
											<span>البريد الإلكترونى</span><span> : <?php echo $info['Email'] ?></span>
										</li>
										<li>
											<i class="fas fa-user fa-fw fa-lg"></i>
											<span>الاسم الكامل</span><span> : <?php echo $info['FullName'] ?></span>
										</li>
										<li>
											<i class="fas fa-users-class fa-fw fa-lg"></i>
											<span>القسم</span><span> : <?php echo $info['section_name'] ?></span>
										</li>
										<li>
											<i class="fas fa-tags fa-fw fa-lg"></i>
											<span>الفرقة</span><span> : <?php echo $info['Band_Name'] ?></span>
										</li>
										<li>
											<i class="fas fa-user-tag fa-fw fa-lg"></i>
											<span>نوع العضوية</span><span> : <?php echo $info['GroupName'] ?></span>
										</li>
										<li>
											<i class="fas fa-calendar fa-fw fa-lg"></i>
											<span>تاريخ التسجيل</span><span> : <?php echo $info['RegDate'] ?></span>
										</li>
									</ul>
									<a href="profile.php?action=EditUser&userid=<?php echo $userid; ?>" class="btn btn-default"><i class="fas fa-edit fa-fw fa-lg"></i> تحديث البيانات</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php if ($_SESSION['ugroup'] != 3) { ?>
			<div id="my-courses" class="my-ads my-courses block price_table text-center">
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading text-right"><i class="far fa-suitcase fa-fw fa-lg"></i> دوراتى</div>
						<div class="panel-body">
							<div class="row">
								<?php
			                        $stmt = $con->prepare("SELECT 
			                                                    courses.*, 
			                                                    sections.ID AS section_id, 
			                                                    sections.Name AS section_name, 
			                                                    band.Band_Name, 
			                                                    users.UserID, 
			                                                    users.FullName 
			                                                FROM 
			                                                    courses 
			                                                INNER JOIN 
			                                                    sections 
			                                                ON 
			                                                    sections.ID = courses.Section_ID 
			                                                INNER JOIN 
			                                                    band 
			                                                ON 
			                                                    band.Band_ID = courses.Band_ID 
			                                                INNER JOIN 
			                                                    users 
			                                                ON 
			                                                    users.UserID = courses.Teacher_ID 
			                                                WHERE 
			                                                    Teacher_ID = ? 
			                                                ORDER BY 
			                                                    Course_ID DESC");

			                        // Execute Query
			                        $stmt->execute(array($userid));

			                        $myCourses = $stmt->fetchAll();

			                        if (! empty($myCourses)) {

			                        	foreach ($myCourses as $Course) {
			                    ?>

						                    <div class="col-lg-3 col-sm-6 col-xs-12">
						                        <div class="price_box course-box wow fadeInUp" data-wow-duration="2s" data-wow-offset="400">
						                            <h4 class="text-primary"><?php echo '<a href="sections.php?pageid=' . $Course['section_id'] . '&pagename=' . str_replace(' ', '-', $Course['section_name']) . '">' . $Course['section_name'] . '</a>'; ?></h4>
						                            <?php
						                            	if ($Course['Approve'] == 0) {
						                            		echo '<span class="approve-status">فى إنتظار الموافقة</span>';
						                            	}
						                            ?>
						                            <img class="img-responsive img-thumbnail center-block" src="AdminDashboard/uploads/CoursesImages/<?php echo $Course['Image']; ?>" alt="<?php echo $Course['Image']; ?>" />
						                            <ul class="list-unstyled">
						                                <li><?php echo $Course['Title']; ?></li>
						                                <li>
						                                    <i class="far fa-tags fa-fw"></i>
						                                    <span><?php echo $Course['Band_Name']; ?></span>
						                                </li>
						                                <li>
						                                    <i class="fas fa-chalkboard-teacher fa-fw"></i>
						                                    <span><a href=""><?php echo $Course['FullName']; ?></a></span>
						                                </li>
						                                <li>
						                                    <i class="far fa-calendar-alt fa-fw"></i>
						                                    <span><?php echo $Course['Create_Date']; ?></span>
						                                </li>
						                            </ul>
						                            <?php if ($_SESSION['ustatus'] == 1) { ?>
						                            <div class="course-overlay">
							                        	<a href="?action=EditCourse&courseid=<?php echo $Course['Course_ID']; ?>" class="btn btn-success">تحديث</a>
							                        	<a href="?action=DeleteCourse&courseid=<?php echo $Course['Course_ID']; ?>" class="btn btn-danger confirm">حذف</a>
							                        </div>
							                        <?php } ?>
						                        </div>
						                    </div>
								<?php
										}

			                      	} else {
										echo 'نآسف لا يوجد دورات لعرضها . <a href="newcourse.php">إضافة دورة جديدة</a>';
									}

								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			<div id="my-courses" class="my-ads my-courses block price_table text-center">
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading text-right"><i class="far fa-suitcase fa-fw fa-lg"></i> الإشتراكات</div>
						<div class="panel-body">
						<?php

							$stmt = $con->prepare("SELECT * FROM users_courses WHERE User_ID = ? ORDER BY Subscribe_ID DESC");

							// Execute Query
							$stmt->execute(array($userid));

							// Fetch The Data
							$myCourses = $stmt->fetchAll();

							if (! empty($myCourses)) {
								echo '<div class="row">';
								foreach ($myCourses as $Course) { ?>
									
									<?php
				                        $stmt = $con->prepare("SELECT 
				                                                    courses.*, 
				                                                    sections.ID AS section_id, 
				                                                    sections.Name AS section_name, 
				                                                    band.Band_Name, 
				                                                    users.FullName 
				                                                FROM 
				                                                    courses 
				                                                INNER JOIN 
				                                                    sections 
				                                                ON 
				                                                    sections.ID = courses.Section_ID 
				                                                INNER JOIN 
				                                                    band 
				                                                ON 
				                                                    band.Band_ID = courses.Band_ID 
				                                                INNER JOIN 
				                                                    users 
				                                                ON 
				                                                    users.UserID = courses.Teacher_ID 
				                                                WHERE 
				                                                    Course_ID = ? 
				                                                ORDER BY 
				                                                    Course_ID DESC");

				                        // Execute Query
				                        $stmt->execute(array($Course['Course_ID']));

				                        $courses = $stmt->fetchAll();

				                        foreach ($courses as $course) {
				                    ?>

				                    <div class="col-lg-3 col-sm-6 col-xs-12">
				                        <div class="price_box course-box wow fadeInUp" data-wow-duration="2s" data-wow-offset="400">
				                            <h4 class="text-primary"><?php echo '<a href="sections.php?pageid=' . $course['section_id'] . '&pagename=' . str_replace(' ', '-', $course['section_name']) . '">' . $course['section_name'] . '</a>'; ?></h4>
				                            <img class="img-responsive img-thumbnail center-block" src="AdminDashboard/uploads/CoursesImages/<?php echo $course['Image']; ?>" alt="<?php echo $course['Image']; ?>" />
				                            <ul class="list-unstyled">
				                                <li><?php echo $course['Title']; ?></li>
				                                <li>
				                                    <i class="far fa-tags fa-fw"></i>
				                                    <span><?php echo $course['Band_Name']; ?></span>
				                                </li>
				                                <li>
				                                    <i class="fas fa-chalkboard-teacher fa-fw"></i>
				                                    <span><a href=""><?php echo $course['FullName']; ?></a></span>
				                                </li>
				                                <li>
				                                    <i class="far fa-calendar-alt fa-fw"></i>
				                                    <span><?php echo $course['Create_Date']; ?></span>
				                                </li>
				                            </ul>
				                            <div class="course-overlay">
				                            	<a href="courses.php?courseid=<?php echo $course['Course_ID']; ?>" class="btn btn-success custom">عرض الدورة</a>
				                            	<?php if ($_SESSION['ustatus'] == 1) { ?>
				                            	<form action="<?php echo 'courses.php?courseid=' . $Course['Course_ID'] ?>" method="POST">
													<input type="submit" name="unsubscribe" class="btn btn-danger confirm" value="إلغاء الإشتراك">
												</form>
												<?php } ?>
				                            </div>
				                        </div>
				                    </div>

				                    <?php } ?>
						  <?php }
								echo '</div>';
							} else {
								echo 'لا يوجد دورات مشترك بها .';
							}
						?>
						</div>
					</div>
				</div>
			</div>
			<div class="my-comments block text-center">
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading text-right"><i class="far fa-comments fa-fw fa-lg"></i> تعليقاتى</div>
						<div class="panel-body">
						<?php

							$stmt = $con->prepare("SELECT DISTINCT 
																comments.Video_ID, 
																courses_videos.* 
															FROM 
																comments 
															INNER JOIN 
																courses_videos 
															ON 
																courses_videos.Video_ID = comments.Video_ID 
															WHERE 
																User_ID = ?");

							// Execute Query
							$stmt->execute(array($userid));

							// Fetch The Data
							$myVideos = $stmt->fetchAll();

							if (! empty($myVideos)) {

								foreach ($myVideos as $Video) {
						?>
									<div class="row">
										<div class="col-sm-4">
											<div class="comments-overlay">
												<img class="img-responsive img-thumbnail center-block" src="AdminDashboard/uploads/CoursesVideos/<?php echo $Video['Image']; ?>" alt="<?php echo $Video['Image']; ?>" />
												<div><a href="<?php echo 'courses.php?courseid=' . $Video['Course_ID'] . '&videoid=' . $Video['Video_ID'] . '&videosource=' . $Video['Video_Source'] ?>"><i class="fas fa-play fa-lg"></i><span> تشغيل الفيديو</span></a></div>
											</div>
											<h4 class="text-center"><a href="<?php echo 'courses.php?courseid=' . $Video['Course_ID'] . '&videoid=' . $Video['Video_ID'] . '&videosource=' . $Video['Video_Source'] ?>"><?php echo ucwords($Video['Title']) ?></a></h4>
										</div>

							  	<?php

							  		$stmt = $con->prepare("SELECT 
																comments.*, 
																users.FullName, 
																users.Image 
															FROM 
																comments 
															INNER JOIN 
																users 
															ON 
																users.UserID = comments.User_ID 
															WHERE 
																Video_ID = ? 
															AND 
																User_ID = ? 
															ORDER BY 
																Comment_Date DESC");

									// Execute Query
									$stmt->execute(array($Video['Video_ID'], $userid));

									// Fetch The Data
									$myComments = $stmt->fetchAll();

								?>
										<div class="col-sm-8">

								  <?php foreach ($myComments as $comment) { ?>
											
											<div class="comment-box">
												<div class="row">
													<div class="col-xs-2 text-center">
														<?php if (! empty($comment['Image'])) { ?>
															<img class="img-responsive img-thumbnail img-circle center-block" src="AdminDashboard\uploads\avatars\<?php echo $comment['Image']; ?>" alt="" />
														<?php } else { ?>
															<img class="img-responsive img-thumbnail img-circle center-block" src="<?php echo $images; ?>avatar.png" alt="" />
														<?php } ?>
													</div>
													<div class="col-xs-10 text-right">
														<div class="comment-info">
															<span><?php echo ucwords($comment['FullName']) ?></span>
															<p><?php echo $comment['Comment_Text'] ?></p>
															<span><?php echo $comment['Comment_Date'] ?></span>
														</div>
													</div>
												</div>
											</div>
								  <?php } ?>

										</div>
									</div>
									<hr class="custom-hr">
						  <?php } ?>

					  <?php } else {
								echo 'لا يوجد تعليقات لعرضها .';
							} ?>

						</div>
					</div>
				</div>
			</div>

  <?php } elseif ($action == 'EditUser') {

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

				<h1 class="info text-center"><i class="fas fa-user-edit fa-fw fa-lg"></i> تحديث البيانات</h1>
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading">تحديث البيانات</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-8">
									<form class="form-horizontal main-form" action="?action=UpdateUser" method="POST" enctype="multipart/form-data">
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
												<input type="password" name="newpassword" class="password form-control" autocomplete="new-password" placeholder="اترك الحقل فارغاً إذا كنت لا تريد تغييره" />
												<i class="show-pass fa fa-eye fa-2x"></i>
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
														$stmt = $con->prepare("SELECT * FROM sections WHERE Visibility = 1");
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
														$stmt = $con->prepare("SELECT * FROM users_groups WHERE GroupID != 1 ORDER BY GroupID ASC");
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
												<input type="submit" value="تحديث البيانات" class="btn btn-primary btn-lg form-control" />
											</div>
										</div>
										<!-- End Submit Field -->
									</form>
								</div>
								<div class="col-md-4">
									<div class="thumbnail item-box live-preview">
										<img id="image-preview" class="img-responsive" src="<?php if (! empty($row['Image'])) {echo 'AdminDashboard\uploads\avatars\\' . $row['Image'];} else {echo $images . 'img.png';} ?>" alt="" />
										<div class="caption text-center">
											<h3 class="live-fullname"><?php echo $row['FullName'] ?></h3>
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

			} ?>
							</div>
						</div>
					</div>
				</div>

  <?php } elseif ($action == 'UpdateUser') { // Update Page

			echo "<h1 class='info text-center'><i class='fas fa-user-edit fa-fw fa-lg'></i> تحديث البيانات</h1>";
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
				$username 	= $_POST['username'];
				$email 		= $_POST['email'];
				$name 		= $_POST['full'];
				$group 		= $_POST['group'];
				$section 	= $_POST['section'];
				$band 		= $_POST['band'];

				if (isset($username)) {

					$filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

					if (strlen($filterdUser) < 4) {

						$formErrors[] = 'أسم المستخدم يجب أن يكون أكبر من 4 أحرف';

					}

				}

				// Password Trick

				$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : md5($_POST['newpassword']);

				if (isset($email)) {

					$filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

					if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {

						$formErrors[] = 'البريد الإلكترونى غير صالح';

					}

				}

				// Validate The Form

				$formErrors = array();

				if (strlen($username) < 4) {
					$formErrors[] = 'اسم المستخدم يجب ألا يكون <strong>أقل من 4 أحرف</strong>';
				}

				if (strlen($username) > 20) {
					$formErrors[] = 'اسم المستخدم يجب ألا يكون <strong>أكبر من 20 حرف</strong>';
				}

				if (empty($username)) {
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

						move_uploaded_file($avatarTmp, "AdminDashboard\uploads\avatars\\" . $avatar);

					}

					$stmt2 = $con->prepare("SELECT 
												*
											FROM 
												users
											WHERE
												Username = ?
											AND 
												UserID != ?");

					$stmt2->execute(array($username, $id));

					$count = $stmt2->rowCount();

					if ($count == 1) {

						$theMsg = '<div class="alert alert-danger text-center">نآسف هذا المستخدم موجود</div>';

						redirectHome($theMsg, 'back');

					} else { 

						// Update The Database With This Info

						$stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ?, GroupID = ?, SectionID = ?, BandID = ?, Image = ? WHERE UserID = ?");

						$stmt->execute(array($username, $email, $name, $pass, $group, $section, $band, $avatar, $id));

						// Echo Success Message

						$theMsg = '<div class="alert alert-success text-center">تم تحديث البيانات بنجاح !</div>';

						redirectHome($theMsg, 'back');

					}

				}

			} else {

				$theMsg = '<div class="alert alert-danger text-center">نآسف لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($action == 'EditCourse') {

			// Check If Get Request item Is Numeric & Get Its Integer Value

			$courseid = isset($_GET['courseid']) && is_numeric($_GET['courseid']) ? intval($_GET['courseid']) : 0;

			// Select All Data Depend On This ID

			$stmt = $con->prepare("SELECT * FROM courses WHERE Course_ID = ?");

			// Execute Query

			$stmt->execute(array($courseid));

			// Fetch The Data

			$course = $stmt->fetch();

			// The Row Count

			$count = $stmt->rowCount();

			if ($count > 0) {

				// Select All Data Depend On This ID

				$stmt = $con->prepare("SELECT * FROM books WHERE Course_ID = ?");

				// Execute Query

				$stmt->execute(array($courseid));

				// Fetch The Data

				$coursebook = $stmt->fetch();
			}

			// If There's Such ID Show The Form

			if ($count > 0) { ?>

				<h1 class="info text-center"><i class="fas fa-suitcase fa-fw fa-lg"></i> تحديث الدورة</h1>
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading">تحديث الدورة</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-8">
									<form class="form-horizontal main-form" action="?action=UpdateCourse" method="POST" enctype="multipart/form-data">
										<input type="hidden" name="courseid" value="<?php echo $courseid ?>" />
										<!-- Start Title Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">عنوان الدورة</label>
											<div class="col-sm-10 col-md-9">
												<input 
													type="text" 
													name="title" 
													class="form-control live" 
													required="required"  
													placeholder="عنوان الدورة"
													data-class=".live-title"
													value="<?php echo $course['Title'] ?>" />
											</div>
										</div>
										<!-- End Title Field -->
										<!-- Start Description Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">وصف المحتوى</label>
											<div class="col-sm-10 col-md-9">
												<input 
													type="text" 
													name="description" 
													class="form-control live" 
													required="required"  
													placeholder="وصف الدورة"
													data-class=".live-desc"
													value="<?php echo $course['Description'] ?>" />
											</div>
										</div>
										<!-- End Description Field -->
										<!-- Start Course Image Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">غلاف الدورة</label>
											<div class="col-sm-10 col-md-9">
												<input type="hidden" name="oldimage" value="<?php echo $course['Image'] ?>" />
												<input type="file" name="newimage" id="image-upload" class="form-control" />
											</div>
										</div>
										<!-- End Course Image Field -->
										<!-- Start Document Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">الملف المرفق</label>
											<div class="col-sm-10 col-md-9">
												<input type="hidden" name="olddocument" value="<?php echo $coursebook['Book_Source'] ?>" />
												<input type="file" name="newdocument" class="form-control" />
											</div>
										</div>
										<!-- End Document Field -->
										<!-- Start Document Title Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">عنوان الملف</label>
											<div class="col-sm-10 col-md-9">
												<input 
													pattern=".{10,}"
													title="عنوان الملف المرفق لا يقل عن 4 أحرف"
													type="text" 
													name="doc-title" 
													class="form-control" 
													placeholder="" 
													required 
													value="<?php echo $coursebook['Book_Title'] ?>" />
											</div>
										</div>
										<!-- End Document Title Field -->
										<!-- Start Document Cover Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">غلاف الملف</label>
											<div class="col-sm-10 col-md-9">
												<input type="hidden" name="olddoc-cover" value="<?php echo $coursebook['Book_Cover'] ?>" />
												<input type="file" name="newdoc-cover" class="form-control" />
											</div>
										</div>
										<!-- End Document Cover Field -->
										<!-- Start Sections Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">القسم</label>
											<div class="col-sm-10 col-md-9">
												<select name="section">
													<?php
														$stmt = $con->prepare("SELECT * FROM sections WHERE Allow_Courses = 1");
														$stmt->execute();
														$sections = $stmt->fetchAll();
														foreach ($sections as $section) {
															echo "<option value='" . $section['ID'] . "'"; 
															if ($course['Section_ID'] == $section['ID']) { echo 'selected'; } 
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
															if ($course['Band_ID'] == $band['Band_ID']) { echo 'selected'; } 
															echo ">" . $band['Band_Name'] . "</option>";
														}
													?>
												</select>
											</div>
										</div>
										<!-- End Band Field -->
										<!-- Start Submit Field -->
										<div class="form-group form-group-lg">
											<div class="col-sm-offset-3 col-md-9">
												<input type="submit" value="تحديث الدورة" class="btn btn-primary btn-lg form-control" />
											</div>
										</div>
										<!-- End Submit Field -->
									</form>
								</div>
								<div class="col-md-4">
									<div class="thumbnail item-box live-preview">
										<img id="image-preview" class="img-responsive" src="AdminDashboard\uploads\CoursesImages\<?php echo $course['Image']; ?>" alt="" />
										<div class="caption">
											<h3 class="live-title"><?php echo $course['Title'] ?></h3>
											<p class="live-desc"><?php echo $course['Description'] ?></p>
										</div>
									</div>
								</div>

							<?php

			// If There's No Such ID Show Error Message

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">لا يوجد مثل هذا المُعرف</div>';

				redirectHome($theMsg);

				echo "</div>";

			} ?>
							</div>
						</div>
					</div>
					<?php
						if ($count > 0) {

							// Select All Data Depend On This CourseID

							$stmt = $con->prepare("SELECT * FROM courses_videos WHERE Course_ID = ?");

							// Execute Query

							$stmt->execute(array($courseid));

							// Fetch The Data

							$videos = $stmt->fetchAll();

							// If There's Such ID Show The Form

							if (! empty($videos)) { ?>

								<div class="panel panel-primary">
									<div class="panel-heading">فيديوهات الدورة</div>
									<div class="panel-body">
										<div class="row">
										<?php foreach ($videos as $video) { ?>
											<div class="col-sm-3 my-comments">
												<div class="comments-overlay">
													<img class="img-responsive img-thumbnail center-block" src="AdminDashboard/uploads/CoursesVideos/<?php echo $video['Image']; ?>" alt="<?php echo $video['Image']; ?>" />
													<div class="course-videos">
														<a href="?action=EditVideo&videoid=<?php echo $video['Video_ID']; ?>" class="btn btn-success">تحديث</a>
														<a href="?action=DeleteVideo&videoid=<?php echo $video['Video_ID']; ?>" class="btn btn-danger confirm">حذف</a>
													</div>
												</div>
												<h4 class="video-title text-center"><a href="<?php echo 'courses.php?courseid=' . $video['Course_ID'] . '&videoid=' . $video['Video_ID'] . '&videosource=' . $video['Video_Source'] ?>"><?php echo ucwords($video['Title']) ?></a></h4>
											</div>
										<?php } ?>
										</div>
									</div>
								</div>
					  <?php } else {

								echo '<div class="alert alert-success text-center">لا يوجد فيديوهات لهذه الدورة .</div>';

							}
						}
					?>
				</div>

  <?php } elseif ($action == 'UpdateCourse') {

			echo "<h1 class='info text-center'><i class='fas fa-suitcase fa-fw fa-lg'></i> تحديث الدورة</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				if (! empty($_FILES['newimage']['name'])) {

					// Course Cover Upload Variables

					$imageName	= $_FILES['newimage']['name'];
					$imageSize	= $_FILES['newimage']['size'];
					$imageTmp	= $_FILES['newimage']['tmp_name'];
					$imageType	= $_FILES['newimage']['type'];

					// List Of Allowed File Typed To Upload

					$imageAllowedExtension = array("jpeg", "jpg", "png", "gif");

					// Get Course Cover Extension

					$tmp = explode('.', $imageName);

					$imageExtension = strtolower(end($tmp));

				} else {

					// Get Variable From The Form

					$image = $_POST['oldimage'];
				}

				if (! empty($_FILES['newdocument']['name'])) {

					// Document Upload Variables

					$documentName	= $_FILES['newdocument']['name'];
					$documentSize	= $_FILES['newdocument']['size'];
					$documentTmp	= $_FILES['newdocument']['tmp_name'];
					$documentType	= $_FILES['newdocument']['type'];

					// List Of Allowed File Typed To Upload

					$documentAllowedExtension = array("pdf", "docx", "ppt", "xlsx");

					// Get Document Extension

					$docTmp = explode('.', $documentName);

					$documentExtension = strtolower(end($docTmp));

				} else {

					// Get Variable From The Form

					$document = $_POST['olddocument'];
				}

				if (! empty($_FILES['newdoc-cover']['name'])) {

					// Document Cover Upload Variables

					$docCoverName	= $_FILES['newdoc-cover']['name'];
					$docCoverSize	= $_FILES['newdoc-cover']['size'];
					$docCoverTmp	= $_FILES['newdoc-cover']['tmp_name'];
					$docCoverType	= $_FILES['newdoc-cover']['type'];

					// List Of Allowed File Typed To Upload

					$docCoverAllowedExtension = array("jpeg", "jpg", "png", "gif");

					// Get Document Cover Extension

					$DocCoverTmp = explode('.', $docCoverName);

					$documentExtension = strtolower(end($DocCoverTmp));

				} else {

					// Get Variable From The Form

					$docCover = $_POST['olddoc-cover'];
				}

				// Get Variables From The Form

				$id 		= $_POST['courseid'];
				$title 		= $_POST['title'];
				$desc 		= $_POST['description'];
				$docTitle 	= $_POST['doc-title'];
				$section 	= $_POST['section'];
				$band 		= $_POST['band'];
				$teacher 	= $_SESSION['uid'];

				// Validate The Form

				$formErrors = array();

				if (empty($title)) {
					$formErrors[] = 'عنوان الدورة يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (empty($desc)) {
					$formErrors[] = 'وصف الدورة يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (strlen($docTitle) < 10) {
					$formErrors[] = 'Document Title Must Be At Least 10 Characters';
				}

				if (! empty($_FILES['newimage']['name'])) {

					if (! empty($imageName) && ! in_array($imageExtension, $imageAllowedExtension)) {
						$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
					}

					if ($imageSize > 4194304) {
						$formErrors[] = 'Image Cant Be Larger Than <strong>4MB</strong>';
					}
				}

				if (! empty($_FILES['newdocument']['name'])) {

					if (! empty($documentName) && ! in_array($documentExtension, $documentAllowedExtension)) {
						$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
					}

					if ($documentSize > 10485760) {
						$formErrors[] = 'Document Cant Be Larger Than <strong>10MB</strong>';
					}
				}

				if (! empty($_FILES['newdoc-cover']['name'])) {

					if (! empty($docCoverName) && ! in_array($docCoverExtension, $docCoverAllowedExtension)) {
						$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
					}

					if ($docCoverSize > 4194304) {
						$formErrors[] = 'Document Cover Cant Be Larger Than <strong>4MB</strong>';
					}
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					if (! empty($_FILES['newimage']['name'])) {

						$image = rand(0, 1000000) . '_' . $imageName;

						move_uploaded_file($imageTmp, "AdminDashboard\uploads\CoursesImages\\" . $image);

					}

					if (! empty($_FILES['newdocument']['name'])) {

						$document = rand(0, 1000000) . '_' . $documentName;

						move_uploaded_file($documentTmp, "AdminDashboard\uploads\Books\\" . $document);

					}

					if (! empty($_FILES['newdoc-cover']['name'])) {

						$docCover = rand(0, 1000000) . '_' . $docCoverName;

						move_uploaded_file($docCoverTmp, "AdminDashboard\uploads\Books\\" . $docCover);

					}

					// Update The Database With This Info

					$stmt = $con->prepare("UPDATE 
												courses 
											SET 
												Title = ?,
												Description = ?,
												Image = ?,
												Section_ID = ?,
												Band_ID = ?,
												Teacher_ID = ?
											WHERE 
												Course_ID = ?");

					$stmt->execute(array($title, $desc, $image, $section, $band, $teacher, $id));


					$stmt2 = $con->prepare("UPDATE 
												books 
											SET 
												Book_Title = ?,
												Book_Cover = ?,
												Book_Source = ?
											WHERE 
												Course_ID = ?");

					$stmt2->execute(array($docTitle, $docCover, $document, $id));

					// Echo Success Message

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' تم تحديث الدورة بنجاح !</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				$theMsg = '<div class="alert alert-danger">لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($action == 'DeleteCourse') {

			echo "<h1 class='info text-center'><i class='fas fa-suitcase fa-fw fa-lg'></i> حذف الدورة</h1>";
			echo "<div class='container'>";

				// Check If Get Request Course ID Is Numeric & Get The Integer Value Of It

				$courseid = isset($_GET['courseid']) && is_numeric($_GET['courseid']) ? intval($_GET['courseid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('Course_ID', 'courses', $courseid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM courses WHERE Course_ID = :zid");

					$stmt->bindParam(":zid", $courseid);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' تم حذف الدورة بنجاح !</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger">هذا المُعرف غير موجود</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} elseif ($action == 'EditVideo') {

			// Check If Get Request item Is Numeric & Get Its Integer Value

			$videoid = isset($_GET['videoid']) && is_numeric($_GET['videoid']) ? intval($_GET['videoid']) : 0;

			// Select All Data Depend On This ID

			$stmt = $con->prepare("SELECT * FROM courses_videos WHERE Video_ID = ?");

			// Execute Query

			$stmt->execute(array($videoid));

			// Fetch The Data

			$video = $stmt->fetch();

			// The Row Count

			$count = $stmt->rowCount();

			// If There's Such ID Show The Form

			if ($count > 0) { ?>

				<h1 class="info text-center"><i class="fas fa-film fa-fw fa-lg"></i> تحديث الفيديو</h1>
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading">تحديث الفيديو</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-8">
									<form class="form-horizontal main-form" action="?action=UpdateVideo" method="POST" enctype="multipart/form-data">
										<input type="hidden" name="videoid" value="<?php echo $videoid ?>" />
										<!-- Start Title Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">العنوان</label>
											<div class="col-sm-10 col-md-9">
												<input 
													type="text" 
													name="title" 
													class="form-control live" 
													required="required"  
													placeholder="عنوان الفيديو"
													data-class=".live-title"
													value="<?php echo $video['Title'] ?>" />
											</div>
										</div>
										<!-- End Title Field -->
										<!-- Start Image Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">الغلاف</label>
											<div class="col-sm-10 col-md-9">
												<input type="hidden" name="oldimage" value="<?php echo $video['Image'] ?>" />
												<input type="file" name="newimage" id="image-upload" class="form-control" />
											</div>
										</div>
										<!-- End Image Field -->
										<!-- Start Video Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">ملف الفيديو</label>
											<div class="col-sm-10 col-md-9">
												<input type="hidden" name="oldvideo" value="<?php echo $video['Video_Source'] ?>" />
												<input type="file" name="newvideo" id="video-upload" class="form-control" />
											</div>
										</div>
										<!-- End Video Field -->
										<!-- Start Courses Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">الدورة</label>
											<div class="col-sm-10 col-md-9">
												<select name="course">
													<?php
														$teacher = $_SESSION['uid'];
														$stmt = $con->prepare("SELECT courses.*, sections.Name AS section_name, band.Band_Name FROM courses INNER JOIN sections ON sections.ID = courses.Section_ID INNER JOIN band ON band.Band_ID = courses.Band_ID WHERE Teacher_ID = ?");
														$stmt->execute(array($teacher));
														$courses = $stmt->fetchAll();
														foreach ($courses as $course) {
															echo "<option value='" . $course['Course_ID'] . "'"; 
															if ($video['Course_ID'] == $course['Course_ID']) { echo 'selected'; } 
															echo ">" . $course['Title'] . " | قسم " . $course['section_name'] . " | " . $course['Band_Name'] . "</option>";
														}
													?>
												</select>
											</div>
										</div>
										<!-- End Courses Field -->
										<!-- Start Submit Field -->
										<div class="form-group form-group-lg">
											<div class="col-sm-offset-3 col-md-9">
												<input type="submit" value="تحديث الفيديو" class="btn btn-primary btn-lg form-control" />
											</div>
										</div>
										<!-- End Submit Field -->
									</form>
								</div>
								<div class="col-md-4">
									<div class="thumbnail item-box live-preview">
										<img id="image-preview" class="img-responsive" src="AdminDashboard\uploads\CoursesVideos\<?php echo $video['Image']; ?>" alt="" />
										<video width="100%" height="240" controls autoplay>
											<source id="video-preview" src="AdminDashboard\uploads\CoursesVideos\<?php echo $video['Video_Source'] ?>" type="video/mp4">
											Your browser does not support HTML5 video.
										</video>
										<div class="caption">
											<h3 class="live-title"><?php echo $video['Title'] ?></h3>
										</div>
									</div>
								</div>

			<?php

			// If There's No Such ID Show Error Message

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger">هذا المُعرف غير موجود</div>';

				redirectHome($theMsg);

				echo "</div>";

			} ?>
							</div>
						</div>
					</div>
				</div>

  <?php } elseif ($action == 'UpdateVideo') {

			echo "<h1 class='info text-center'><i class='fas fa-film fa-fw fa-lg'></i> تحديث الفيديو</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$id 	= $_POST['videoid'];
				$title	= $_POST['title'];
				$course = $_POST['course'];

				if (! empty($_FILES['newimage']['name'])) {

					// Cover Upload Variables

					$imageName	= $_FILES['image']['name'];
					$imageSize	= $_FILES['image']['size'];
					$imageTmp	= $_FILES['image']['tmp_name'];
					$imageType	= $_FILES['image']['type'];

					// List Of Allowed File Typed To Upload

					$imageAllowedExtension = array("jpeg", "jpg", "png", "gif");

					// Get Cover Extension

					$imgTmp = explode('.', $imageName);

					$imageExtension = strtolower(end($imgTmp));

				} else {

					// Get Variable From The Form

					$image = $_POST['oldimage'];
				}



				if (! empty($_FILES['newvideo']['name'])) {

					// PDF File Upload Variables

					$videoName	= $_FILES['video']['name'];
					$videoSize	= $_FILES['video']['size'];
					$videoTmp	= $_FILES['video']['tmp_name'];
					$videoType	= $_FILES['video']['type'];

					// List Of Allowed File Typed To Upload

					$videoAllowedExtension = array("mp4", "avi", "flv", "wmv", "mov");

					// Get PDF File Extension

					$tmp = explode('.', $videoName);

					$videoExtension = strtolower(end($tmp));
				} else {

					// Get Variable From The Form

					$video = $_POST['oldvideo'];
				}


				// Validate The Form

				$formErrors = array();

				if (empty($title)) {
					$formErrors[] = 'عنوان الفيديو يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (! empty($_FILES['newimage']['name'])) {

					if (! empty($imageName) && ! in_array($imageExtension, $imageAllowedExtension)) {
						$formErrors[] = 'إمتداد صورة الغلاف هذا <strong>غير مسموح به</strong>';
					}

					if ($imageSize > 4194304) {

						$formErrors[] = 'يجب ألا يكون حجم صورة الغلاف <strong>أكبر من 4 ميغابايت</strong>';

					}
				}

				if (! empty($_FILES['newvideo']['name'])) {

					if (! empty($videoName) && ! in_array($videoExtension, $videoAllowedExtension)) {
						$formErrors[] = 'إمتداد ملف الفيديو هذا <strong>غير مسموح به</strong>';
					}

					if ($videoSize > 52428800) {
						$formErrors[] = 'يجب ألا يكون حجم الفيديو <strong>أكبر من 50 ميغابايت</strong>';
					}
				}

				if ($course == 0) {
					$formErrors[] = 'يجب أن تختار الدورة المُضاف إليها الفيديو';
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					if (! empty($_FILES['newimage']['name'])) {

						$image = rand(0, 1000000) . '_' . $imageName;

						move_uploaded_file($imageTmp, "AdminDashboard\uploads\CoursesVideos\\" . $image);
					}

					if (! empty($_FILES['newvideo']['name'])) {

						$video = rand(0, 1000000) . '_' . $videoName;

						move_uploaded_file($videoTmp, "AdminDashboard\uploads\CoursesVideos\\" . $video);
					}

					// Update The Database With This Info

					$stmt = $con->prepare("UPDATE 
												courses_videos 
											SET 
												Title = ?,
												Image = ?,
												Video_Source = ?,
												Course_ID = ?
											WHERE 
												Video_ID = ?");

					$stmt->execute(array($title, $image, $video, $course, $id));

					// Echo Success Message

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' تم تحديث الفيديو بنجاح !</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				$theMsg = '<div class="alert alert-danger">لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($action == 'DeleteVideo') {

			echo "<h1 class='info text-center'><i class='fas fa-film fa-fw fa-lg'></i> حذف الفيديو</h1>";
			echo "<div class='container'>";

				// Check If Get Request Course ID Is Numeric & Get The Integer Value Of It

				$videoid = isset($_GET['videoid']) && is_numeric($_GET['videoid']) ? intval($_GET['videoid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('Video_ID', 'courses_videos', $videoid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM courses_videos WHERE Video_ID = :zid");

					$stmt->bindParam(":zid", $videoid);

					$stmt->execute();

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' تم حذف الفيديو بنجاح !</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger">هذا المُعرف غير موجود</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		}

	} else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>