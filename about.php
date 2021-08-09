<?php
    ob_start();
    session_start();
    $pageTitle = 'About';
    include 'initialization.php';
?>

        <!-- Start Breadcrumb -->
        
        <div class="breadcrumb-holder">
            <div class="container">
                <ol class="breadcrumb">
                  <li><a href="index.php" title="الرئيسية"><i class="fas fa-home fa-fw fa-lg"></i></a></li>
                  <li class="active">عن الموقع</li>
                </ol>
            </div>
        </div>
        
        <!-- End Breadcrumb -->
        
        <!-- Start About Us Intro -->
        
        <section class="about-us text-center">
            <div class="container">
                <h1>About Us</h1>
                <hr>
                <p class="lead">We Create Your Digital Dreams Just Think And Tell Us What You Need <br> Or Leave The Inspiration To Us And Just Watch, Just Believe In Our Professionals</p>
                <img class="img-thumbnail" src="<?php echo $images; ?>company_team.jpg" alt="Company Team">
            </div>
        </section>
        
        <!-- End About Us Intro -->
        
        <!-- Start About Features -->
        
        <section class="about-features text-center">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <i class="fa fa-code fa-5x"></i>
                        <h3>We Love Code</h3>
                        <p>We Create Your Digital Dreams Just Think And Tell Us What You Need Or Leave The Inspiration To Us And Just Watch, Just Believe In Our Professionals</p>
                    </div>
                    <div class="col-sm-4">
                        <i class="fa fa-child fa-5x"></i>
                        <h3>We Are Happy</h3>
                        <p>We Create Your Digital Dreams Just Think And Tell Us What You Need Or Leave The Inspiration To Us And Just Watch, Just Believe In Our Professionals</p>
                    </div>
                    <div class="col-sm-4">
                        <i class="fa fa-users fa-5x"></i>
                        <h3>We Are Social</h3>
                        <p>We Create Your Digital Dreams Just Think And Tell Us What You Need Or Leave The Inspiration To Us And Just Watch, Just Believe In Our Professionals</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- End About Features -->
        
        <!-- Start About CEO -->
        
        <section class="about-ceo">
            <div class="container">
                <div class="row">
                    <div class="col-sm-5">
                        <img class="img-responsive" src="<?php echo $images; ?>ceo.png" alt="CEO">
                    </div>
                    <div class="col-sm-7">
                        <h2 class="h1">We Are Happy To Help You</h2>
                        <p class="lead">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        <a class="btn btn-primary">Contact Us</a>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- End ABout CEO -->

<?php
    include $tpl . 'footer.php'; 
    ob_end_flush();
?>