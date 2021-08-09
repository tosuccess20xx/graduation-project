<?php

	/*
	================================================
	== Manage Comments Page
	== You Can Edit | Delete | Approve Comments From Here
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Comments';

	if (isset($_SESSION['Username'])) {

		include 'initialization.php';

		$action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

		// Start Manage Page

		if ($action == 'Manage') { // Manage Comments Page

			// Select All Comments

			$stmt = $con->prepare("SELECT 
										comments.*, 
										courses_Videos.Title AS Video_Name, 
										users.FullName 
									FROM 
										comments
									INNER JOIN 
										courses_Videos 
									ON 
										courses_Videos.Video_ID = comments.Video_ID
									INNER JOIN 
										users 
									ON 
										users.UserID = comments.User_ID
									ORDER BY 
										Comment_ID DESC");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$comments = $stmt->fetchAll();

			if (! empty($comments)) {

			?>

			<h1 class="text-center"><i class="far fa-comments fa-fw fa-lg"></i> إدارة التعليقات</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>ID</td>
							<td>التعليق</td>
							<td>عنوان الفيديو</td>
							<td>أسم المستخدم</td>
							<td>تاريخ الإضافة</td>
							<td>التحكم</td>
						</tr>
						<?php
							foreach($comments as $comment) {
								echo "<tr>";
									echo "<td>" . $comment['Comment_ID'] . "</td>";
									echo "<td>" . $comment['Comment_Text'] . "</td>";
									echo "<td>" . $comment['Video_Name'] . "</td>";
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
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">لا يوجد تعليقات لعرضها</div>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($action == 'Edit') {

			// Check If Get Request comid Is Numeric & Get Its Integer Value

			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

			// Select All Data Depend On This ID

			$stmt = $con->prepare("SELECT * FROM comments WHERE Comment_ID = ?");

			// Execute Query

			$stmt->execute(array($comid));

			// Fetch The Data

			$row = $stmt->fetch();

			// The Row Count

			$count = $stmt->rowCount();

			// If There's Such ID Show The Form

			if ($count > 0) { ?>

				<h1 class="text-center"><i class="far fa-comments fa-fw fa-lg"></i> تحديث التعليق</h1>
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading">تحديث التعليق</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<form class="form-horizontal" action="?action=Update" method="POST">
										<input type="hidden" name="comid" value="<?php echo $comid ?>" />
										<!-- Start Comment Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-1 control-label">التعليق</label>
											<div class="col-sm-10 col-md-8">
												<textarea class="form-control" name="comment"><?php echo $row['Comment_Text'] ?></textarea>
											</div>
										</div>
										<!-- End Comment Field -->
										<!-- Start Submit Field -->
										<div class="form-group form-group-lg">
											<div class="col-sm-offset-1 col-sm-10">
												<input type="submit" value="تحديث التعليق" class="btn btn-primary" />
											</div>
										</div>
										<!-- End Submit Field -->
									</form>
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

			echo "<h1 class='text-center'><i class='far fa-comments fa-fw fa-lg'></i> تحديث التعليق</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$comid 		= $_POST['comid'];
				$comment 	= $_POST['comment'];

				// Update The Database With This Info

				$stmt = $con->prepare("UPDATE comments SET Comment_Text = ? WHERE Comment_ID = ?");

				$stmt->execute(array($comment, $comid));

				// Echo Success Message

				$theMsg = '<div class="alert alert-success text-center">تم تحديث التعليق بنجاح !</div>';

				redirectHome($theMsg, 'back');

			} else {

				$theMsg = '<div class="alert alert-danger text-center">نآسف لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($action == 'Delete') { // Delete Page

			echo "<h1 class='text-center'><i class='far fa-comments fa-fw fa-lg'></i> حذف التعليق</h1>";

			echo "<div class='container'>";

				// Check If Get Request comid Is Numeric & Get The Integer Value Of It

				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('Comment_ID', 'comments', $comid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM comments WHERE Comment_ID = :zid");

					$stmt->bindParam(":zid", $comid);

					$stmt->execute();

					$theMsg = '<div class="alert alert-success text-center">تم حذف التعليق بنجاح !</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger text-center">هذا المُعرف غير موجود</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} elseif ($action == 'Approve') {

			echo "<h1 class='text-center'><i class='far fa-comments fa-fw fa-lg'></i> الموافقة على التعليق</h1>";
			echo "<div class='container'>";

				// Check If Get Request comid Is Numeric & Get The Integer Value Of It

				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('Comment_ID', 'comments', $comid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE Comment_ID = ?");

					$stmt->execute(array($comid));

					$theMsg = '<div class="alert alert-success text-center">تم الموافقة على التعليق بنجاح !</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="alert alert-danger text-center">هذا المُعرف غير موجود</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} elseif ($action == 'UnApprove') {

			echo "<h1 class='text-center'><i class='far fa-comments fa-fw fa-lg'></i> إلغاء الموافقة على التعليق</h1>";
			echo "<div class='container'>";

				// Check If Get Request comid Is Numeric & Get The Integer Value Of It

				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('Comment_ID', 'comments', $comid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE comments SET Status = 0 WHERE Comment_ID = ?");

					$stmt->execute(array($comid));

					$theMsg = '<div class="alert alert-success text-center text-center">تم إلغاء الموافقة على التعليق بنجاح !</div>';

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