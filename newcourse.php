<?php
	ob_start();
	session_start();
	$pageTitle = 'إنشاء دورة جديدة';
	include 'initialization.php';
	if (isset($_SESSION['user']) && $_SESSION['ugroup'] != 3) {

		if ($_SESSION['ustatus'] == 1) {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$formErrors = array();

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

				// Document Upload Variables

				$documentName	= $_FILES['document']['name'];
				$documentSize	= $_FILES['document']['size'];
				$documentTmp	= $_FILES['document']['tmp_name'];
				$documentType	= $_FILES['document']['type'];

				// List Of Allowed File Typed To Upload

				$documentAllowedExtension = array("pdf", "docx", "ppt", "xlsx");

				// Get Document Extension

				$docTmp = explode('.', $documentName);

				$documentExtension = strtolower(end($docTmp));

				// Document Cover Upload Variables

				$docCoverName	= $_FILES['doc-cover']['name'];
				$docCoverSize	= $_FILES['doc-cover']['size'];
				$docCoverTmp	= $_FILES['doc-cover']['tmp_name'];
				$docCoverType	= $_FILES['doc-cover']['type'];

				// List Of Allowed File Typed To Upload

				$docCoverAllowedExtension = array("jpeg", "jpg", "png", "gif");

				// Get Document Cover Extension

				$DocCoverTmp = explode('.', $docCoverName);

				$docCoverExtension = strtolower(end($DocCoverTmp));

				$title 		= filter_var($_POST['title'], FILTER_SANITIZE_STRING);
				$desc 		= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
				$section 	= $_POST['section'];
				$band 		= $_POST['band'];
				$docTitle 	= $_POST['doc-title'];

				if (strlen($title) < 4) {

					$formErrors[] = 'Course Title Must Be At Least 4 Characters';

				}

				if (strlen($desc) < 10) {

					$formErrors[] = 'Course Description Must Be At Least 10 Characters';

				}

				if (strlen($docTitle) < 10) {

					$formErrors[] = 'Document Title Must Be At Least 10 Characters';

				}

				if ($section == 0) {

					$formErrors[] = 'Section Cant Be Empty';

				}

				if ($band == 0) {

					$formErrors[] = 'Band Cant Be Empty';

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

				if (! empty($documentName) && ! in_array($documentExtension, $documentAllowedExtension)) {

					$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';

				}

				if (empty($documentName)) {

					$formErrors[] = 'Document Is <strong>Required</strong>';

				}

				if ($documentSize > 10485760) {

					$formErrors[] = 'Document Cant Be Larger Than <strong>10MB</strong>';

				}

				if (! empty($docCoverName) && ! in_array($docCoverExtension, $docCoverAllowedExtension)) {

					$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';

				}

				if (empty($docCoverName)) {

					$formErrors[] = 'Document Cover Is <strong>Required</strong>';

				}

				if ($docCoverSize > 4194304) {

					$formErrors[] = 'Document Cover Cant Be Larger Than <strong>4MB</strong>';

				}


				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					$image = rand(0, 1000000) . '_' . $imageName;

					move_uploaded_file($imageTmp, "AdminDashboard\uploads\CoursesImages\\" . $image);



					$document = rand(0, 1000000) . '_' . $documentName;

					move_uploaded_file($documentTmp, "AdminDashboard\uploads\Books\\" . $document);



					$docCover = rand(0, 1000000) . '_' . $docCoverName;

					move_uploaded_file($docCoverTmp, "AdminDashboard\uploads\Books\\" . $docCover);



					// Insert Courseinfo In Database

					$stmt = $con->prepare("INSERT INTO 

						courses(Title, Description, Create_Date, Image, Approve, Section_ID, Band_ID, Teacher_ID)

						VALUES(:ztitle, :zdesc, now(), :zimage, 0, :zsection, :zband, :zteacher)");

					$stmt->execute(array(

						'ztitle' 	=> $title,
						'zdesc' 	=> $desc,
						'zimage' 	=> $image,
						'zsection' 	=> $section,
						'zband' 	=> $band,
						'zteacher'	=> $_SESSION['uid']

					));

					$stmt2 = $con->prepare("SELECT Course_ID FROM courses WHERE Title = ? AND Description = ?");

					$stmt2->execute(array($title, $desc));

					$course = $stmt2->fetch();

					$courseid = $course['Course_ID'];

					// Insert Documentinfo In Database

					$stmt3 = $con->prepare("INSERT INTO 

						books(Book_Title, Book_Cover, Book_Source, Create_Date, Course_ID)

						VALUES(:ztitle, :zdocCover, :zdocument, now(), :zcourse)");

					$stmt3->execute(array(

						'ztitle' 	=> $title,
						'zdocCover' => $docCover,
						'zdocument' => $document,
						'zcourse' 	=> $courseid

					));

					// Echo Success Message

					if ($stmt) {

						$succesMsg = 'تم إضافة الدورة بنجاح ! فى إنتظار الموافقة لكى تتمكن من إضافة الفيديوهات .';
						
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
								<label class="col-sm-3 control-label">عنوان الدورة</label>
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
							<!-- Start Description Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">وصف المحتوى</label>
								<div class="col-sm-10 col-md-9">
									<input 
										pattern=".{10,}"
										title="This Field Require At Least 10 Characters"
										type="text" 
										name="description" 
										class="form-control live"   
										placeholder="" 
										data-class=".live-desc"
										required />
								</div>
							</div>
							<!-- End Description Field -->
							<!-- Start Image Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">غلاف الدورة</label>
								<div class="col-sm-10 col-md-9">
									<input type="file" name="image" id="image-upload" class="form-control" required />
								</div>
							</div>
							<!-- End Image Field -->
							<!-- Start Document Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">الملف المرفق</label>
								<div class="col-sm-10 col-md-9">
									<input type="file" name="document" class="form-control" required />
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
										required />
								</div>
							</div>
							<!-- End Document Title Field -->
							<!-- Start Document Cover Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">غلاف الملف</label>
								<div class="col-sm-10 col-md-9">
									<input type="file" name="doc-cover" class="form-control" required />
								</div>
							</div>
							<!-- End Document Cover Field -->
							<!-- Start Sections Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-3 control-label">القسم</label>
								<div class="col-sm-10 col-md-9">
									<select name="section">
										<option value="0">...</option>
										<?php
											$stmt = $con->prepare("SELECT * FROM sections WHERE Allow_Courses = 1");
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
							<!-- Start Submit Field -->
							<div class="form-group form-group-lg">
								<div class="col-sm-offset-3 col-sm-9">
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
				<!-- Start Loopiong Through Errors -->
				<?php 
					if (! empty($formErrors)) {
						foreach ($formErrors as $error) {
							echo '<div class="alert alert-danger text-center">' . $error . '</div>';
						}
					}
					if (isset($succesMsg)) {
						echo '<div class="alert alert-success text-center">' . $succesMsg . '</div>';
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

			$theMsg = '<div class="alert alert-danger text-center">Sorry You Can\'t Add Courses Waiting For Account Activation</div>';

			redirectHome($theMsg, 'back');

			echo "</div>";

		}

	} else {
		header('Location: index.php');
		exit();
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>