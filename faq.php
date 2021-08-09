<?php
    ob_start();
    session_start();
    $pageTitle = 'FAQ';
    include 'initialization.php';
?>

        <!-- Start Breadcrumb -->
        
        <div class="breadcrumb-holder">
            <div class="container">
                <ol class="breadcrumb">
                  <li><a href="index.php" title="الرئيسية"><i class="fas fa-home fa-fw fa-lg"></i></a></li>
                  <li class="active">بنك الأسئلة</li>
                </ol>
            </div>
        </div>
        
        <!-- End Breadcrumb -->
        
        <!-- Start FAQ Intro -->
        
        <section class="faq text-center">
            <div class="container">
                <h1>بنك الأسئلة والإجابات</h1>
                <hr>
                <p class="lead">ستجد هنا جميع الأسئلة التى تبحث عنها وقاعدة المعرفة الكاملة</p>
            </div>
        </section>
        
        <!-- End FAQ Intro -->
        
        <!-- Start FAQ Accordion -->
        
        <section class="faq-questions">
            <div class="container">
                <div class="panel-group" id="accordion" roles="tablist" aria-multiselectable="true">
                    
                    <!-- Start Question 1 -->
                    
                    <div class="panel panel-default">
                        <div class="panel-heading" roles="tab" id="heading-one">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-one" aria-expanded="true" arial-controls="collapse-one">
                                    قسم تركيبات ومعدات كهربية
                                </a>
                            </h4>
                        </div>
                        <div id="collapse-one" class="panel-collapse collapse" roles="tabpanel" aria-labelledby="heading-one">
                            <div class="panel-body">
                                <ul class="list-unstyled">
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Prog_log_con.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة تحكم منطقى مبرمج <span>PLC</span></a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Elec_prot_equ.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة آلات كهربية ووقاية</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Air_cond_tec.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة تركيبات كهربية</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/P_P_manag_elec.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة تخطيط وإدارة إنتاج</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Comp_ind.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة حاسب آلى</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- End Question 1 -->
                    
                    <!-- Start Question 2 -->
                    
                    <div class="panel panel-default">
                        <div class="panel-heading" roles="tab" id="heading-two">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-two" aria-expanded="true" arial-controls="collapse-two">
                                    قسم التبريد والتكييف
                                </a>
                            </h4>
                        </div>
                        <div id="collapse-two" class="panel-collapse collapse" roles="tabpanel" aria-labelledby="heading-two">
                            <div class="panel-body">
                                <ul class="list-unstyled">
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Air_cond_tec.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة تكنولوجيا التكييف</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Elec_cont_tec1.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة تكنولوجيا كهرباء وتحكم الجزء الأول</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Elec_cont_tec2.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة تكنولوجيا كهرباء وتحكم الجزء الثانى</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Elec_cont_tec3.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة تكنولوجيا كهرباء وتحكم الجزء الثالث</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Elec_cont_tec4.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة تكنولوجيا كهرباء وتحكم الجزء الرابع</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Comp_ind.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة حاسب آلى</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- End Question 2 -->
                    
                    <!-- Start Question 3 -->
                    
                    <div class="panel panel-default">
                        <div class="panel-heading" roles="tab" id="heading-three">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-three" aria-expanded="true" arial-controls="collapse-three">
                                    قسم الحاسبات
                                </a>
                            </h4>
                        </div>
                        <div id="collapse-three" class="panel-collapse collapse" roles="tabpanel" aria-labelledby="heading-three">
                            <div class="panel-body">
                                <ul class="list-unstyled">
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Comp_Prog1.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة برمجيات الحاسب الجزء الأول</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Comp_Prog2.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة برمجيات الحاسب الجزء الثانى</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Comp_Prog3.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة برمجيات الحاسب الجزء الثالث</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Comp_Prog4.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة برمجيات الحاسب الجزء الرابع</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Comp_ind.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة حاسب آلى</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- End Question 3 -->
                    
                    <!-- Start Question 4 -->
                    
                    <div class="panel panel-default">
                        <div class="panel-heading" roles="tab" id="heading-four">
                            <h4 class="panel-title">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse-four" aria-expanded="true" arial-controls="collapse-four">
                                    قسم الإلكترونيات
                                </a>
                            </h4>
                        </div>
                        <div id="collapse-four" class="panel-collapse collapse" roles="tabpanel" aria-labelledby="heading-four">
                            <div class="panel-body">
                                <ul class="list-unstyled">
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Elec_Cont.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة تحكم إلكترونى</a></li>
                                    <li><a href="AdminDashboard/uploads/QuestionsBank/Comp_ind.exe" title="Download"><i class="far fa-download fa-fw fa-lg"></i>مادة حاسب آلى</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- End Question 4 -->
                    
                </div>
            </div>
        </section>
        
        <!-- End FAQ Accordion -->

<?php
    include $tpl . 'footer.php'; 
    ob_end_flush();
?>