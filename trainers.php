<?php
    ob_start();
    session_start();
    $pageTitle = 'Trainers';
    include 'initialization.php';
?>

        <!-- Start Breadcrumb -->
        
        <div class="breadcrumb-holder">
            <div class="container">
                <ol class="breadcrumb">
                    <li><a href="index.php" title="الرئيسية"><i class="fas fa-home fa-fw fa-lg"></i></a></li>
              <?php if (isset($_GET['trainerid']) && is_numeric($_GET['trainerid'])) { ?>
                        <?php
                            $stmt = $con->prepare("SELECT FullName FROM users WHERE UserID = ?");

                            // Execute Query
                            $stmt->execute(array($_GET['trainerid']));

                            $trainer = $stmt->fetch();
                        ?>
                        <li><a href="trainers.php">المدربين</a></li>
                        <li class="active">م / <?php echo ucfirst($trainer['FullName']); ?></li>
              <?php } else { ?>
                        <li class="active">المدربين</li>
              <?php } ?>
                </ol>
            </div>
        </div>
        
        <!-- End Breadcrumb -->

      <?php if (isset($_GET['trainerid']) && is_numeric($_GET['trainerid'])) { ?>

                <section class="our_team text-center">
                    <div class="team">
                        <div class="container section price_table text-center">
                            <div class="row">

                                <div class="row">

                                    <?php

                                        $trainerid = intval($_GET['trainerid']);

                                        $stmt = $con->prepare("SELECT 
                                                                    users.*, 
                                                                    sections.ID AS section_id, 
                                                                    sections.Name AS section_name, 
                                                                    sections.Description AS section_description 
                                                                FROM 
                                                                    users 
                                                                INNER JOIN 
                                                                    sections 
                                                                ON 
                                                                    sections.ID = users.SectionID 
                                                                WHERE 
                                                                    UserID = ?");

                                        // Execute Query
                                        $stmt->execute(array($trainerid));

                                        $trainer = $stmt->fetch();

                                    ?>

                                    <div class="col-sm-12">
                                        <div class="person wow pulse" data-wow-duration="1s" data-wow-offset="400">
                                            <?php if (! empty($trainer['Image'])) { ?>
                                                <img class="img-responsive img-thumbnail img-circle center-block" src="AdminDashboard\uploads\avatars\<?php echo $trainer['Image']; ?>" width="200" height="200" alt="<?php echo ucwords($trainer['Username']) ?>" />
                                            <?php } else { ?>
                                                <img class="img-responsive img-thumbnail img-circle center-block" src="<?php echo $images; ?>avatar.png" width="200" height="200" alt="<?php echo ucwords($trainer['Username']) ?>" />
                                            <?php } ?>
                                            <h3><?php echo '<a href="?trainerid=' . $trainer['UserID'] . '">' . ucwords($trainer['FullName']) . '</a>'; ?></h3>
                                            <p><?php echo '<a href="sections.php?pageid=' . $trainer['section_id'] . '&pagename=' . str_replace(' ', '-', $trainer['section_name']) . '">' . $trainer['section_description'] . '</a>'; ?></p>
                                            <a href="#"><i class="fab fa-google-plus-g fa-2x"></i></a>
                                            <a href="#"><i class="fab fa-facebook-f fa-2x"></i></a>
                                            <a href="#"><i class="fab fa-twitter fa-2x"></i></a>
                                            <a href="#"><i class="fab fa-youtube fa-2x"></i></a>
                                        </div>
                                    </div>

                                </div>

                                <?php
                                    
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
                                                                Teacher_ID = ?
                                                            AND 
                                                                Approve = 1 
                                                            ORDER BY Course_ID ASC");

                                    // Execute Query
                                    $stmt->execute(array($trainerid));

                                    // Fetch The Data
                                    $allCourses = $stmt->fetchAll();

                                    foreach ($allCourses as $Course) { ?>
                                        <div class="col-lg-3 col-sm-6 col-xs-12">
                                            <div class="price_box custom wow fadeInUp" data-wow-duration="2s" data-wow-offset="400">
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
                                                        <span><?php echo $Course['FullName']; ?></span>
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
                                                        <a href="courses.php?courseid=<?php echo $Course['Course_ID']; ?>" class="btn btn-success">الإشتراك الآن</a>
                                              <?php } ?>
                                            </div>
                                        </div>
                              <?php } ?>
                            </div>
                        </div>
                    </div>
                </section>

      <?php } else { ?>

                <!-- Start Section Our Team -->

                <section class="our_team text-center">
                    <div class="team">
                        <div class="container">
                            <h2><span>فريق المدربين لدينا</span></h2>
                            <div class="row">

                                <?php
                                    $stmt = $con->prepare("SELECT 
                                                                users.*, 
                                                                sections.ID AS section_id, 
                                                                sections.Name AS section_name, 
                                                                sections.Description AS section_description 
                                                            FROM 
                                                                users 
                                                            INNER JOIN 
                                                                sections 
                                                            ON 
                                                                sections.ID = users.SectionID 
                                                            WHERE 
                                                                GroupID = 2 
                                                            AND 
                                                                RegStatus = 1 
                                                            ORDER BY 
                                                                UserID DESC");

                                    // Execute Query
                                    $stmt->execute();

                                    $trainers = $stmt->fetchAll();

                                    foreach ($trainers as $trainer) {
                                ?>

                                <div class="col-lg-3 col-sm-6">
                                    <div class="person wow pulse" data-wow-duration="1s" data-wow-offset="400">
                                        <?php if (! empty($trainer['Image'])) { ?>
                                            <img class="img-responsive img-thumbnail img-circle center-block" src="AdminDashboard\uploads\avatars\<?php echo $trainer['Image']; ?>" width="200" height="200" alt="<?php echo ucwords($trainer['Username']) ?>" />
                                        <?php } else { ?>
                                            <img class="img-responsive img-thumbnail img-circle center-block" src="<?php echo $images; ?>avatar.png" width="200" height="200" alt="<?php echo ucwords($trainer['Username']) ?>" />
                                        <?php } ?>
                                        <h3><?php echo '<a href="?trainerid=' . $trainer['UserID'] . '">' . ucwords($trainer['FullName']) . '</a>'; ?></h3>
                                        <p><?php echo '<a href="sections.php?pageid=' . $trainer['section_id'] . '&pagename=' . str_replace(' ', '-', $trainer['section_name']) . '">' . $trainer['section_description'] . '</a>'; ?></p>
                                        <a href="#"><i class="fab fa-google-plus-g fa-2x"></i></a>
                                        <a href="#"><i class="fab fa-facebook-f fa-2x"></i></a>
                                        <a href="#"><i class="fab fa-twitter fa-2x"></i></a>
                                        <a href="#"><i class="fab fa-youtube fa-2x"></i></a>
                                    </div>
                                </div>

                                <?php } ?>

                            </div>
                        </div>
                    </div>
                    <div class="rainbow-line"></div>
                </section>

                <!-- End Section Our Team -->
                
      <?php } ?>

<?php
    include $tpl . 'footer.php'; 
    ob_end_flush();
?>