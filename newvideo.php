<?php
	ob_start();
	session_start();
	$pageTitle = 'إضافة فيديو جديد';
	include 'initialization.php';
	if (isset($_SESSION['user']) && $_SESSION['ugroup'] != 3) {

		if ($_SESSION['ustatus'] == 1) {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$formErrors = array();

				// Image Upload Variables

				$imageName	= $_FILES['image']['name'];
				$imageSize	= $_FILES['image']['size'];
				$imageTmp	= $_FILES['image']['tmp_name'];
				$imageType	= $_FILES['image']['type'];

				// List Of Allowed File Typed To Upload

				$imageAllowedExtension = array("jpeg", "jpg", "png", "gif");

				// Get Image Extension

				$imgTmp = explode('.', $imageName);

				$imageExtension = strtolower(end($imgTmp));



				// Video Upload Variables

				$videoName	= $_FILES['video']['name'];
				$videoSize	= $_FILES['video']['size'];
				$videoTmp	= $_FILES['video']['tmp_name'];
				$videoType	= $_FILES['video']['type'];

				// List Of Allowed File Typed To Upload

				$videoAllowedExtension = array("mp4", "avi", "flv", "wmv", "mov");

				// Get Video Extension

				$tmp = explode('.', $videoName);

				$videoExtension = strtolower(end($tmp));

				$title 		= filter_var($_POST['title'], FILTER_SANITIZE_STRING);
				$course 	= filter_var($_POST['course'], FILTER_SANITIZE_NUMBER_INT);

				if (strlen($title) < 4) {

					$formErrors[] = 'Video Title Must Be At Least 4 Characters';

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

				if (! empty($videoName) && ! in_array($videoExtension, $videoAllowedExtension)) {

					$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';

				}

				if (empty($videoName)) {

					$formErrors[] = 'Video Is <strong>Required</strong>';

				}

				if ($videoSize > 52428800) {

					$formErrors[] = 'Video Cant Be Larger Than <strong>50MB</strong>';

				}

				if ($course == 0) {

					$formErrors[] = 'Course Cant Be Empty';

				}


				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {


					$image = rand(0, 1000000) . '_' . $imageName;

					move_uploaded_file($imageTmp, "AdminDashboard\uploads\CoursesVideos\\" . $image);


					$video = rand(0, 1000000) . '_' . $videoName;

					move_uploaded_file($videoTmp, "AdminDashboard\uploads\CoursesVideos\\" . $video);


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

					if ($stmt) {

						$succesMsg = 'Video Has Been Added';
						
					}

				}

			}


?>
<h1 class="text-center"><?php echo $pageTitle ?></h1>
<div class="create-ad block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading"><?php echo $pageTitle ?></div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-8">
						<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
							<!-- Start Title Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">عنوان الفيديو</label>
								<div class="col-sm-10 col-md-9">
									<input 
										pattern=".{4,}"
										title="This Field Require At Least 4 Characters"
										type="text" 
										name="title" 
										class="form-control live"  
										placeholder=""
										data-class=".live-title"
										required />
								</div>
							</div>
							<!-- End Title Field -->
							<!-- Start Image Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">غلاف الفيديو</label>
								<div class="col-sm-10 col-md-9">
									<input type="file" name="image" id="image-upload" class="form-control" required />
								</div>
							</div>
							<!-- End Image Field -->
							<!-- Start Video Source Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">ملف الفيديو</label>
								<div class="col-sm-10 col-md-9">
									<input type="file" name="video" id="video-upload" class="form-control" required />
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
											$stmt = $con->prepare("SELECT courses.*, band.Band_Name FROM courses INNER JOIN band ON band.Band_ID = courses.Band_ID WHERE Teacher_ID = ?");
											$stmt->execute(array($_SESSION['uid']));
											$courses = $stmt->fetchAll();
											foreach ($courses as $course) {
												echo "<option value='" . $course['Course_ID'] . "'>" . $course['Title'] . " | " . $course['Band_Name'] . "</option>";
											}
										?>
									</select>
								</div>
							</div>
							<!-- End Courses Field -->
							<!-- Start Submit Field -->
							<div class="form-group form-group-lg">
								<div class="col-sm-offset-3 col-sm-9">
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
				<!-- Start Loopiong Through Errors -->
				<?php 
					if (! empty($formErrors)) {
						foreach ($formErrors as $error) {
							echo '<div class="alert alert-danger">' . $error . '</div>';
						}
					}
					if (isset($succesMsg)) {
						echo '<div class="alert alert-success">' . $succesMsg . '</div>';
					}
				?>
				<!-- End Loopiong Through Errors -->
			</div>
		</div>
	</div>
</div>
<?php

		} else {

			echo "<div class='container'>";

			$theMsg = '<div class="alert alert-danger">Sorry You Can\'t Add Videos Waiting For Account Activation</div>';

			redirectHome($theMsg, 'back');

			echo "</div>";

		}

	} else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>