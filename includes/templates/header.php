<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <!-- IE Compatibility Meta -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- First Mobile Meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Educational Website <?php getTitle() ?></title>
        <link rel="icon" href="">
        <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.css">
        <link rel="stylesheet" href="<?php echo $css; ?>bootstrap_rtl.css">
        <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css" />
        <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>frontend.css">
        <link rel='stylesheet' href="<?php echo $css; ?>hover.css" />
        <link rel='stylesheet' href="<?php echo $css; ?>animate.css" />
        <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.min.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>

        <?php
            if (isset($_SESSION['ustatus']) && $_SESSION['ustatus'] == 0) {
                echo '<div class="alert alert-danger account-activation">نآسف لا يمكنك مشاهدة فيديوهات الدورات برجاء إنتظار تفعيل حسابك !</div>';
            }
        ?>

        <!-- Start Our Navbar -->

        <nav class="navbar navbar-default" role="navigation">
            <div class="rainbow-line"></div>
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#app-nav">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand wobble-horizontal" href="index.php"><span><img src="<?php echo $images; ?>logo.png" width="70" height="70" alt="logo"></span>فنى وأفتخر </a>
                </div>
                <div class="collapse navbar-collapse" id="app-nav">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php"><i class="fas fa-home fa-fw fa-lg"></i> الرئيسية</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-users-class fa-fw fa-lg"></i> الأقسام <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <?php
                                    $allSections = getAllFrom("*", "sections", "WHERE Visibility = 1", "", "ID", "ASC");
                                    foreach ($allSections as $Section) {
                                        echo 
                                        '<li>
                                            <a href="sections.php?pageid=' . $Section['ID'] . '&pagename=' . str_replace(' ', '-', $Section['Name']) . '">
                                                ' . $Section['Name'] . '
                                            </a>
                                        </li>';
                                    }
                                ?>
                            </ul>
                        </li>
                        <li><a href="trainers.php"><i class="fas fa-chalkboard-teacher fa-fw fa-lg"></i> المدربين</a></li>
                        <li><a href="faq.php"><i class="far fa-question-circle fa-fw fa-lg"></i> بنك الأسئلة</a></li>
                        <li><a href="about.php"><i class="far fa-info-circle fa-fw fa-lg"></i> عن الموقع</a></li>
                        <?php 
                            if (! isset($_SESSION['user'])) { 
                                if ($pageTitle != 'Login') { ?>
                                    <a href="login.php" class="btn"><i class="fas fa-sign-in-alt fa-fw fa-lg"></i> تسجيل الدخول</a>
                        <?php } } ?>
                    </ul>
                    <?php if (isset($_SESSION['user'])) { ?>
                            <ul class="nav navbar-nav">
                                <li class="dropdown">
                                    <?php
                                        if (empty($_SESSION['image'])) {
                                            echo '<img class="my-image img-thumbnail img-circle" src="' . $images . 'avatar.png" alt="" />';
                                        } else {
                                            echo '<img class="my-image img-thumbnail img-circle" src="' . 'AdminDashboard\uploads\avatars\\' . $_SESSION['image'] . '" alt="" />';
                                        }
                                    ?>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo ucfirst($sessionUser) ?> <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="profile.php"><i class="fas fa-user-circle fa-fw fa-lg"></i> الملف الشخصى</a></li>
                                        <?php if ($_SESSION['ugroup'] != 3) { ?>
                                        <li><a href="newcourse.php"><i class="fas fa-suitcase fa-fw fa-lg"></i> إضافة دورة</a></li>
                                        <li><a href="newvideo.php"><i class="far fa-video-plus fa-fw fa-lg"></i> إضافة فيديو</a></li>
                                        <?php } ?>
                                        <li><a href="profile.php#my-courses"><i class="far fa-suitcase fa-fw fa-lg"></i> دوراتى</a></li>
                                        <li><a href="logout.php"><i class="fas fa-sign-out-alt fa-fw fa-lg"></i> تسجيل الخروج</a></li>
                                    </ul>
                                </li>
                            </ul>
                    <?php } ?>
                </div>
            </div> <!-- End Of The Container -->
            <div class="rainbow-line"></div>
        </nav>

        <!-- End Our Navbar -->