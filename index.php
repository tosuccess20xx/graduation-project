<?php
    ob_start();
    session_start();
    $pageTitle = 'Home';
    include 'initialization.php';
?>

        <!-- Start Carousel -->

        <div id="myslide" class="carousel slide hidden-xs" data-ride="carousel">

            <ol class="carousel-indicators">
                <li data-target="#myslide" data-slide-to="0" class="active"></li>
                <li data-target="#myslide" data-slide-to="1"></li>
                <li data-target="#myslide" data-slide-to="2"></li>
                <li data-target="#myslide" data-slide-to="3"></li>
            </ol>

            <div class="carousel-inner">

                <div class="item active">
                    <img src="<?php echo $images; ?>01.jpg" width="1920" height="600" alt="Pic 1">
                    <div class="carousel-caption hidden-xs">
                        <h2 class="h1">تركيبات ومعدات كهربية</h2>
                        <p class="lead">يقوم المتخصص في هذا المجال بامتلاك المعارف والمهارات ذات الصلة بصيانة وتشغيل نظم توليد ونقل الطاقة الكهربائية والتحكم بها وتنفيذ تركيبات الكهربائية، مثل التركيبات المنزلية والصناعية ولوحات التوزيع , وكذلك تشغيل وصيانة نظم القوى الكهربائية والدوائر الكهربائية ودوائر التحكم في المنشآت وتنفيذ التمديدات الكهربائية للمنشآت.</p>
                    </div>
                </div>

                <div class="item">
                    <img src="<?php echo $images; ?>02.jpg" width="1920" height="600" alt="Pic 2">
                    <div class="carousel-caption hidden-xs">
                        <h2 class="h1">التبريد والتكييف</h2>
                        <p class="lead">يقوم المتخصص فى هذا المجال بإمتلاك المعارف والمهارات التخصصية اللازمة لتركيب وصيانة المكيفات الهوائية وتمديد الشبكات الخاصة بوحدات التبريد والتسخين للمنشآت العامة , وكذلك تكييف الهواء المركزى والتبريد التجارى وأسس التحكم وأنظمته , والصيانة وإصلاح الأعطال .</p>
                    </div>
                </div>

                <div class="item">
                    <img src="<?php echo $images; ?>03.jpg" width="1920" height="600" alt="Pic 3">
                    <div class="carousel-caption hidden-xs">
                        <h2 class="h1">الحاسبات</h2>
                        <p class="lead">يقوم المتخصص فى هذا المجال بإمتلاك المعارف والمهارات التخصصية اللازمة لتصميم أنظمة التحكم المختلفة وتصميم قواعد البيانات وبرمجة أنظمة الحاسب الآلى وتصميم وتحسين وتطوير البرامج الخاصة بالحاسب الآلى , وكذلك تصميم المواقع الإلكترونية وتطويرها وحل المشاكل الخاصة بها .</p>
                    </div>
                </div>

                <div class="item">
                    <img src="<?php echo $images; ?>04.jpg" width="1920" height="600" alt="Pic 4">
                    <div class="carousel-caption hidden-xs">
                        <h2 class="h1">الإلكترونيات</h2>
                        <p class="lead">يقوم المتخصص فى هذا المجال بإمتلاك المعارف والمهارات التخصصية اللازمة لتصميم وفحص وتصليح الرقاقات المصغرة ووحدات التحكم والتوصيلات والأجزاء الإلكترونية , وكذلك قراءة وتفسير مخططات التوصيلات والدوائر الكهربائية .</p>
                    </div>
                </div>

            </div>

            <a class="left carousel-control" href="#myslide" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>

            <a class="right carousel-control" href="#myslide" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>

        </div>

        <!-- End Carousel -->

        <!-- Start Section About -->

        <div class="rainbow-line"></div>

        <section class="about text-center wow bounceIn" data-wow-duration="2s" data-wow-offset="300">
            <div class="container">
                <h1><img src="<?php echo $images; ?>logo.png" width="160" height="160" alt="logo"><br/><span>فنى وأفتخر </span>هو موقع تعليمى يهدف إلى توفير بيئة تعليمية تفاعليه مشوقة تزيد من دافعية الطلبة وتشجعهم على الإبداع .</h1>
            </div>
        </section>

        <div class="rainbow-line"></div>

        <!-- End Section About -->

        <?php if (! isset($_SESSION['user'])) { ?>

        <!-- Start Section Contact Us -->

        <section class="contact-us text-center">
            <div class="fields">
                <div class="container">
                    <div class="row">
                        <i class="fa fa-sign-in-alt fa-5x"></i>
                        <h2 class="h1">تسجيل الدخول</h2>
                        <p class="lead">لكى تتمكن من مشاهدة الدورات والمشاركة والتفاعل .</p>

                        <!-- Start Contact Form -->

                        <form action="login.php" method="POST" role="form">
                            <div class="col-md-6 col-md-push-3 wow bounceInLeft" data-wow-duration="1s" data-wow-offset="400">
                                <div class="form-group">
                                    <input type="text" name="username" autocomplete="off" class="form-control input-lg" placeholder="اسم المستخدم">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" autocomplete="new-password" class="form-control input-lg" placeholder="كلمة المرور">
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="تسجيل الدخول" name="login" class="btn btn-lg btn-block">
                                </div>
                            </div>
                        </form>

                        <!-- End Contact Form -->

                    </div>
                </div>
            </div>
        </section>

        <!-- End Section Contact Us -->

        <?php } ?>

        <!-- Start Testimonials Section -->

        <div class="rainbow-line"></div>

        <section class="testimonials text-center wow flipInY" data-wow-duration="1s" data-wow-offset="200">

            <!-- Start Container -->

            <div class="container">

                <h2><span>آراء الطلاب ومقترحاتهم</span></h2>

                <!-- Start Testimonials Carousel -->

                <div id="carousel_testimonials" class="carousel slide" data-ride="carousel">

                    <div class="carousel-inner comments-text">

                        <?php
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
                                                    ORDER BY 
                                                        Comment_ID DESC 
                                                    LIMIT 4");

                            // Execute Query
                            $stmt->execute();

                            $comments = $stmt->fetchAll();

                            foreach ($comments as $comment) {
                        ?>

                        <div class="item">
                            <p class="lead"><?php echo $comment['Comment_Text'] ?></p>
                            <span><?php echo ucwords($comment['FullName']) ?></span>
                        </div>

                        <?php } ?>

                    </div>

                    <ol class="carousel-indicators comments-carousel">
                        <?php foreach ($comments as $comment) { ?>
                        <li data-target="#carousel_testimonials">
                            <?php if (! empty($comment['Image'])) { ?>
                                <img class="img-responsive img-thumbnail img-circle center-block" src="AdminDashboard\uploads\avatars\<?php echo $comment['Image']; ?>" width="70" height="70" alt="<?php echo ucwords($comment['Username']) ?>" />
                            <?php } else { ?>
                                <img class="img-responsive img-thumbnail img-circle center-block" src="<?php echo $images; ?>avatar.png" width="70" height="70" alt="<?php echo ucwords($comment['Username']) ?>" />
                            <?php } ?>
                        </li>
                        <?php } ?>
                    </ol>

                </div>

                <!-- End Testimonials Carousel -->

            </div>

            <!-- End Container -->

        </section>

        <div class="rainbow-line"></div>

        <!-- End Testimonials Section -->

        <!-- Start Section Price Table -->

        <section class="price_table latest-courses text-center">
            <div class="container">

                <h2><span>أحدث الدورات</span></h2>

                <div class="row">

                    <?php
                        $stmt = $con->prepare("SELECT 
                                                    courses.*, 
                                                    sections.ID AS section_id, 
                                                    sections.Name AS section_name, 
                                                    band.Band_Name, 
                                                    users.UserID, 
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
                                                    Approve = 1 
                                                ORDER BY 
                                                    Course_ID DESC 
                                                LIMIT 4");

                        // Execute Query
                        $stmt->execute();

                        $courses = $stmt->fetchAll();

                        foreach ($courses as $course) {
                    ?>

                    <div class="col-lg-3 col-sm-6 col-xs-12">
                        <div class="price_box wow fadeInUp" data-wow-duration="2s" data-wow-offset="400">
                            <h4 class="text-primary"><?php echo '<a href="sections.php?pageid=' . $course['section_id'] . '&pagename=' . str_replace(' ', '-', $course['section_name']) . '">' . $course['section_name'] . '</a>'; ?></h4>
                            <img class="img-responsive img-thumbnail center-block" src="AdminDashboard/uploads/CoursesImages/<?php echo $course['Image']; ?>" alt="<?php echo $course['Image']; ?>" />
                            <ul class="list-unstyled">
                                <li><?php echo $course['Title']; ?></li>
                                <li>
                                    <i class="far fa-tags fa-fw"></i>
                                    <span><?php echo $course['Band_Name']; ?></span>
                                </li>
                                <li>
                                    <i class="fas fa-chalkboard-teacher fa-fw"></i>
                                    <span><?php echo '<a href="trainers.php?trainerid=' . $course['UserID'] . '">' . ucwords($course['FullName']) . '</a>'; ?></span>
                                </li>
                                <li>
                                    <i class="far fa-calendar-alt fa-fw"></i>
                                    <span><?php echo $course['Create_Date']; ?></span>
                                </li>
                            </ul>
                            <?php

                                if (isset($_SESSION['uid'])) {

                                    $stmt = $con->prepare("SELECT * FROM users_courses WHERE User_ID = ? AND Course_ID = ?");

                                    $stmt->execute(array($_SESSION['uid'], $course['Course_ID']));

                                    $count = $stmt->rowCount();

                                    if ($count > 0) {
                            ?>
                                        <a href="courses.php?courseid=<?php echo $course['Course_ID']; ?>" class="btn btn-primary">عرض الدورة</a>
                              <?php } else { ?>
                                        <a href="courses.php?courseid=<?php echo $course['Course_ID']; ?>" class="btn btn-success">الإشتراك الآن</a>
                            <?php   }

                                } else {
                            ?>
                                    <a href="courses.php?courseid=<?php echo $course['Course_ID']; ?>" class="btn btn-success">الإشتراك الآن</a>
                          <?php } ?>
                        </div>
                    </div>

                    <?php } ?>

                </div>

            </div>
        </section>

        <!-- End Section Price Table -->

        <!-- Start Section Our Team -->

        <section class="our_team text-center">
            <div class="rainbow-line"></div>
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
                                                        UserID DESC 
                                                    LIMIT 4");

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
                                <h3><?php echo '<a href="trainers.php?trainerid=' . $trainer['UserID'] . '">' . ucwords($trainer['FullName']) . '</a>'; ?></h3>
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

        <!-- Start Section Subscribe -->

        <section class="subscribe text-center wow fadeInUp" data-wow-duration="1s" data-wow-offset="200">
            <div class="container">
                <h2 class="h1">Keep In Touch</h2>
                <p class="lead">Sign Up For Newsletter Dont Worry About Spam We Hate It Too.</p>
            </div>
        </section>

        <!-- End Section Subscribe -->

        <!-- Start Section Stats -->

        <section class="statistics text-center">
            <div class="rainbow-line"></div>
            <div class="data">
                <div class="container">
                    <h2><span>الإحصائيات الرئيسية</span></h2>
                    <div class="row">

                        <div class="col-md-3 col-sm-6">
                            <div class="stats pulse">
                                <i class="fa fa-users fa-5x"></i>
                                <p><?php echo countStats('UserID', 'users') ?></p>
                                <span>الأعضاء</span>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="stats pulse">
                                <i class="fa fa-comments fa-5x"></i>
                                <p><?php echo countStats('Comment_ID', 'comments') ?></p>
                                <span>التعليقات</span>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="stats pulse">
                                <i class="fa fa-suitcase fa-5x"></i>
                                <p><?php echo countStats('Course_ID', 'courses') ?></p>
                                <span>الدورات</span>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <div class="stats pulse">
                                <i class="fa fa-video-plus fa-5x"></i>
                                <p><?php echo countStats('Video_ID', 'courses_videos') ?></p>
                                <span>الفيديوهات</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="rainbow-line"></div>
        </section>

        <!-- End Section Stats -->

        <!-- Start Section Our Clients -->

        <div class="our-clients">
            <div class="container">
                <div class="row">
                    <ul class="list-unstyled">
                        <li class="col-md-2 col-xs-4">
                            <img class="img-responsive center-block wow bounceIn" src="<?php echo $images; ?>our-clients/bbc.png" width="126" height="28" alt="BBC" data-wow-duration=".5s" data-wow-offset="200" data-wow-delay=".5s" />
                        </li>
                        <li class="col-md-2 col-xs-4">
                            <img class="img-responsive center-block wow bounceIn" src="<?php echo $images; ?>our-clients/cnn.png" width="126" height="28" alt="CNN" data-wow-duration=".5s" data-wow-offset="200" data-wow-delay="1s" />
                        </li>
                        <li class="col-md-2 col-xs-4">
                            <img class="img-responsive center-block wow bounceIn" src="<?php echo $images; ?>our-clients/forbes.png" width="126" height="28" alt="Forbes" data-wow-duration=".5s" data-wow-offset="200" data-wow-delay="1.5s" />
                        </li>
                        <li class="col-md-2 col-xs-4">
                            <img class="img-responsive center-block wow bounceIn" src="<?php echo $images; ?>our-clients/wired.png" width="126" height="28" alt="Wired" data-wow-duration=".5s" data-wow-offset="200" data-wow-delay="2s" />
                        </li>
                        <li class="col-md-2 col-xs-4">
                            <img class="img-responsive center-block wow bounceIn" src="<?php echo $images; ?>our-clients/wsj.png" width="126" height="28" alt="WSJ" data-wow-duration=".5s" data-wow-offset="200" data-wow-delay="2.5s" />
                        </li>
                        <li class="col-md-2 col-xs-4">
                            <img class="img-responsive center-block wow bounceIn" src="<?php echo $images; ?>our-clients/techradar.png" width="126" height="28" alt="Tech Radar" data-wow-duration=".5s" data-wow-offset="200" data-wow-delay="3s" />
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- End Section Our Clients -->












        <div class="container">
            <div class="row">
                <?php
                    /*$allCourses = getAllFrom('*', 'courses', 'where Approve = 1', '', 'Course_ID');
                    foreach ($allCourses as $Course) {
                        echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="thumbnail item-box">';
                                echo '<span class="price-tag">Band ' . $Course['Band_ID'] . '</span>';
                                echo '<img class="img-responsive" src="' . $images . 'img.png" alt="" />';
                                echo '<div class="caption">';
                                    echo '<h3><a href="courses.php?courseid='. $Course['Course_ID'] .'">' . $Course['Title'] .'</a></h3>';
                                    echo '<p>' . $Course['Description'] . '</p>';
                                    echo '<div class="date">' . $Course['Create_Date'] . '</div>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    }
                */?>
            </div>
        </div>
<?php
    include $tpl . 'footer.php'; 
    ob_end_flush();
?>