		<!-- Start Ultimate Footer Section -->

        <section class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <h3>روابط الموقع</h3>
                        <ul class="list-unstyled three-columns">
                            <li><a href="index.php">الرئيسية</a></li>
                            <?php
                                $allSections = getAllFrom("*", "sections", "WHERE Visibility = 1", "", "ID", "ASC");
                                foreach ($allSections as $Section) {
                                    echo 
                                    '<li>
                                        <a href="sections.php?pageid=' . $Section['ID'] . '&pagename=' . str_replace(' ', '-', $Section['Name']) . '">قسم
                                            ' . $Section['Name'] . '
                                        </a>
                                    </li>';
                                }
                            ?>
                            <li><a href="trainers.php">المدربين</a></li>
                            <li><a href="faq.php">بنك الأسئلة</a></li>
                            <li><a href="about.php">عن الموقع</a></li>
                        </ul>
                        <ul class="list-unstyled social-list">
                            <li><a href="#"><img src="<?php echo $images; ?>social-bookmarks/facebook.png" width="48" height="48" alt="Facebook" /></a></li>
                            <li><a href="#"><img src="<?php echo $images; ?>social-bookmarks/gplus.png" width="48" height="48" alt="Google Plus" /></a></li>
                            <li><a href="#"><img src="<?php echo $images; ?>social-bookmarks/twitter.png" width="48" height="48" alt="Twitter" /></a></li>
                            <li><a href="#"><img src="<?php echo $images; ?>social-bookmarks/pinterest.png" width="48" height="48" alt="Pinterest" /></a></li>
                            <li><a href="#"><img src="<?php echo $images; ?>social-bookmarks/rss.png" width="48" height="48" alt="Rss" /></a></li>
                            <li><a href="#"><img src="<?php echo $images; ?>social-bookmarks/email.png" width="48" height="48" alt="Email" /></a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <h3>أحدث الفيديوهات</h3>

                        <?php
                            $stmt = $con->prepare("SELECT 
                                                        courses_videos.*, 
                                                        courses.Title AS course_title 
                                                    FROM 
                                                        courses_videos 
                                                    INNER JOIN 
                                                        courses 
                                                    ON 
                                                        courses.Course_ID = courses_videos.Course_ID 
                                                    ORDER BY 
                                                        Video_ID DESC 
                                                    LIMIT 3");

                            // Execute Query
                            $stmt->execute();

                            $videos = $stmt->fetchAll();

                            foreach ($videos as $video) {
                        ?>

                        <div class="media">
                            <a class="pull-left" href="courses.php<?php echo '?courseid=' . $video['Course_ID'] . '&videoid=' . $video['Video_ID'] . '&videosource=' . $video['Video_Source'] ?>">
                                <img class="media-object" src="AdminDashboard/uploads/CoursesVideos/<?php echo $video['Image']; ?>" width="100" alt="Image 01" />
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading"><?php echo $video['Title'] ?></h4>
                                <a href="courses.php<?php echo '?courseid=' . $video['Course_ID'] . '&videoid=' . $video['Video_ID'] . '&videosource=' . $video['Video_Source'] ?>">دورة <?php echo $video['course_title'] ?></a>
                            </div>
                        </div>

                        <?php } ?>

                    </div>
                    <div class="col-lg-4">
                        <h3>فريق عمل الموقع</h3>
                        <img class="img-thumbnail" src="<?php echo $images; ?>work/01.jpg" width="150" height="100" alt="Image 01" />
                        <img class="img-thumbnail" src="<?php echo $images; ?>work/02.jpg" width="150" height="100" alt="Image 02" />
                        <img class="img-thumbnail" src="<?php echo $images; ?>work/03.jpg" width="150" height="100" alt="Image 03" />
                        <img class="img-thumbnail" src="<?php echo $images; ?>work/04.jpg" width="150" height="100" alt="Image 04" />
                    </div>
                </div>
            </div>
            <div class="copyright text-center">
                جميع الحقوق محفوظة &copy; 2019 <span>موقع فنى وأفتخر</span>
            </div>
        </section>

        <!-- End Ultimate Footer Section -->

        <!-- Start Section Loading -->

        <!--<div class="loading-overlay">
                    <div class="spinner">
                      <div class="double-bounce1"></div>
                      <div class="double-bounce2"></div>
                    </div>
                </div>-->

        <!-- End Section Loading -->

        <!-- Start Scroll To Top -->

        <div id="scroll-top">
            <i class="far fa-arrow-alt-circle-up"></i>
        </div>

        <!-- End Scroll To Top -->

		<script src="<?php echo $js; ?>jquery-1.11.1.min.js"></script>
		<script src="<?php echo $js ?>jquery-ui.min.js"></script>
		<script src="<?php echo $js; ?>bootstrap.min.js"></script>
		<script src="<?php echo $js ?>jquery.selectBoxIt.min.js"></script>
		<script src="<?php echo $js; ?>frontend.js"></script>
		<!--<script src="<?php echo $js; ?>wow.min.js"></script>
        <script>new WOW().init();</script>
        <script src="<?php echo $js; ?>jquery.nicescroll.min.js"></script>-->
    </body>
</html>