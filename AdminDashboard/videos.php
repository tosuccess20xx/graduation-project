<?php

	/*
	================================================
	== Videos Page
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Videos';

	if (isset($_SESSION['Username'])) {

		include 'initialization.php';

		$action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

		if ($action == 'Manage') {


			$stmt = $con->prepare("SELECT 
										courses_videos.*, 
										courses.Title AS course_name 
									FROM 
										courses_videos
									INNER JOIN 
										courses 
									ON 
										courses.Course_ID = courses_videos.Course_ID 
									ORDER BY 
										Video_ID DESC");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$videos = $stmt->fetchAll();

			if (! empty($videos)) {

			?>

			<h1 class="text-center"><i class="fas fa-film fa-fw fa-lg"></i> إدارة الفيديوهات</h1>
			<div class="container">
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
				<a href="videos.php?action=Add" class="btn btn-lg btn-primary">
					<i class="fas fa-video-plus fa-fw fa-lg"></i> إضافة فيديو جديد
				</a>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">لا يوجد فيديوهات لعرضها</div>';
					echo '<a href="videos.php?action=Add" class="btn btn-lg btn-primary">
							<i class="fas fa-video-plus fa-fw fa-lg"></i> فيديو جديد
						</a>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($action == 'Add') { ?>

			<h1 class="text-center"><i class="fas fa-film fa-fw fa-lg"></i> إضافة فيديو جديد</h1>
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading">إضافة فيديو جديد</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-8">
								<form class="form-horizontal" action="?action=Insert" method="POST" enctype="multipart/form-data">
									<!-- Start Title Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">عنوان الفيديو</label>
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
									<!-- Start Image Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">غلاف الفيديو</label>
										<div class="col-sm-10 col-md-9">
											<input type="file" name="image" id="image-upload" class="form-control" required="required" />
										</div>
									</div>
									<!-- End Image Field -->
									<!-- Start Video Source Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">ملف الفيديو</label>
										<div class="col-sm-10 col-md-9">
											<input type="file" name="video" id="video-upload" class="form-control" required="required" />
										</div>
									</div>
									<!-- End Video Source Field -->
									<!-- Start Courses Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">الدورة</label>
										<div class="col-sm-10 col-md-9">
											<select name="course">
												<option value="0">...</option>
												<?php
													$stmt = $con->prepare("SELECT courses.*, sections.Name AS section_name, band.Band_Name FROM courses INNER JOIN sections ON sections.ID = courses.Section_ID INNER JOIN band ON band.Band_ID = courses.Band_ID");
													$stmt->execute();
													$courses = $stmt->fetchAll();
													foreach ($courses as $course) {
														echo "<option value='" . $course['Course_ID'] . "'>" . $course['Title'] . " | قسم " . $course['section_name'] . " | " . $course['Band_Name'] . "</option>";
													}
												?>
											</select>
										</div>
									</div>
									<!-- End Courses Field -->
									<!-- Start Submit Field -->
									<div class="form-group form-group-lg">
										<div class="col-sm-offset-3 col-md-9">
											<input type="submit" value="إضافة فيديو" class="btn btn-primary btn-lg form-control" />
										</div>
									</div>
									<!-- End Submit Field -->
								</form>
							</div>
							<div class="col-md-4">
								<div class="thumbnail item-box live-preview">
									<img id="image-preview" class="img-responsive" src="<?php echo $images; ?>img.png" alt="" />
									<video width="100%" height="240" controls autoplay>
										<source id="video-preview" src="#" type="video/mp4">
										Your browser does not support HTML5 video.
									</video>
									<div class="caption">
										<h3 class="live-title">عنوان الفيديو</h3>
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

				echo "<h1 class='text-center'><i class='fas fa-film fa-fw fa-lg'></i> إضافة فيديو</h1>";
				echo "<div class='container'>";

				// Get Variables From The Form

				$title	= $_POST['title'];
				$course = $_POST['course'];


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


				// Validate The Form

				$formErrors = array();

				if (empty($title)) {
					$formErrors[] = 'عنوان الفيديو يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (! empty($imageName) && ! in_array($imageExtension, $imageAllowedExtension)) {
					$formErrors[] = 'إمتداد صورة الغلاف هذا <strong>غير مسموح به</strong>';
				}

				if (empty($imageName)) {
					$formErrors[] = 'صورة الغلاف <strong>مطلوبة</strong>';
				}

				if ($imageSize > 4194304) {

					$formErrors[] = 'يجب ألا يكون حجم صورة الغلاف <strong>أكبر من 4 ميغابايت</strong>';

				}

				if (! empty($videoName) && ! in_array($videoExtension, $videoAllowedExtension)) {
					$formErrors[] = 'إمتداد ملف الفيديو هذا <strong>غير مسموح به</strong>';
				}

				if (empty($videoName)) {
					$formErrors[] = 'ملف الفيديو <strong>مطلوب</strong>';
				}

				if ($videoSize > 52428800) {
					$formErrors[] = 'يجب ألا يكون حجم الفيديو <strong>أكبر من 50 ميغابايت</strong>';
				}

				if ($course == 0) {
					$formErrors[] = 'يجب أن تختار الدورة المُضاف إليها الفيديو';
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger text-center">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {


					$image = rand(0, 1000000) . '_' . $imageName;

					move_uploaded_file($imageTmp, "uploads\CoursesVideos\\" . $image);


					$video = rand(0, 1000000) . '_' . $videoName;

					move_uploaded_file($videoTmp, "uploads\CoursesVideos\\" . $video);


					// Insert Userinfo In Database

					$stmt = $con->prepare("INSERT INTO 

						courses_videos(Title, Image, Video_Source, Create_Date, Course_ID)

						VALUES(:ztitle, :zimage, :zvideo, now(), :zcourse)");

					$stmt->execute(array(

						'ztitle' 	=> $title,
						'zimage' 	=> $image,
						'zvideo' 	=> $video,
						'zcourse' 	=> $course

					));

					// Echo Success Message

					$theMsg = '<div class="alert alert-success text-center">تم إضافة الفيديو بنجاح !</div>';

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

				<h1 class="text-center"><i class="fas fa-film fa-fw fa-lg"></i> تحديث الفيديو</h1>
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading">تحديث الفيديو</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-8">
									<form class="form-horizontal" action="?action=Update" method="POST" enctype="multipart/form-data">
										<input type="hidden" name="videoid" value="<?php echo $videoid ?>" />
										<!-- Start Title Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">عنوان الفيديو</label>
											<div class="col-sm-10 col-md-9">
												<input 
													type="text" 
													name="title" 
													class="form-control live" 
													required="required"  
													placeholder=""
													data-class=".live-title"
													value="<?php echo $video['Title'] ?>" />
											</div>
										</div>
										<!-- End Title Field -->
										<!-- Start Image Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">غلاف الفيديو</label>
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
														$stmt = $con->prepare("SELECT courses.*, sections.Name AS section_name, band.Band_Name FROM courses INNER JOIN sections ON sections.ID = courses.Section_ID INNER JOIN band ON band.Band_ID = courses.Band_ID");
														$stmt->execute();
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
										<img id="image-preview" class="img-responsive" src="uploads\CoursesVideos\<?php echo $video['Image']; ?>" alt="" />
										<video width="100%" height="240" controls autoplay>
											<source id="video-preview" src="uploads\CoursesVideos\<?php echo $video['Video_Source'] ?>" type="video/mp4">
											Your browser does not support HTML5 video.
										</video>
										<div class="caption">
											<h3 class="live-title"><?php echo $video['Title'] ?></h3>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading"><i class="far fa-comments fa-fw fa-lg"></i> إدارة تعليقات الفيديو</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<?php

									// Select All Comments

									$stmt = $con->prepare("SELECT 
																comments.*, 
																users.FullName 
															FROM 
																comments 
															INNER JOIN 
																users 
															ON 
																users.UserID = comments.User_ID 
															WHERE Video_ID = ?");

									// Execute The Statement

									$stmt->execute(array($videoid));

									// Assign To Variable 

									$comments = $stmt->fetchAll();

									if (! empty($comments)) {
										
									?>
									<div class="table-responsive">
										<table class="main-table text-center table table-bordered">
											<tr>
												<td>التعليق</td>
												<td>أسم المستخدم</td>
												<td>تاريخ الإضافة</td>
												<td>التحكم</td>
											</tr>
											<?php
												foreach($comments as $comment) {
													echo "<tr>";
														echo "<td>" . $comment['Comment_Text'] . "</td>";
														echo "<td>" . $comment['FullName'] . "</td>";
														echo "<td>" . $comment['Comment_Date'] ."</td>";
														echo "<td>
															<a href='comments.php?action=Edit&comid=" . $comment['Comment_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> تحديث</a>
															<a href='comments.php?action=Delete&comid=" . $comment['Comment_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-times'></i> حذف</a>";
															if ($comment['Status'] == 0) {
																echo "<a href='comments.php?action=Approve&comid="
																		 . $comment['Comment_ID'] . "' 
																		class='btn btn-info activate confirm'>
																		<i class='fa fa-check'></i> موافقة</a>";
															} else {
					                                            echo "<a href='comments.php?action=UnApprove&comid="
																		 . $comment['Comment_ID'] . "' 
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
							  <?php } ?>
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

		} elseif ($action == 'Update') {

			echo "<h1 class='text-center'><i class='fas fa-film fa-fw fa-lg'></i> تحديث الفيديو</h1>";
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
					echo '<div class="alert alert-danger text-center">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					if (! empty($_FILES['newimage']['name'])) {

						$image = rand(0, 1000000) . '_' . $imageName;

						move_uploaded_file($imageTmp, "uploads\CoursesVideos\\" . $image);
					}

					if (! empty($_FILES['newvideo']['name'])) {

						$video = rand(0, 1000000) . '_' . $videoName;

						move_uploaded_file($videoTmp, "uploads\CoursesVideos\\" . $video);
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

					$theMsg = '<div class="alert alert-success text-center">تم تحديث الفيديو بنجاح !</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				$theMsg = '<div class="alert alert-danger text-center">لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($action == 'Delete') {

			echo "<h1 class='text-center'><i class='fas fa-film fa-fw fa-lg'></i> حذف الفيديو</h1>";
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

					$theMsg = '<div class="alert alert-success text-center">تم حذف الفيديو بنجاح !</div>';

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