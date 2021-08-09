<?php

	/*
	================================================
	== Books Page
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Books';

	if (isset($_SESSION['Username'])) {

		include 'initialization.php';

		$action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

		if ($action == 'Manage') {


			$stmt = $con->prepare("SELECT 
										books.*, 
										courses.Title AS course_title 
									FROM 
										books
									INNER JOIN 
										courses 
									ON 
										courses.Course_ID = books.Course_ID 
									ORDER BY 
										Book_ID DESC");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$books = $stmt->fetchAll();

			if (! empty($books)) {

			?>

			<h1 class="text-center"><i class="far fa-books fa-fw fa-lg"></i> إدارة الكتب</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table manage-members text-center table table-bordered">
						<tr>
							<td>ID</td>
							<td>عنوان الكتاب</td>
							<td>غلاف الكتاب</td>
							<td>ملف الكتاب</td>
							<td>تاريخ الإضافة</td>
							<td>الدورة</td>
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
									echo "<td>" . $book['course_title'] ."</td>";
									echo "<td>
										<a href='books.php?action=Edit&bookid=" . $book['Book_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> تحديث</a>
										<a href='books.php?action=Delete&bookid=" . $book['Book_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-times'></i> حذف</a>";
									echo "</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
				<a href="books.php?action=Add" class="btn btn-lg btn-primary">
					<i class="far fa-plus-square fa-fw fa-lg"></i> إضافة كتاب جديد
				</a>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">لا يوجد كتب لعرضها</div>';
					echo '<a href="books.php?action=Add" class="btn btn-lg btn-primary">
							<i class="far fa-plus-square fa-fw fa-lg"></i> إضافة كتاب جديد
						</a>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($action == 'Add') { ?>

			<h1 class="text-center"><i class="far fa-books fa-fw fa-lg"></i> إضافة كتاب جديد</h1>
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading">إضافة كتاب جديد</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-8">
								<form class="form-horizontal" action="?action=Insert" method="POST" enctype="multipart/form-data">
									<!-- Start Title Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">عنوان الكتاب</label>
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
									<!-- Start Book Cover Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">غلاف الكتاب</label>
										<div class="col-sm-10 col-md-9">
											<input type="file" name="cover" id="image-upload" class="form-control" required="required" />
										</div>
									</div>
									<!-- End Book Cover Field -->
									<!-- Start PDF File Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">ملف الكتاب</label>
										<div class="col-sm-10 col-md-9">
											<input type="file" name="pdf" class="form-control" required="required" />
										</div>
									</div>
									<!-- End PDF File Field -->
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
											<input type="submit" value="إضافة كتاب" class="btn btn-primary btn-lg form-control" />
										</div>
									</div>
									<!-- End Submit Field -->
								</form>
							</div>
							<div class="col-md-4">
								<div class="thumbnail item-box live-preview">
									<img id="image-preview" class="img-responsive" src="<?php echo $images; ?>img.png" alt="" />
									<div class="caption text-center">
										<h3 class="live-title">عنوان الكتاب</h3>
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

				echo "<h1 class='text-center'><i class='far fa-books fa-fw fa-lg'></i> إضافة كتاب</h1>";
				echo "<div class='container'>";

				// Get Variables From The Form

				$title		= $_POST['title'];
				$course 	= $_POST['course'];


				// Cover Upload Variables

				$coverName	= $_FILES['cover']['name'];
				$coverSize	= $_FILES['cover']['size'];
				$coverTmp	= $_FILES['cover']['tmp_name'];
				$coverType	= $_FILES['cover']['type'];

				// List Of Allowed File Typed To Upload

				$coverAllowedExtension = array("jpeg", "jpg", "png", "gif");

				// Get Cover Extension

				$covTmp = explode('.', $coverName);

				$coverExtension = strtolower(end($covTmp));



				// PDF File Upload Variables

				$pdfName	= $_FILES['pdf']['name'];
				$pdfSize	= $_FILES['pdf']['size'];
				$pdfTmp		= $_FILES['pdf']['tmp_name'];
				$pdfType	= $_FILES['pdf']['type'];

				// List Of Allowed File Typed To Upload

				$pdfAllowedExtension = array("pdf");

				// Get PDF File Extension

				$tmp = explode('.', $pdfName);

				$pdfExtension = strtolower(end($tmp));


				// Validate The Form

				$formErrors = array();

				if (empty($title)) {
					$formErrors[] = 'عنوان الكتاب يجب ألا يكون <strong>فارغاً</strong>';
				}

				if (! empty($coverName) && ! in_array($coverExtension, $coverAllowedExtension)) {
					$formErrors[] = 'إمتداد صورة الغلاف هذا <strong>غير مسموح به</strong>';
				}

				if (empty($coverName)) {
					$formErrors[] = 'صورة الغلاف <strong>مطلوبة</strong>';
				}

				if ($coverSize > 4194304) {

					$formErrors[] = 'يجب ألا يكون حجم صورة الغلاف <strong>أكبر من 4 ميغابايت</strong>';

				}

				if (! empty($pdfName) && ! in_array($pdfExtension, $pdfAllowedExtension)) {
					$formErrors[] = 'إمتداد ملف الكتاب هذا <strong>غير مسموح به</strong>';
				}

				if (empty($pdfName)) {
					$formErrors[] = 'ملف الكتاب <strong>مطلوب</strong>';
				}

				if ($pdfSize > 10485760) {
					$formErrors[] = 'يجب ألا يكون حجم ملف الكتاب <strong>أكبر من 10 ميغابايت</strong>';
				}

				if ($course == 0) {
					$formErrors[] = 'يجب عليك أن تختار <strong>القسم</strong>';
				}

				// Loop Into Errors Array And Echo It

				foreach($formErrors as $error) {
					echo '<div class="alert alert-danger text-center">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {


					$cover = rand(0, 1000000) . '_' . $coverName;

					move_uploaded_file($coverTmp, "uploads\Books\\" . $cover);


					$pdf = rand(0, 1000000) . '_' . $pdfName;

					move_uploaded_file($pdfTmp, "uploads\Books\\" . $pdf);


					// Insert Userinfo In Database

					$stmt = $con->prepare("INSERT INTO 

						books(Book_Title, Book_Cover, Book_Source, Create_Date, Course_ID)

						VALUES(:ztitle, :zcover, :zpdf, now(), :zcourse)");

					$stmt->execute(array(

						'ztitle' 	=> $title,
						'zcover' 	=> $cover,
						'zpdf' 		=> $pdf,
						'zcourse'	=> $course

					));

					// Echo Success Message

					$theMsg = '<div class="alert alert-success text-center">تم إضافة الكتاب بنجاح !</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger text-center">نآسف لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg);

				echo "</div>";

			}

			echo "</div>";

		} elseif ($action == 'Edit') {

			// Check If Get Request item Is Numeric & Get Its Integer Value

			$bookid = isset($_GET['bookid']) && is_numeric($_GET['bookid']) ? intval($_GET['bookid']) : 0;

			// Select All Data Depend On This ID

			$stmt = $con->prepare("SELECT * FROM books WHERE Book_ID = ?");

			// Execute Query

			$stmt->execute(array($bookid));

			// Fetch The Data

			$book = $stmt->fetch();

			// The Row Count

			$count = $stmt->rowCount();

			// If There's Such ID Show The Form

			if ($count > 0) { ?>

				<h1 class="text-center"><i class="far fa-books fa-fw fa-lg"></i> تحديث الكتاب</h1>
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading">تحديث الكتاب</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-8">
									<form class="form-horizontal" action="?action=Update" method="POST" enctype="multipart/form-data">
										<input type="hidden" name="bookid" value="<?php echo $bookid ?>" />
										<!-- Start Document Title Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">عنوان الكتاب</label>
											<div class="col-sm-10 col-md-9">
												<input 
													type="text" 
													name="title" 
													class="form-control live" 
													required="required"  
													placeholder=""
													data-class=".live-title"
													value="<?php echo $book['Book_Title'] ?>" />
											</div>
										</div>
										<!-- End Document Title Field -->
										<!-- Start Document Cover Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">غلاف الكتاب</label>
											<div class="col-sm-10 col-md-9">
												<input type="hidden" name="olddoc-cover" value="<?php echo $book['Book_Cover'] ?>" />
												<input type="file" name="newdoc-cover" class="form-control" />
											</div>
										</div>
										<!-- End Document Cover Field -->
										<!-- Start Document Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">ملف الكتاب</label>
											<div class="col-sm-10 col-md-9">
												<input type="hidden" name="olddocument" value="<?php echo $book['Book_Source'] ?>" />
												<input type="file" name="newdocument" class="form-control" />
											</div>
										</div>
										<!-- End Document Field -->
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
															if ($book['Course_ID'] == $course['Course_ID']) { echo 'selected'; } 
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
												<input type="submit" value="تحديث الكتاب" class="btn btn-primary btn-lg form-control" />
											</div>
										</div>
										<!-- End Submit Field -->
									</form>
								</div>
								<div class="col-md-4">
									<div class="thumbnail item-box live-preview">
										<img id="image-preview" class="img-responsive" src="<?php if (! empty($book['Book_Cover'])) {echo 'uploads\Books\\' . $book['Book_Cover'];} else {echo $images . 'img.png';} ?>" alt="" />
										<div class="caption text-center">
											<h3 class="live-title"><?php echo $book['Book_Title'] ?></h3>
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

		} elseif ($action == 'Update') {

			echo "<h1 class='text-center'><i class='far fa-books fa-fw fa-lg'></i> تحديث الكتاب</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$id 	= $_POST['bookid'];
				$title 	= $_POST['title'];
				$course = $_POST['course'];


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


				// Validate The Form

				$formErrors = array();

				if (empty($title)) {
					$formErrors[] = 'عنوان الكتاب يجب ألا يكون <strong>فارغاً</strong>';
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
					echo '<div class="alert alert-danger text-center">' . $error . '</div>';
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					if (! empty($_FILES['newdocument']['name'])) {

						$document = rand(0, 1000000) . '_' . $documentName;

						move_uploaded_file($documentTmp, "uploads\Books\\" . $document);

					}

					if (! empty($_FILES['newdoc-cover']['name'])) {

						$docCover = rand(0, 1000000) . '_' . $docCoverName;

						move_uploaded_file($docCoverTmp, "uploads\Books\\" . $docCover);

					}

					// Update The Database With This Info

					$stmt = $con->prepare("UPDATE 
												books 
											SET 
												Book_Title = ?,
												Book_Cover = ?,
												Book_Source = ?,
												Course_ID = ?
											WHERE 
												Book_ID = ?");

					$stmt->execute(array($title, $docCover, $document, $course, $id));

					// Echo Success Message

					$theMsg = '<div class="alert alert-success text-center">تم تحديث الكتاب بنجاح !</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				$theMsg = '<div class="alert alert-danger text-center">نآسف لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($action == 'Delete') {

			echo "<h1 class='text-center'><i class='far fa-books fa-fw fa-lg'></i> حذف الكتاب</h1>";
			echo "<div class='container'>";

				// Check If Get Request Course ID Is Numeric & Get The Integer Value Of It

				$bookid = isset($_GET['bookid']) && is_numeric($_GET['bookid']) ? intval($_GET['bookid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('Book_ID', 'books', $bookid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM books WHERE Book_ID = :zid");

					$stmt->bindParam(":zid", $bookid);

					$stmt->execute();

					$theMsg = '<div class="alert alert-success text-center">تم حذف الكتاب بنجاح !</div>';

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