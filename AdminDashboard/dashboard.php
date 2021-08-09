<?php

	ob_start(); // Output Buffering Start

	session_start();

	if (isset($_SESSION['Username'])) {

		$pageTitle = 'Dashboard';

		include 'initialization.php';

		/* Start Dashboard Page */

		$numUsers = 6; // Number Of Latest Users

		$latestUsers = getLatest("*", "users", "UserID", $numUsers); // Latest Users Array

		$numCourses = 6; // Number Of Latest Courses

		$latestCourses = getLatest("*", 'courses', 'Course_ID', $numCourses); // Latest Courses Array

		$numComments = 6;

		?>

		<div class="home-stats">
			<div class="container text-center">
				<h1><i class="fas fa-desktop fa-fw fa-lg"></i> لوحة التحكم</h1>
				<div class="row">
					<div class="col-md-3">
						<div class="stat st-teachers">
							<i class="fa fa-chalkboard-teacher"></i>
							<div class="info">
								المدربين
								<span>
									<a href="teachers.php"><?php echo checkItem("GroupID", "users", 2) ?></a>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="stat st-teach-pending">
							<i class="fa fa-user-plus"></i>
							<div class="info">
								مدربين غير مفعلين
								<span>
									<a href="teachers.php?action=Manage&page=Pending">
										<?php echo checkPendingUsers("RegStatus", "users", "WHERE GroupID = 2 AND RegStatus = 0") ?>
									</a>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="stat st-student">
							<i class="fa fa-user-graduate"></i>
							<div class="info">
								الطلاب
								<span>
									<a href="students.php"><?php echo checkItem("GroupID", "users", 3) ?></a>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="stat st-stude-pending">
							<i class="fa fa-user-plus"></i>
							<div class="info">
								طلاب غير مفعلين
								<span>
									<a href="students.php?action=Manage&page=Pending">
										<?php echo checkPendingUsers("RegStatus", "users", "WHERE GroupID = 3 AND RegStatus = 0") ?>
									</a>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container text-center">
				<div class="row">
					<div class="col-md-3">
						<div class="stat st-sections">
							<i class="fa fa-users-class"></i>
							<div class="info">
								الأقسام
								<span>
									<a href="sections.php"><?php echo countItems('ID', 'sections') ?></a>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="stat st-courses">
							<i class="fa fa-suitcase"></i>
							<div class="info">
								الدورات
								<span>
									<a href="courses.php"><?php echo countItems('Course_ID', 'courses') ?></a>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="stat st-videos">
							<i class="fa fa-film"></i>
							<div class="info">
								الفيديوهات
								<span>
									<a href="videos.php"><?php echo countItems('Video_ID', 'courses_videos') ?></a>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="stat st-comments">
							<i class="fa fa-comments"></i>
							<div class="info">
								التعليقات
								<span>
									<a href="comments.php"><?php echo countItems('Comment_ID', 'comments') ?></a>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="latest">
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-users"></i>
								أحدث <span class="number"><?php echo $numUsers ?></span> مستخدمين مسجلين 
								<span class="toggle-info pull-right">
									<i class="fa fa-minus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled latest-users">
								<?php
									if (! empty($latestUsers)) {
										foreach ($latestUsers as $user) {
											echo '<li>';
												echo ucfirst($user['Username']);
												echo "<a 
														href='teachers.php?action=Edit&userid=" . $user['UserID'] . "' 
														class='btn btn-success pull-right'>
														<i class='fa fa-edit'></i> تحديث</a>";
												if ($user['RegStatus'] == 0) {
													echo "<a 
															href='teachers.php?action=Activate&userid=" . $user['UserID'] . "' 
															class='btn btn-info pull-right activate confirm'>
															<i class='fa fa-user-check'></i> تفعيل</a>";
												} else {
                                                    echo "<a 
															href='teachers.php?action=UnActivate&userid=" . $user['UserID'] . "' 
															class='btn btn-danger pull-right activate confirm'>
															<i class='fas fa-user-times'></i> إلغاء التفعيل</a>";
                                                }
											echo '</li>';
										}
									} else {
										echo 'لا يوجد أعضاء لعرضهم .';
									}
								?>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-tag"></i> 
								أحدث <span class="number"><?php echo $numCourses ?></span> دورات 
								<span class="toggle-info pull-right">
									<i class="fa fa-minus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled latest-users">
									<?php
										if (! empty($latestCourses)) {
											foreach ($latestCourses as $Course) {
												echo '<li>';
													echo $Course['Title'];
													echo "<a 
															href='courses.php?action=Edit&courseid=" . $Course['Course_ID'] . "' 
															class='btn btn-success pull-right'>
															<i class='fa fa-edit'></i> تحديث</a>";
													if ($Course['Approve'] == 0) {
														echo "<a 
																href='courses.php?action=Approve&courseid=" . $Course['Course_ID'] . "' 
																class='btn btn-info pull-right activate confirm'>
																<i class='fa fa-check'></i> موافقة</a>";
													} else {
                                                        echo "<a 
																href='courses.php?action=UnApprove&courseid=" . $Course['Course_ID'] . "' 
																class='btn btn-danger pull-right activate confirm'>
																<i class='fa fa-ban'></i> إلغاء الموافقة</a>";
                                                    }
												echo '</li>';
											}
										} else {
											echo 'لا يوجد دورات لعرضها .';
										}
									?>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<!-- Start Latest Comments -->
				<div class="row">
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-comments-o"></i>
								أحدث <span class="number"><?php echo $numComments ?></span> تعليقات 
								<span class="toggle-info pull-right">
									<i class="fa fa-minus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
								<?php
									$stmt = $con->prepare("SELECT 
																comments.*, users.Username
															FROM 
																comments
															INNER JOIN 
																users 
															ON 
																users.UserID = comments.User_ID
															ORDER BY 
																Comment_ID DESC
															LIMIT $numComments");

									$stmt->execute();
									$comments = $stmt->fetchAll();

									if (! empty($comments)) {
										foreach ($comments as $comment) {
											echo '<div class="comment-box latest-users">';
												echo '<span class="member-n">
													<a href="teachers.php?action=Edit&userid=' . $comment['User_ID'] . '">
														' . ucfirst($comment['Username']) . '</a></span>';
												echo '<p class="member-c">' . $comment['Comment_Text'] . '</p>';
												echo "<a href='comments.php?action=Edit&comid=" . $comment['Comment_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> تحديث</a>";
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
											echo '</div>';
											echo '<hr class="custom-hr">';
										}
									} else {
										echo 'لا يوجد تعليقات لعرضها .';
									}
								?>
							</div>
						</div>
					</div>
				</div>
				<!-- End Latest Comments -->
			</div>
		</div>

		<?php

		/* End Dashboard Page */

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>