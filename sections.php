<?php 
	ob_start();
	session_start();
	$pageTitle = 'Sections';
	include 'initialization.php';
?>

<!-- Start Breadcrumb -->
<div class="breadcrumb-holder">
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="index.php" title="الرئيسية"><i class="fas fa-home fa-fw fa-lg"></i></a></li>
			<li class="active">قسم <?php echo ucwords(str_replace('-', ' ', $_GET['pagename'])); ?></li>
		</ol>
	</div>
</div>
<!-- End Breadcrumb -->
<div class="container section price_table text-center">
	<h1 class="section-title text-center"><span>قسم <?php echo ucwords(str_replace('-', ' ', $_GET['pagename'])); ?></span></h1>
	<div class="row">
		<?php
		if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
			
			$section = intval($_GET['pageid']);
			
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
										Section_ID = ?
									AND 
										Approve = 1 
									ORDER BY Course_ID ASC");

			// Execute Query
			$stmt->execute(array($section));

			// Fetch The Data
			$allCourses = $stmt->fetchAll();

			foreach ($allCourses as $Course) { ?>
				<div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="price_box wow fadeInUp" data-wow-duration="2s" data-wow-offset="400">
                        <h4 class="text-primary"><?php echo $Course['section_name']; ?></h4>
                        <img class="img-responsive img-thumbnail center-block" src="AdminDashboard/uploads/CoursesImages/<?php echo $Course['Image']; ?>" alt="<?php echo $Course['Image']; ?>" />
                        <ul class="list-unstyled">
                            <li><?php echo $Course['Title']; ?></li>
                            <li>
                                <i class="far fa-tags fa-fw"></i>
                                <span><?php echo $Course['Band_Name']; ?></span>
                            </li>
                            <li>
                                <i class="fas fa-chalkboard-teacher fa-fw"></i>
                                <span><?php echo '<a href="trainers.php?trainerid=' . $Course['Teacher_ID'] . '">' . ucwords($Course['FullName']) . '</a>'; ?></span>
                            </li>
                            <li>
                                <i class="far fa-calendar-alt fa-fw"></i>
                                <span><?php echo $Course['Create_Date']; ?></span>
                            </li>
                        </ul>
                        <?php

                            if (isset($_SESSION['uid'])) {

                                $stmt = $con->prepare("SELECT * FROM users_courses WHERE User_ID = ? AND Course_ID = ?");

                                $stmt->execute(array($_SESSION['uid'], $Course['Course_ID']));

                                $count = $stmt->rowCount();

                                if ($count > 0) {
                        ?>
                                    <a href="courses.php?courseid=<?php echo $Course['Course_ID']; ?>" class="btn btn-primary">عرض الدورة</a>
                          <?php } else { ?>
                                    <a href="courses.php?courseid=<?php echo $Course['Course_ID']; ?>" class="btn btn-success">الإشتراك الآن</a>
                        <?php   }

                            } else {
                        ?>
                                <a href="courses.php?courseid=<?php echo $course['Course_ID']; ?>" class="btn btn-success">الإشتراك الآن</a>
                      <?php } ?>
                    </div>
                </div>
			<?php }
		} else {
			echo 'You Must Add Page ID';
		}
		?>
	</div>
</div>

<?php 
	include $tpl . 'footer.php';
	ob_end_flush();
?>