

        <nav class="navbar navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="dashboard.php"><i class="fas fa-home fa-fw fa-lg"></i> الرئيسية</a>
                </div>
                <div class="collapse navbar-collapse" id="app-nav">
                    <ul class="nav navbar-nav">
                        <li><a href="sections.php"><i class="fas fa-users-class fa-fw fa-lg"></i> الأقسام</a></li>
                        <li><a href="courses.php"><i class="fas fa-suitcase fa-fw fa-lg"></i> الدورات</a></li>
                        <li><a href="videos.php"><i class="fas fa-film fa-fw fa-lg"></i> الفيديوهات</a></li>
                        <li><a href="books.php"><i class="far fa-books fa-fw fa-lg"></i> الكتب</a></li>
                        <li><a href="teachers.php"><i class="fas fa-chalkboard-teacher fa-fw fa-lg"></i> المدربين</a></li>
                        <li><a href="students.php"><i class="fas fa-user-graduate fa-fw fa-lg"></i> الطلاب</a></li>
                        <li><a href="comments.php"><i class="far fa-comments fa-fw fa-lg"></i> التعليقات</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <?php
                                if (empty($_SESSION['Image'])) {
                                    echo '<img class="my-image img-thumbnail img-circle" src="' . $images . 'chris.jpg" alt="" />';
                                } else {
                                    echo '<img class="my-image img-thumbnail img-circle" src="' . $images . $_SESSION['Image'] . '" alt="" />';
                                }
                            ?>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo ucfirst($_SESSION['Username']) ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../index.php"><i class="fas fa-home fa-fw fa-lg"></i> زيارة الموقع</a></li>
                                <li><a href="teachers.php?action=Edit&userid=<?php echo $_SESSION['ID'] ?>"><i class="fas fa-user-circle fa-fw fa-lg"></i> الملف الشخصى</a></li>
                                <li><a href="logout.php"><i class="fas fa-sign-out-alt fa-fw fa-lg"></i> تسجيل الخروج</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

