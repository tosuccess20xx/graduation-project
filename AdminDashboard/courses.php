<?php

	/*
	================================================
	== Courses Page
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Courses';

	if (isset($_SESSION['Username'])) {

		include 'initialization.php';

		$action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

		if ($action == 'Manage') {


			$stmt = $con->prepare("SELECT 
										courses.*, 
										sections.Name AS section_name, 
										users.Username,
										band.Band_Name 
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
									ORDER BY 
										Course_ID DESC");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$courses = $stmt->fetchAll();

			if (! empty($courses)) {

			?>

			<h1 class="text-center"><i class="fas fa-suitcase fa-fw fa-lg"></i> إدارة الدورات</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table manage-members text-center table table-bordered">
						<tr>
							<td>ID</td>
							<td>الغلاف</td>
							<td>عنوان الدورة</td>
							<td>الوصف</td>
							<td>تاريخ الإضافة</td>
							<td>القسم</td>
							<td>الفرقة</td>
							<td>المدرب</td>
							<td>التحكم</td>
						</tr>
						<?php
							foreach($courses as $course) {
								echo "<tr>";
									echo "<td>" . $course['Course_ID'] . "</td>";
									echo "<td>";
									if (empty($course['Image'])) {
										echo 'No Image';
									} else {
										echo "<img src='uploads/CoursesImages/" . $course['Image'] . "' alt='' />";
									}
									echo "</td>";
									echo "<td>" . $course['Title'] . "</td>";
									echo "<td>" . $course['Description'] . "</td>";
									echo "<td>" . $course['Create_Date'] ."</td>";
									echo "<td>" . $course['section_name'] ."</td>";
									echo "<td>" . $course['Band_Name'] ."</td>";
									echo "<td>" . $course['Username'] ."</td>";
									echo "<td>
										<a href='courses.php?action=Edit&courseid=" . $course['Course_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> تحديث</a>
										<a href='courses.php?action=Delete&courseid=" . $course['Course_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-times'></i> حذف</a>";
										if ($course['Approve'] == 0) {
											echo "<a 
													href='courses.php?action=Approve&courseid=" . $course['Course_ID'] . "' 
													class='btn btn-info activate confirm'>
													<i class='fa fa-check'></i> موافقة</a>";
										} else {
                                            echo "<a 
													href='courses.php?action=UnApprove&courseid=" . $course['Course_ID'] . "' 
													class='btn btn-danger activate confirm'>
													<i class='fa fa-ban'></i> إلغاء الموافقة</a>";
                                        }
									echo "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
				<a href="courses.php?action=Add" class="btn btn-lg btn-primary">
					<i class="far fa-plus-square fa-fw fa-lg"></i> إضافة دورة جديدة
				</a>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">لا يوجد دورات لعرضها</div>';
					echo '<a href="courses.php?action=Add" class="btn btn-lg btn-primary">
							<i class="far fa-plus-square fa-fw fa-lg"></i> دورة جديدة
						</a>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($action == 'Add') { ?>

			<h1 class="text-center"><i class="fas fa-suitcase fa-fw fa-lg"></i> إضافة دورة جديدة</h1>
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading">إضافة دورة جديدة</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-8">
								<form class="form-horizontal" action="?action=Insert" method="POST" enctype="multipart/form-data">
									<!-- Start Title Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">عنوان الدورة</label>
										<div class="col-sm-10 col-md-9">
											<input 
												type="text" 
												name="title" 
												class="form-control live" 
												required="required"  
												placeholder="" 
												data-class=".live-title" />
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
												placeholder="" 
												data-class=".live-desc" />
										</div>
									</div>
									<!-- End Description Field -->
									<!-- Start Image Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">غلاف الدورة</label>
										<div class="col-sm-10 col-md-9">
											<input type="file" name="image" id="image-upload" class="form-control" required="required" />
										</div>
									</div>
									<!-- End Image Field -->
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
									<!-- Start Teachers Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">المدرب</label>
										<div class="col-sm-10 col-md-9">
											<select name="teacher">
												<option value="0">...</option>
												<?php
													$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 3");
													$stmt->execute();
													$users = $stmt->fetchAll();
													foreach ($users as $user) {
														echo "<option value='" . $user['UserID'] . "'>" . ucfirst($user['FullName']) . "</option>";
													}
												?>
											</select>
										</div>
									</div>
									<!-- End Teachers Field -->
									<!-- Start Submit Field -->
									<div class="form-group form-group-lg">
										<div class="col-sm-offset-3 col-md-9">
											<input type="submit" value="إضافة دورة" class="btn btn-primary btn-lg form-control" />
										</div>
									</div>
									<!-- End Submit Field -->
								</form>
							</div>
							<div class="col-md-4">
								<div class="thumbnail item-box live-preview">
									<span class="price-tag">
										<span class="live-band">الفرقة</span>
									</span>
									<img id="image-preview" class="img-responsive" src="<?php echo $images; ?>img.png" alt="" />
									<div class="caption">
										<h3 class="live-title">عنوان الدورة</h3>
										<p class="live-desc">وصف المحتوى</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php

		} elseif ($action == 'Insert') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'><i class='fas fa-suitcase fa-fw fa-lg'></i> إضافة دورة</h1>";
				echo "<div class='container'>";

				// Get Variables From The Form

				$title		= $_POST['title'];
				$desc 		= $_POST['description'];
				$section 	= $_POST['section'];
				$band 		= $_POST['band'];
				$teacher 	= $_POST['teacher'];

				// Course Cover Upload Variables

				$imageName	= $_FILES['image']['name'];
				$imageSize	= $_FILES['image']['size'];
				$imageTmp	= $_FILES['image']['tmp_name'];
				$imageType	= $_FILES['image']['type'];

				// List Of Allowed File Typed To Upload

				$imageAllowedExtension = array("jpeg", "jpg", "png", "gif");

				// Get Course Cover Extension

				$tmp = explode('.', $imageName);

				$imageExtension = strtolower(end($tmp));

				// Validate The Form

				$formErrors = array();

				if (empty($title)) {
					$formErrors[] = 'عنوان الدورة يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (empty($desc)) {
					$formErrors[] = 'وصف الدورة يجب ألا يكون <strong>فارغاً</strong>';
				}

				if ($section == 0) {
					$formErrors[] = 'يجب عليك أن تختار <strong>القسم</strong>';
				}

				if ($band == 0) {
					$formErrors[] = 'يجب عليك أن تختار <strong>الفرقة</strong>';
				}

				if ($teacher == 0) {
					$formErrors[] = 'يجب عليك أن تختار <strong>المدرب</strong>';
				}

				if (! empty($imageName) && ! in_array($imageExtension, $imageAllowedExtension)) {
					$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
				}

				if (empty($imageName)) {
					$formErrors[] = 'Image Is <strong>Required</strong>';
				}

				if ($imageSize > 4194304) {
					$formErrors[] = 'Image Cant Be Larger Than <strong>4MB</strong>';
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger text-center">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					$image = rand(0, 1000000) . '_' . $imageName;

					move_uploaded_file($imageTmp, "uploads\CoursesImages\\" . $image);

					// Insert Userinfo In Database

					$stmt = $con->prepare("INSERT INTO 

						courses(Title, Description, Create_Date, Image, Approve, Section_ID, Band_ID, Teacher_ID)

						VALUES(:ztitle, :zdesc, now(), 1, :zsection, :zband, :zteacher)");

					$stmt->execute(array(

						'ztitle' 	=> $title,
						'zdesc' 	=> $desc,
						'zimage' 	=> $image,
						'zsection'	=> $section,
						'zband' 	=> $band,
						'zteacher'	=> $teacher

					));

					// Echo Success Message

					$theMsg = '<div class="alert alert-success text-center">تم إضافة الدورة بنجاح !</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger text-center">لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg);

				echo "</div>";

			}

			echo "</div>";

		} elseif ($action == 'Edit') {

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

			// If There's Such ID Show The Form

			if ($count > 0) { ?>

				<h1 class="text-center"><i class="fas fa-suitcase fa-fw fa-lg"></i> تحديث الدورة</h1>
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading">تحديث الدورة</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-8">
									<form class="form-horizontal" action="?action=Update" method="POST" enctype="multipart/form-data">
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
													placeholder=""
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
													placeholder=""
													data-class=".live-desc"
													value="<?php echo $course['Description'] ?>" />
											</div>
										</div>
										<!-- End Description Field -->
										<!-- Start Image Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">غلاف الدورة</label>
											<div class="col-sm-10 col-md-9">
												<input type="hidden" name="oldimage" value="<?php echo $course['Image'] ?>" />
												<input type="file" name="newimage" id="image-upload" class="form-control" />
											</div>
										</div>
										<!-- End Image Field -->
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
										<!-- Start Teachers Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">المدرب</label>
											<div class="col-sm-10 col-md-9">
												<select name="teacher">
													<?php
														$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 3");
														$stmt->execute();
														$users = $stmt->fetchAll();
														foreach ($users as $user) {
															echo "<option value='" . $user['UserID'] . "'"; 
															if ($course['Teacher_ID'] == $user['UserID']) { echo 'selected'; } 
															echo ">" . ucfirst($user['FullName']) . "</option>";
														}
													?>
												</select>
											</div>
										</div>
										<!-- End Teachers Field -->
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
										<span class="price-tag">
											<span class="live-band">الفرقة</span>
										</span>
										<img id="image-preview" class="img-responsive" src="uploads\CoursesImages\<?php echo $course['Image']; ?>" alt="" />
										<div class="caption">
											<h3 class="live-title"><?php echo $course['Title'] ?></h3>
											<p class="live-desc"><?php echo $course['Description'] ?></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading"><i class="fas fa-film fa-fw fa-lg"></i> إدارة فيديوهات الدورة</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<?php

										$stmt = $con->prepare("SELECT 
																	courses_videos.*, 
																	courses.Title AS course_name 
																FROM 
																	courses_videos
																INNER JOIN 
																	courses 
																ON 
																	courses.Course_ID = courses_videos.Course_ID 
																WHERE 
																	courses_videos.Course_ID = ? 
																ORDER BY 
																	Video_ID DESC");

										// Execute The Statement

										$stmt->execute(array($courseid));

										// Assign To Variable 

										$videos = $stmt->fetchAll();

										if (! empty($videos)) {

										?>

										<div class="table-responsive">
											<table class="main-table manage-members text-center table table-bordered">
												<tr>
													<td>ID</td>
													<td>الغلاف</td>
													<td>عنوان الفيديو</td>
													<td>ملف الفيديو</td>
													<td>تاريخ الإضافة</td>
													<td>اسم الدورة</td>
													<td>التحكم</td>
												</tr>
												<?php
													foreach($videos as $video) {
														echo "<tr>";
															echo "<td>" . $video['Video_ID'] . "</td>";
															echo "<td>";
															if (empty($video['Image'])) {
																echo 'No Image';
															} else {
																echo "<img src='uploads/CoursesVideos/" . $video['Image'] . "' alt='' />";
															}
															echo "</td>";
															echo "<td>" . $video['Title'] . "</td>";
															echo "<td>" . $video['Video_Source'] . "</td>";
															echo "<td>" . $video['Create_Date'] ."</td>";
															echo "<td>" . $video['course_name'] ."</td>";
															echo "<td>
																<a href='videos.php?action=Edit&videoid=" . $video['Video_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> تحديث</a>
																<a href='videos.php?action=Delete&videoid=" . $video['Video_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-times'></i> حذف</a>";
															echo "</td>";
														echo "</tr>";
													}
												?>
												<tr>
											</table>
										</div>

										<?php } else {

											echo '<div class="container">';
												echo '<div class="nice-message">لا يوجد فيديوهات لعرضها</div>';
												echo '<a href="videos.php?action=Add" class="btn btn-lg btn-primary">
														<i class="fas fa-video-plus fa-fw fa-lg"></i> فيديو جديد
													</a>';
											echo '</div>';

										} ?>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading"><i class="far fa-books fa-fw fa-lg"></i> إدارة كتب الدورة</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<?php

										$stmt = $con->prepare("SELECT * FROM books WHERE Course_ID = ?");

										// Execute The Statement

										$stmt->execute(array($courseid));

										// Assign To Variable 

										$books = $stmt->fetchAll();

										if (! empty($books)) {

										?>

										<div class="table-responsive">
											<table class="main-table manage-members text-center table table-bordered">
												<tr>
													<td>ID</td>
													<td>عنوان الكتاب</td>
													<td>غلاف الكتاب</td>
													<td>ملف الكتاب</td>
													<td>تاريخ الإضافة</td>
													<td>التحكم</td>
												</tr>
												<?php
													foreach($books as $book) {
														echo "<tr>";
															echo "<td>" . $book['Book_ID'] . "</td>";
															echo "<td>" . $book['Book_Title'] . "</td>";
															echo "<td>";
															if (empty($book['Book_Cover'])) {
																echo 'لا يوجد غلاف';
															} else {
																echo "<img src='uploads/Books/" . $book['Book_Cover'] . "' alt='' />";
															}
															echo "</td>";
															echo "<td>" . $book['Book_Source'] . "</td>";
															echo "<td>" . $book['Create_Date'] ."</td>";
															echo "<td>
																<a href='books.php?action=Edit&bookid=" . $book['Book_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> تحديث</a>
																<a href='books.php?action=Delete&bookid=" . $book['Book_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-times'></i> حذف</a>";
															echo "</td>";
														echo "</tr>";
													}
												?>
											</table>
										</div>

										<?php } else {

											echo '<div class="container">';
												echo '<div class="nice-message">لا يوجد كتب لعرضها</div>';
												echo '<a href="books.php?action=Add" class="btn btn-lg btn-primary">
														<i class="far fa-plus-square fa-fw fa-lg"></i> إضافة كتاب جديد
													</a>';
											echo '</div>';

										} ?>
								</div>
							</div>
						</div>
					</div>
				</div>

			<?php

			// If There's No Such ID Show Error Message

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger text-center">لا يوجد مثل هذا المُعرف</div>';

				redirectHome($theMsg);

				echo "</div>";

			}			

		} elseif ($action == 'Update') {

			echo "<h1 class='text-center'><i class='fas fa-suitcase fa-fw fa-lg'></i> تحديث الدورة</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$id 		= $_POST['courseid'];
				$title 		= $_POST['title'];
				$desc 		= $_POST['description'];
				$section 	= $_POST['section'];
				$band 		= $_POST['band'];
				$teacher 	= $_POST['teacher'];

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

				// Validate The Form

				$formErrors = array();

				if (empty($title)) {
					$formErrors[] = 'عنوان الدورة يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (empty($desc)) {
					$formErrors[] = 'وصف الدورة يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (! empty($_FILES['newimage']['name'])) {

					if (! empty($imageName) && ! in_array($imageExtension, $imageAllowedExtension)) {
						$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
					}

					if ($imageSize > 4194304) {
						$formErrors[] = 'Image Cant Be Larger Than <strong>4MB</strong>';
					}
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger text-center">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					if (! empty($_FILES['newimage']['name'])) {

						$image = rand(0, 1000000) . '_' . $imageName;

						move_uploaded_file($imageTmp, "uploads\CoursesImages\\" . $image);

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

					// Echo Success Message

					$theMsg = '<div class="alert alert-success text-center">تم تحديث الدورة بنجاح !</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				$theMsg = '<div class="alert alert-danger text-center">لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($action == 'Delete') {

			echo "<h1 class='text-center'><i class='fas fa-suitcase fa-fw fa-lg'></i> حذف الدورة</h1>";
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

					$theMsg = '<div class="alert alert-success text-center">تم حذف الدورة بنجاح !</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger text-center">هذا المُعرف غير موجود</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} elseif ($action == 'Approve') {

			echo "<h1 class='text-center'><i class='fas fa-suitcase fa-fw fa-lg'></i> الموافقة على الدورة</h1>";
			echo "<div class='container'>";

				// Check If Get Request Course ID Is Numeric & Get The Integer Value Of It

				$courseid = isset($_GET['courseid']) && is_numeric($_GET['courseid']) ? intval($_GET['courseid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('Course_ID', 'courses', $courseid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE courses SET Approve = 1 WHERE Course_ID = ?");

					$stmt->execute(array($courseid));

					$theMsg = '<div class="alert alert-success text-center">تم الموافقة على الدورة بنجاح !</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger text-center">هذا المُعرف غير موجود</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} elseif ($action == 'UnApprove') {

			echo "<h1 class='text-center'><i class='fas fa-suitcase fa-fw fa-lg'></i> إلغاء الموافقة على الدورة</h1>";
			echo "<div class='container'>";

				// Check If Get Request Course ID Is Numeric & Get The Integer Value Of It

				$courseid = isset($_GET['courseid']) && is_numeric($_GET['courseid']) ? intval($_GET['courseid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('Course_ID', 'courses', $courseid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE courses SET Approve = 0 WHERE Course_ID = ?");

					$stmt->execute(array($courseid));

					$theMsg = '<div class="alert alert-success text-center">تم إلغاء الموافقة على الدورة بنجاح !</div>';

					redirectHome($theMsg, 'back');

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