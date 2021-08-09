<?php

	/*
	================================================
	== Sections Page
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Sections';

	if (isset($_SESSION['Username'])) {

		include 'initialization.php';

		$action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

		if ($action == 'Manage') {

			$sort = 'asc';

			$sort_array = array('asc', 'desc');

			if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

				$sort = $_GET['sort'];

			}

			$stmt = $con->prepare("SELECT * FROM sections ORDER BY Ordering $sort");

			$stmt->execute();

			$sections = $stmt->fetchAll(); 

			if (! empty($sections)) {

			?>

			<h1 class="text-center"><i class="fas fa-users-class fa-fw fa-lg"></i> إدارة الأقسام</h1>
			<div class="container categories">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-edit"></i> الأقسام
						<div class="option pull-right">
							<i class="fa fa-sort"></i> الترتيب: [
							<a class="<?php if ($sort == 'asc') { echo 'active'; } ?>" href="?sort=asc">الأقدم</a> | 
							<a class="<?php if ($sort == 'desc') { echo 'active'; } ?>" href="?sort=desc">الأحدث</a> ]
							<i class="fa fa-eye"></i> طريقة العرض: [
							<span class="active" data-view="full">كامل</span> |
							<span data-view="classic">كلاسيكى</span> ]
						</div>
					</div>
					<div class="panel-body">
						<?php
							foreach($sections as $section) {
								echo "<div class='cat'>";
									echo "<div class='hidden-buttons'>";
										echo "<a href='sections.php?action=Edit&secid=" . $section['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> تحديث</a>";
										echo "<a href='sections.php?action=Delete&secid=" . $section['ID'] . "' class='btn btn-xs btn-danger confirm'><i class='fa fa-times'></i> حذف</a>";
									echo "</div>";
									echo "<h3>" . $section['Name'] . '</h3>';
									echo "<div class='full-view'>";
										echo "<p>"; if($section['Description'] == '') { echo 'هذا القسم لا يوجد لديه وصف'; } else { echo $section['Description']; } echo "</p>";
										if($section['Visibility'] == 0) { echo '<span class="visibility cat-span"><i class="fa fa-eye"></i> مخفى</span>'; } 
										if($section['Allow_Comment'] == 0) { echo '<span class="commenting cat-span"><i class="fa fa-comment-slash"></i> التعليقات مغلقة</span>'; }
										if($section['Allow_Courses'] == 0) { echo '<span class="advertises cat-span"><i class="fa fa-ban"></i> الدورات مغلقة</span>'; }  
									echo "</div>";
								echo "</div>";
								echo "<hr>";
							}
						?>
					</div>
				</div>
				<a class="add-category btn btn-primary btn-lg" href="sections.php?action=Add"><i class="far fa-plus-square fa-fw fa-lg"></i> إضافة قسم جديد</a>
			</div>

			<?php } else {

				echo '<div class="container">';
					echo '<div class="nice-message">لا يوجد أقسام لعرضها</div>';
					echo '<a href="sections.php?action=Add" class="btn btn-primary btn-lg">
							<i class="far fa-plus-square fa-fw fa-lg"></i> قسم جديد
						</a>';
				echo '</div>';

			} ?>

			<?php

		} elseif ($action == 'Add') { ?>

			<h1 class="text-center"><i class="fas fa-users-class fa-fw fa-lg"></i> إضافة قسم جديد</h1>
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading">إضافة قسم</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-8">
								<form class="form-horizontal" action="?action=Insert" method="POST">
									<!-- Start Name Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">الاسم</label>
										<div class="col-sm-10 col-md-9">
											<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="اسم القسم" />
										</div>
									</div>
									<!-- End Name Field -->
									<!-- Start Description Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">الوصف</label>
										<div class="col-sm-10 col-md-9">
											<input type="text" name="description" class="form-control" required="required" placeholder="وصف القسم" />
										</div>
									</div>
									<!-- End Description Field -->
									<!-- Start Ordering Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">الترتيب</label>
										<div class="col-sm-10 col-md-9">
											<input type="text" name="ordering" class="form-control" required="required" placeholder="رقم ترتيب القسم" />
										</div>
									</div>
									<!-- End Ordering Field -->
									<!-- Start Visibility Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">مرئى</label>
										<div class="col-sm-10 col-md-9">
											<div>
												<input id="vis-yes" type="radio" name="visibility" value="1" checked />
												<label for="vis-yes">نعم</label> 
											</div>
											<div>
												<input id="vis-no" type="radio" name="visibility" value="0" />
												<label for="vis-no">لا</label> 
											</div>
										</div>
									</div>
									<!-- End Visibility Field -->
									<!-- Start Commenting Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">السماح بالتعليقات</label>
										<div class="col-sm-10 col-md-9">
											<div>
												<input id="com-yes" type="radio" name="commenting" value="1" checked />
												<label for="com-yes">نعم</label> 
											</div>
											<div>
												<input id="com-no" type="radio" name="commenting" value="0" />
												<label for="com-no">لا</label> 
											</div>
										</div>
									</div>
									<!-- End Commenting Field -->
									<!-- Start Courses Field -->
									<div class="form-group form-group-lg">
										<label class="col-sm-3 control-label">السماح للدورات</label>
										<div class="col-sm-10 col-md-9">
											<div>
												<input id="Courses-yes" type="radio" name="Courses" value="1" checked />
												<label for="Courses-yes">نعم</label> 
											</div>
											<div>
												<input id="Courses-no" type="radio" name="Courses" value="0" />
												<label for="Courses-no">لا</label> 
											</div>
										</div>
									</div>
									<!-- End Courses Field -->
									<!-- Start Submit Field -->
									<div class="form-group form-group-lg">
										<div class="col-sm-offset-3 col-md-9">
											<input type="submit" value="إضافة قسم" class="btn btn-primary btn-lg form-control" />
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

		} elseif ($action == 'Insert') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo "<h1 class='text-center'><i class='fas fa-users-class fa-fw fa-lg'></i> إدراج القسم</h1>";
				echo "<div class='container'>";

				// Get Variables From The Form

				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$order 		= $_POST['ordering'];
				$visible 	= $_POST['visibility'];
				$comment 	= $_POST['commenting'];
				$Courses 	= $_POST['Courses'];

				// Check If Section Exist in Database

				$check = checkItem("Name", "sections", $name);

				if ($check == 1) {

					$theMsg = '<div class="alert alert-danger text-center">نآسف هذا القسم موجود</div>';

					redirectHome($theMsg, 'back');

				} else {

					// Insert Section Info In Database

					$stmt = $con->prepare("INSERT INTO 

						sections(Name, Description, Ordering, Visibility, Allow_Comment, Allow_Courses)

					VALUES(:zname, :zdesc, :zorder, :zvisible, :zcomment, :zCourses)");

					$stmt->execute(array(
						'zname' 	=> $name,
						'zdesc' 	=> $desc,
						'zorder' 	=> $order,
						'zvisible' 	=> $visible,
						'zcomment' 	=> $comment,
						'zCourses'		=> $Courses
					));

					// Echo Success Message

					$theMsg = '<div class="alert alert-success text-center">تم إضافة القسم بنجاح !</div>';

					redirectHome($theMsg, 'back');

				}

			} else {

				echo "<div class='container'>";

				$theMsg = '<div class="alert alert-danger text-center">نآسف لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg, 'back');

				echo "</div>";

			}

			echo "</div>";

		} elseif ($action == 'Edit') {

			// Check If Get Request secid Is Numeric & Get Its Integer Value

			$secid = isset($_GET['secid']) && is_numeric($_GET['secid']) ? intval($_GET['secid']) : 0;

			// Select All Data Depend On This ID

			$stmt = $con->prepare("SELECT * FROM sections WHERE ID = ?");

			// Execute Query

			$stmt->execute(array($secid));

			// Fetch The Data

			$sec = $stmt->fetch();

			// The Row Count

			$count = $stmt->rowCount();

			// If There's Such ID Show The Form

			if ($count > 0) { ?>

				<h1 class="text-center"><i class="fas fa-users-class fa-fw fa-lg"></i> تحديث القسم</h1>
				<div class="container">
					<div class="panel panel-primary">
						<div class="panel-heading">تحديث القسم</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-8">
									<form class="form-horizontal" action="?action=Update" method="POST">
										<input type="hidden" name="secid" value="<?php echo $secid ?>" />
										<!-- Start Name Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">الاسم</label>
											<div class="col-sm-10 col-md-9">
												<input type="text" name="name" class="form-control" required="required" placeholder="اسم القسم" value="<?php echo $sec['Name'] ?>" />
											</div>
										</div>
										<!-- End Name Field -->
										<!-- Start Description Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">الوصف</label>
											<div class="col-sm-10 col-md-9">
												<input type="text" name="description" class="form-control" required="required" placeholder="وصف القسم" value="<?php echo $sec['Description'] ?>" />
											</div>
										</div>
										<!-- End Description Field -->
										<!-- Start Ordering Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">الترتيب</label>
											<div class="col-sm-10 col-md-9">
												<input type="text" name="ordering" class="form-control" required="required" placeholder="رقم ترتيب القسم" value="<?php echo $sec['Ordering'] ?>" />
											</div>
										</div>
										<!-- End Ordering Field -->
										<!-- Start Visibility Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">مرئى</label>
											<div class="col-sm-10 col-md-9">
												<div>
													<input id="vis-yes" type="radio" name="visibility" value="1" <?php if ($sec['Visibility'] == 1) { echo 'checked'; } ?> />
													<label for="vis-yes">نعم</label> 
												</div>
												<div>
													<input id="vis-no" type="radio" name="visibility" value="0" <?php if ($sec['Visibility'] == 0) { echo 'checked'; } ?> />
													<label for="vis-no">لا</label> 
												</div>
											</div>
										</div>
										<!-- End Visibility Field -->
										<!-- Start Commenting Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">السماح بالتعليقات</label>
											<div class="col-sm-10 col-md-9">
												<div>
													<input id="com-yes" type="radio" name="commenting" value="1" <?php if ($sec['Allow_Comment'] == 1) { echo 'checked'; } ?> />
													<label for="com-yes">نعم</label> 
												</div>
												<div>
													<input id="com-no" type="radio" name="commenting" value="0" <?php if ($sec['Allow_Comment'] == 0) { echo 'checked'; } ?> />
													<label for="com-no">لا</label> 
												</div>
											</div>
										</div>
										<!-- End Commenting Field -->
										<!-- Start Courses Field -->
										<div class="form-group form-group-lg">
											<label class="col-sm-3 control-label">السماح للدورات</label>
											<div class="col-sm-10 col-md-9">
												<div>
													<input id="Courses-yes" type="radio" name="Courses" value="1" <?php if ($sec['Allow_Courses'] == 1) { echo 'checked'; } ?>/>
													<label for="Courses-yes">نعم</label> 
												</div>
												<div>
													<input id="Courses-no" type="radio" name="Courses" value="0" <?php if ($sec['Allow_Courses'] == 0) { echo 'checked'; } ?>/>
													<label for="Courses-no">لا</label> 
												</div>
											</div>
										</div>
										<!-- End Courses Field -->
										<!-- Start Submit Field -->
										<div class="form-group form-group-lg">
											<div class="col-sm-offset-3 col-md-9">
												<input type="submit" value="تحديث القسم" class="btn btn-primary btn-lg form-control" />
											</div>
										</div>
										<!-- End Submit Field -->
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading"><i class="fas fa-suitcase fa-fw fa-lg"></i> إدارة دورات القسم</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<?php

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
																WHERE 
																	Section_ID = ?
																ORDER BY 
																	Course_ID DESC");

										// Execute The Statement

										$stmt->execute(array($secid));

										// Assign To Variable 

										$courses = $stmt->fetchAll();

										if (! empty($courses)) {

										?>

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

										<?php } else {

											echo '<div class="container">';
												echo '<div class="nice-message">لا يوجد دورات لعرضها</div>';
												echo '<a href="courses.php?action=Add" class="btn btn-lg btn-primary">
														<i class="far fa-plus-square fa-fw fa-lg"></i> دورة جديدة
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

			echo "<h1 class='text-center'><i class='fas fa-users-class fa-fw fa-lg'></i> تحديث القسم</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$id 		= $_POST['secid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$order 		= $_POST['ordering'];
				$visible 	= $_POST['visibility'];
				$comment 	= $_POST['commenting'];
				$Courses 	= $_POST['Courses'];

				// Update The Database With This Info

				$stmt = $con->prepare("UPDATE 
											sections 
										SET 
											Name = ?, 
											Description = ?, 
											Ordering = ?, 
											Visibility = ?,
											Allow_Comment = ?,
											Allow_Courses = ? 
										WHERE 
											ID = ?");

				$stmt->execute(array($name, $desc, $order, $visible, $comment, $Courses, $id));

				// Echo Success Message

				$theMsg = '<div class="alert alert-success text-center">تم تحديث القسم بنجاح !</div>';

				redirectHome($theMsg, 'back');

			} else {

				$theMsg = '<div class="alert alert-danger text-center">لا يمكنك تصفح هذه الصفحة مباشرة</div>';

				redirectHome($theMsg);

			}

			echo "</div>";

		} elseif ($action == 'Delete') {

			echo "<h1 class='text-center'><i class='fas fa-users-class fa-fw fa-lg'></i> حذف القسم</h1>";
			echo "<div class='container'>";

				// Check If Get Request Catid Is Numeric & Get The Integer Value Of It

				$secid = isset($_GET['secid']) && is_numeric($_GET['secid']) ? intval($_GET['secid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('ID', 'sections', $secid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM sections WHERE ID = :zid");

					$stmt->bindParam(":zid", $secid);

					$stmt->execute();

					$theMsg = '<div class="alert alert-success text-center">تم حذف القسم بنجاح !</div>';

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