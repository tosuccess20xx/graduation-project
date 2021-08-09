<?php
	ob_start();
	session_start();
	$pageTitle = 'Courses';
	include 'initialization.php';
?>

	<!-- Start Breadcrumb -->
        
    <div class="breadcrumb-holder">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href="index.php" title="الرئيسية"><i class="fas fa-home fa-fw fa-lg"></i></a></li>
          <?php if (isset($_GET['courseid']) && is_numeric($_GET['courseid'])) { ?>
                    <?php
                        $stmt = $con->prepare("SELECT 
                        							courses.Title, 
                        							courses.Section_ID, 
                        							sections.Name AS section_name 
                    							FROM 
                    								courses 
                								INNER JOIN 
                									sections 
            									ON 
            										sections.ID = courses.Section_ID 
        										WHERE Course_ID = ?");

                        // Execute Query
                        $stmt->execute(array($_GET['courseid']));

                        $course = $stmt->fetch();
                    ?>
                    <li><?php echo '<a href="sections.php?pageid=' . $course['Section_ID'] . '&pagename=' . str_replace(' ', '-', $course['section_name']) . '">قسم ' . $course['section_name'] . '</a>'; ?></li>
                    <li class="active">دورة <?php echo ucfirst($course['Title']); ?></li>
          <?php } ?>
            </ol>
        </div>
    </div>
    
    <!-- End Breadcrumb -->

<?php

	// Check If Get Request item Is Numeric & Get Its Integer Value
	$courseid = isset($_GET['courseid']) && is_numeric($_GET['courseid']) ? intval($_GET['courseid']) : 0;

	// Select All Data Depend On This ID
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
							AND 
								Approve = 1");

	// Execute Query
	$stmt->execute(array($courseid));

	$count = $stmt->rowCount();

	if ($count > 0) {

	// Fetch The Data
	$course = $stmt->fetch();

	$stmt = $con->prepare("SELECT * FROM books WHERE Course_ID = ?");

	$stmt->execute(array($courseid));

	$courseDoc = $stmt->fetch();
?>
<h1 class="text-center"><?php echo ucwords($course['Title']) ?></h1>
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<img class="img-responsive img-thumbnail center-block" src="AdminDashboard/uploads/CoursesImages/<?php echo $course['Image']; ?>" alt="<?php echo $course['Image']; ?>" />
			<?php
				if (isset($_SESSION['user'])) {

					$userid = $_SESSION['uid'];

					if ($_SERVER['REQUEST_METHOD'] == 'POST') {

						if (isset($_POST['subscribe'])) {

							$stmt = $con->prepare("INSERT INTO 
													users_courses(User_ID, Course_ID, Subscribe_Date)
												VALUES(:zuserid, :zcourseid, now())");
							
							$stmt->execute(array(

								'zuserid' => $userid,
								'zcourseid' => $courseid

							));

							$subscribeMsg = 'تم الإشتراك فى الدورة بنجاح ! يمكنك الآن مشاهدة جميع فيديوهات الدورة .';

						} elseif (isset($_POST['unsubscribe'])) {

							$stmt = $con->prepare("DELETE FROM users_courses WHERE User_ID = ? AND Course_ID = ?");

							$stmt->execute(array($userid, $courseid));

							$unsubscribeMsg = 'تم إلغاء الإشتراك فى هذه الدورة , يرجى الإشتراك مرة أخرى لمشاهدة جميع فيديوهات الدورة .';
						}

					}

					$stmt = $con->prepare("SELECT * FROM users_courses WHERE User_ID = ? AND Course_ID = ?");

					$stmt->execute(array($userid, $courseid));

					$count = $stmt->rowCount();

					if ($count > 0) { ?>
						
						<?php if ($_SESSION['ustatus'] == 1) { ?>

						<form class="unsubscribe" action="<?php echo $_SERVER['PHP_SELF'] . '?courseid=' . $courseid ?>" method="POST">
							<input type="submit" name="unsubscribe" class="btn btn-danger btn-lg confirm" value="إلغاء الإشتراك فى الدورة">
						</form>

						<?php } ?>

			<?php	} elseif ($_SESSION['ustatus'] == 1) { ?>

						<form class="subscribe" action="<?php echo $_SERVER['PHP_SELF'] . '?courseid=' . $courseid ?>" method="POST">
							<input type="submit" name="subscribe" class="btn btn-success btn-lg confirm" value="الإشتراك فى الدورة">
						</form>

						<?php $subscribeMsg = 'إشترك الآن فى الدورة لتشاهد جميع الفيديوهات !'; ?>

			<?php	}
				} else {
					echo '<div class="subscribe"><a class="btn btn-success btn-lg" href="login.php">الإشتراك</a></div>';
				}
			?>
		</div>
		<div class="col-md-8 item-info">
			<p><?php echo ucwords($course['Description']) ?></p>
			<ul class="list-unstyled">
				<li>
					<i class="far fa-calendar-alt fa-fw fa-lg"></i>
					<span>تاريخ الإضافة</span><span> : <?php echo $course['Create_Date'] ?></span>
				</li>
				<li>
					<i class="far fa-video fa-fw fa-lg"></i>
					<span>الفيديوهات</span><span> : <?php echo countItems('Video_ID', 'courses_videos', 'Course_ID', $courseid) ?></span>
				</li>
				<?php if (isset($_SESSION['ustatus']) && $_SESSION['ustatus'] == 1) { ?>
				<li>
					<i class="far fa-book-open fa-fw fa-lg"></i>
					<span>الملف المرفق</span> : <?php echo '<a href="#" data-toggle="modal" data-target="#myModal">
														<img class="img-thumbnail" src="AdminDashboard/uploads/Books/' . $courseDoc['Book_Cover'] . '" alt="" />' . ucwords($courseDoc['Book_Title']) . '</a>'; ?>
				</li>
				<!-- Modal -->
				<div class="modal fade" id="myModal" role="dialog">
					<div class="modal-dialog">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header text-center">
								<h4 class="modal-title"><?php echo $courseDoc['Book_Title'] ?></h4>
							</div>
							<div class="modal-body">
								<embed src="AdminDashboard/uploads/Books/<?php echo $courseDoc['Book_Source'] ?>" type="application/pdf" width="100%" height="100%" />
								
								<a href="AdminDashboard/uploads/Books/<?php echo $courseDoc['Book_Source'] ?>" target="_blank">عرض الكتاب بالحجم الكامل</a>
								
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				<li>
					<i class="far fa-tags fa-fw fa-lg"></i>
					<span>الفرقة</span> : <?php echo $course['Band_Name'] ?>
				</li>
				<li>
					<i class="fas fa-users-class fa-fw fa-lg"></i>
					<span>القسم</span> : <?php echo '<a href="sections.php?pageid=' . $course['section_id'] . '&pagename=' . str_replace(' ', '-', $course['section_name']) . '">' . $course['section_name'] . '</a>'; ?>
				</li>
				<li>
					<i class="fas fa-chalkboard-teacher fa-fw fa-lg"></i>
					<span>المدرب</span> : <?php echo '<a href="trainers.php?trainerid=' . $course['Teacher_ID'] . '">' . ucwords($course['FullName']) . '</a>'; ?>
				</li>
			</ul>
		</div>
	</div>
	<?php
		if (isset($subscribeMsg)) {

			echo '<div class="alert alert-success subscribeMsg">' . $subscribeMsg . '</div>';

		} elseif (isset($unsubscribeMsg)) {

			echo '<div class="alert alert-danger subscribeMsg">' . $unsubscribeMsg . '</div>';

		}
	?>
	<hr class="custom-hr">
</div>
<?php

	if (isset($_SESSION['user'])) {

		if ($_SESSION['ustatus'] == 1) {

			if ($count > 0) {

				$limit = NULL;

			} else {

				$limit = 'LIMIT 2';

			}

			// Select All Videos

			$stmt = $con->prepare("SELECT * FROM courses_videos WHERE Course_ID = ? ORDER BY Video_ID ASC $limit");

			// Execute The Statement

			$stmt->execute(array($courseid));

			// Assign To Variable 

			$videos = $stmt->fetchAll();

?>

				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<?php if (isset($_GET['videoid'])) { ?>
							<video id="video" width="100%" height="100%" preload="auto" tabindex="0" controls>
								<source id="video-preview" src="#" type="video/mp4">
								<source src="<?php echo $images; ?>img.png" type="png">
								Your browser does not support HTML5 video.
							</video>
							<?php } else { ?>
								<div class="video-overlay">
									<img class="img-responsive img-thumbnail center-block" src="AdminDashboard/uploads/CoursesImages/<?php echo $course['Image']; ?>" alt="<?php echo $course['Image']; ?>" />
									<div>
										<span><?php echo countItems('Video_ID', 'courses_videos', 'Course_ID', $courseid) ?></span>
										<div>
											<i class="fas fa-bars fa-lg"></i>
											<i class="fas fa-play fa-lg"></i>
										</div>
									</div>
									<div><a href="#"><i class="fas fa-play fa-lg"></i><span> تشغيل القائمة</span></a></div>
								</div>
							<?php } ?>
							<?php if (isset($_GET['videoid'])) { ?>
								<?php if (isset($_SESSION['user'])) { ?>
								<!-- Start Add Comment -->
								<div class="row">
									<div class="col-md-12">
										<h3 class="comments-count">التعليقات <?php echo countItems('Comment_ID', 'comments', 'Video_ID', $_GET['videoid']) ?></h3>
										<hr class="custom-hr">
										<div class="add-comment">
											<form action="<?php echo $_SERVER['PHP_SELF'] . '?courseid=' . $courseid . '&videoid=' . $_GET['videoid'] . '&videosource=' . $_GET['videosource'] ?>" method="POST">
												<div class="row">
													<div class="col-xs-2 text-center">
														<?php if (! empty($_SESSION['image'])) { ?>
															<img class="img-responsive img-thumbnail img-circle center-block" src="AdminDashboard\uploads\avatars\<?php echo $_SESSION['image']; ?>" alt="" />
														<?php } else { ?>
															<img class="img-responsive img-thumbnail img-circle center-block" src="<?php echo $images; ?>avatar.png" alt="" />
														<?php } ?>
													</div>
													<div class="col-xs-10">
														<div class="comment-info">
															<span><?php echo ucwords($_SESSION['user']) ?></span>
															<textarea name="comment" placeholder="أضف تعليقك" required></textarea>
															<input class="btn btn-primary" type="submit" value="&#xf075; أضف تعليق">
														</div>
													</div>
												</div>
											</form>
											<?php 
												if ($_SERVER['REQUEST_METHOD'] == 'POST') {

													$comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
													$videoid 	= $_GET['videoid'];
													$userid 	= $_SESSION['uid'];

													if (! empty($comment)) {

														$stmt = $con->prepare("INSERT INTO 
															comments(Comment_Text, Comment_Date, Video_ID, User_ID)
															VALUES(:zcomment, NOW(), :zvideoid, :zuserid)");

														$stmt->execute(array(

															'zcomment' => $comment,
															'zvideoid' => $videoid,
															'zuserid' => $userid

														));

														if ($stmt) {

															echo '<div class="alert alert-success text-center">تم إضافة تعليق بنجاح !</div>';

														}

													} else {

														echo '<div class="alert alert-danger text-center">يجب أن تكتب تعليق .</div>';

													}

												}
											?>
										</div>
									</div>
								</div>
								<!-- End Add Comment -->
								<?php } else {
									echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add Comment';
								} ?>
								<hr class="custom-hr">
									<?php

										// Select All Users Except Admin 
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
																	Status = 1 
																ORDER BY 
																	Comment_ID DESC");

										// Execute The Statement

										$stmt->execute(array($_GET['videoid']));

										// Assign To Variable 

										$comments = $stmt->fetchAll();

									?>
								
								<?php foreach ($comments as $comment) { ?>
									<div class="comment-box">
										<div class="row">
											<div class="col-xs-2 text-center">
												<?php if (! empty($comment['Image'])) { ?>
													<img class="img-responsive img-thumbnail img-circle center-block" src="AdminDashboard\uploads\avatars\<?php echo $comment['Image']; ?>" alt="" />
												<?php } else { ?>
													<img class="img-responsive img-thumbnail img-circle center-block" src="<?php echo $images; ?>avatar.png" alt="" />
												<?php } ?>
											</div>
											<div class="col-xs-10">
												<div class="comment-info">
													<span><?php echo ucwords($comment['FullName']) ?></span>
													<p><?php echo $comment['Comment_Text'] ?></p>
													<span><?php echo $comment['Comment_Date'] ?></span>
												</div>
											</div>
										</div>
									</div>
									<hr class="custom-hr">
								<?php } ?>
							<?php } ?>
						</div>
						<div class="col-md-6 playlist">
							<?php foreach ($videos as $video) {?>
							<div class="row <?php if (isset($_GET['videoid']) && $_GET['videoid'] == $video['Video_ID']) { echo 'current'; } ?>">
								<div class="col-sm-4">
									<div class="course-videos-overlay <?php if (isset($_GET['videoid']) && $_GET['videoid'] == $video['Video_ID']) { echo 'current-video'; } ?>">
										<img class="img-responsive img-thumbnail center-block" src="AdminDashboard/uploads/CoursesVideos/<?php echo $video['Image']; ?>" alt="<?php echo $video['Image']; ?>" />
										<?php

											if (isset($_GET['videoid'])) {
												if ($_GET['videoid'] == $video['Video_ID']) {
													echo '<div class="current-play"><i class="far fa-play fa-lg"></i></div>';
												}
											}

										?>
										<div class="course-overlay"><a href="<?php echo 'courses.php?courseid=' . $video['Course_ID'] . '&videoid=' . $video['Video_ID'] . '&videosource=' . $video['Video_Source'] ?>"><i class="fas fa-play fa-lg"></i><span> تشغيل الفيديو</span></a></div>
									</div>
								</div>
								<div class="col-sm-8 item-info">
									<h4><a class="play-video" href="<?php echo '?courseid=' . $courseid . '&videoid=' . $video['Video_ID'] . '&videosource=' . $video['Video_Source'] ?>"><?php echo ucwords($video['Title']) ?></a></h4>
									<ul class="list-unstyled">
										<li>
											<i class="far fa-calendar-alt fa-fw"></i>
											<span>تاريخ الإضافة</span><span> : <?php echo $video['Create_Date'] ?></span>
										</li>
									</ul>
								</div>
							</div>
							<hr class="custom-hr">
							<?php } ?>
						</div>
					</div>
				</div>

<?php 	} else {
			echo '<div class="container">';
				echo '<div class="alert alert-danger text-center">نأسف لا يمكنك مشاهدة فيديوهات الدورة . برجاء إنتظار تفعيل حسابك</div>';
			echo '</div>';
		}

	} else {
				echo '<div class="container">';
					echo '<div class="alert alert-success text-center">يجب تسجيل الدخول للإشتراك فى الدورة ومشاهدة محتوى الدورات</div>';
				echo '</div>';
	}

	} else {
		echo '<div class="container">';
			echo '<div class="alert alert-danger text-center">لا يوجد مثل هذا المعرف أو هذه الدورة في انتظار الموافقة</div>';
		echo '</div>';
	}
	include $tpl . 'footer.php';
	ob_end_flush();
?>