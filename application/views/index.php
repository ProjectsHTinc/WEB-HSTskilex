<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>SkilEx</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" href="<?php echo base_url(); ?>assets/images/favicon.png">
    <link rel="shortcut icon" type="image/ico" href="<?php echo base_url(); ?>assets/images/favicon.png" />
    <!-- Plugin-CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/normalize.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/slick-theme.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/icofont.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
    <!-- Main-Stylesheets -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/helper.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/theme.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/style.css">
	 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/demo.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="<?php echo base_url(); ?>assets/js/vendor/modernizr-2-8-3-min.js"></script>

</head>

<body data-spy="scroll" data-target=".mainmenu-area" data-offset="50">
	 <div class="preloader">
        <div class="wrap">
            <div class="loader"></div>
        </div>
    </div>

    <div class="mainmenu-area transparent" data-spy="affix" data-offset-top="197">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 row-flex">
                    <div class="site-brand">
                        <a href="<?php echo base_url(); ?>" class="logo"><img src="<?php echo base_url(); ?>assets/images/logo-light.png" alt=""></a>
                    </div>
                    <!-- <button class="burger">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>
                    <div class="mainmenu">
                      <ul class="nav">
                          <li><a href="index.html">Home</a></li>
                          <li><a href="#service-area">Service</a></li>
                          <li><a href="#about-area">About</a></li>
                          <li><a href="#team-area">Team</a></li>
                          <li><a href="#contact-area">Contact</a></li>
                      </ul>
                    </div> -->
                    <button type="button" class="navbar-toggle burger" data-toggle="collapse" data-target="#navigationbar">
                       <span class="sr-only">Toggle navigation</span>
                       <span class="bar"></span>
                       <span class="bar"></span>
                       <span class="bar"></span>
                    </button>
                    <div class="collapse navbar-collapse mainmenu" id="navigationbar" >
                        <ul class="nav navbar-nav">
                          <li><a href="<?php echo base_url(); ?>">Home</a></li>
                          <li><a href="#service-area">Services</a></li>
                          <li><a href="#about-area">About</a></li>
                          <li><a href="#feature-area">FAQ</a></li>
                          <li><a href="#contact-area">Contact</a></li>
                          <!-- <li><a href="http://happysanz.net/skilex_vendor/">Vendor Registration</a></li> -->
                        </ul>
                   </div>


                </div>
            </div>
        </div>
    </div>
    <!-- Mainmenu-Area / -->
    <!-- Header-Area -->
    <header class="header-area" id="home-area">
        <!-- Wave-Content -->
        <div class="waveWrapper waveAnimation">
            <div class="waveWrapperInner bgTop">
                <div class="wave waveTop" style="background-image: url('<?php echo base_url(); ?>assets/images/wave-top.png')"></div>
            </div>
            <div class="waveWrapperInner bgMiddle">
                <div class="wave waveMiddle" style="background-image: url('<?php echo base_url(); ?>assets/images/wave-mid.png')"></div>
            </div>
            <div class="waveWrapperInner bgBottom">
                <div class="wave waveBottom" style="background-image: url('<?php echo base_url(); ?>assets/images/wave-bot.png')"></div>
            </div>
        </div>
        <!-- Wave-Content / -->
        <!-- Bubble-Content -->
        <div class="bubble-animate">
            <div class="circle small square1"></div>
            <div class="circle small square2"></div>
            <div class="circle small square3"></div>
            <div class="circle small square4"></div>
            <div class="circle small square5"></div>
            <div class="circle medium square1"></div>
            <div class="circle medium square2"></div>
            <div class="circle medium square3"></div>
            <div class="circle medium square4"></div>
            <div class="circle medium square5"></div>
            <div class="circle large square1"></div>
            <div class="circle large square2"></div>
            <div class="circle large square3"></div>
            <div class="circle large square4"></div>
        </div>
        <!-- Bubble-Content / -->

        <!-- Header-Content -->
        <div class="container">
            <div class="row ">
                <div class="col-xs-12 col-md-8">
                    <!-- Header-Text -->
                    <div class="text-box white-box  service_content">
                        <h2 class="title wow fadeInUp" data-wow-delay="0.3s">Services that are affordable <br>
From people that are capable</h2>


                    </div>
                    <!-- Header-Text / -->
                    <div class="space-40"></div>
                    <a href="#" class="bttn-1 wow fadeInRight" data-wow-delay="0.8s"><i class="fa fa-download" aria-hidden="true"></i>  Download on App Store
</a>
                    <a href="#" class="bttn-1 wow fadeInRight" data-wow-delay="1s"><i class="fa fa-download" aria-hidden="true"></i>  Download on Play Store </a>
                </div>
                <div class="col-xs-12 col-md-4 hidden-xs hidden-sm">
                    <!-- Single-Screen-Image -->
                    <figure class="single-image-slide">
                        <div>
                            <img src="<?php echo base_url(); ?>assets/images/screen-1.png" alt="">
                        </div>
                        <div>
                            <img src="<?php echo base_url(); ?>assets/images/screen-2.png" alt="">
                        </div>
                        <div>
                            <img src="<?php echo base_url(); ?>assets/images/screen-3.png" alt="">
                        </div>

                    </figure>
                    <!-- Single-Screen-Image / -->
                </div>
            </div>
        </div>
        <!-- Header-Content / -->
    </header>
    <!-- Header-Area / -->
    <!-- Service-area -->
    <section class="service-area section-padding" id="service-area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <div class="title-box">
                        <h5 class="top-title">Our Services</h5>
                        <h2 class="title"></h2>

                        <div class="space-70"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="0.3s" >
                        <div class="box-icon">
                            <img src="<?php echo base_url(); ?>assets/images/service_1.png">
                        </div>
                        <h3 class="title">Electrical Services</h3>
                      <p class="ser_desc">From repairs and fixes to installation of appliances and security systems</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="0.5s" >
                        <div class="box-icon">
                          <img src="<?php echo base_url(); ?>assets/images/service_2.png">
                        </div>
                        <h3 class="title">Plumbing</h3>
                      <p class="ser_desc">From repairs and fixes to accessories</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="0.7s" >
                        <div class="box-icon">
                            <img src="<?php echo base_url(); ?>assets/images/service_3.png">
                        </div>
                        <h3 class="title">Carpentry</h3>
                      <p class="ser_desc">From woodworks to wood carving</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_20.png">
                        </div>
                        <h3 class="title">Clinical Services(Nurse)</h3>
                      <p class="ser_desc">Dressings and injections</p>
                    </div>
                </div>
                <!-- <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="0.9s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_4.png">
                        </div>
                        <h3 class="title">Cleaning Toilet</h3>
                      <p></p>
                    </div>
                </div> -->
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.1s" >
                        <div class="box-icon">
                            <img src="<?php echo base_url(); ?>assets/images/service_5.png">
                        </div>
                        <h3 class="title">Janitorial Services</h3>
                      <p class="ser_desc">From cleaning and sanitation to overall maintenance of buildings</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_6.png">
                        </div>
                        <h3 class="title">Home Cleaning </h3>
                      <p class="ser_desc">From kitchen and living room to full home deep cleaning</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_7.png">
                        </div>
                        <h3 class="title">AC Services</h3>
                      <p class="ser_desc">From dry and wet services to overall  maintenance</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                            <img src="<?php echo base_url(); ?>assets/images/service_8.png">
                        </div>
                        <h3 class="title">Gardening</h3>
                      <p class="ser_desc">From mowing and blowing to patio designing</p>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_9.png">
                        </div>
                        <h3 class="title">Driving Services</h3>
                      <p class="ser_desc">From local to out-of-town trips on hourly and daily basis</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_10.png">
                        </div>

                          <h3 class="title">Cooking</h3>
                      <p class="ser_desc">From local cuisines to continental</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_11.png">
                        </div>
                          <h3 class="title">Ups Services</h3>

                      <p class="ser_desc">From repairs and fixes to installation and maintenance</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_12.png">
                        </div>
                          <h3 class="title">Personal Care </h3>

                      <p class="ser_desc">From pedicure and manicure to party and bridal makeup</p>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_13.png">
                        </div>
                        <h3 class="title">Home Appliance Services</h3>

                      <p class="ser_desc">From kitchen appliances to home automation</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_14.png">
                        </div>
                        <h3 class="title">Pest Control</h3>
                      <p class="ser_desc">From insects to rodents</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_15.png">
                        </div>
                      <h3 class="title">Painting Services</h3>
                      <p class="ser_desc">Both interior and exterior</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_16.png">
                        </div>
                         <h3 class="title">Computer Services</h3>
                      <p class="ser_desc">From software and router installations to hardware repairs and services</p>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_17.png">
                        </div>
                        <h3 class="title">  Tutor-Home Tuition</h3>

                      <p class="ser_desc">From Classes 1 to 12  of all boards and Engineering Mathematics</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_18.png">
                        </div>
                          <h3 class="title">Physiotheraphy</h3>

                      <p class="ser_desc">From sports injuries to surgical rehabilitation</p>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                        <div class="box-icon">
                              <img src="<?php echo base_url(); ?>assets/images/service_19.png">
                        </div>
                          <h3 class="title">Car Wash</h3>

                      <p class="ser_desc">Interior, exterior, puncture services,and maintenance</p>
                    </div>
                </div>  <div class="col-xs-12 col-md-3">
                      <div class="service-box wow fadeInRight" data-wow-delay="1.3s" >
                          <div class="box-icon">
                                <img src="<?php echo base_url(); ?>assets/images/service_21.png">
                          </div>
                        <h3 class="title">Personalized Fitness Tutor</h3>
                        <p class="ser_desc">From fitness training to dietary classes</p>
                      </div>
                  </div>









            </div>
        </div>
    </section>
    <!-- Service-area / -->
    <!-- About-area -->
    <section class="about-area section-padding section-bg" id="about-area">
        <div class="container">
            <div class="row row-flex">
                <div class="col-xs-12 col-md-6 hidden-xs hidden-sm text-center">
                    <figure class="single-image wow fadeInDown" data-wow-delay="0.5s" >
                        <img src="<?php echo base_url(); ?>/assets/images/screen-1.png" alt="" style="height:500px;">
                    </figure>
                </div>
                <div class="col-xs-12 col-md-6 wow fadeInRight">
                    <div class="title-box left">
                        <h2 class="title">Who are we?</h2>
                        <p class="title">SkilEx is an online platform</p>
                        <p class="desc">To get the best professionals to sort all your home needs.
                        <br>  <br>
                        With the help of SkilEx App, you can book various household services at time and place of your own choice. <br>  <br>
                          we simplify your everyday living with a variety of   <br> at home-services like Electrical, Plumbing, house cleaning, maintenance, home repair, and several other great service.<br>  <br>
                          These services are given by the professionals who are qualified to do the job in an accurate way possible.
                      </p>

                    </div>
                    <div class="space-30"></div>

                </div>
            </div>
        </div>
    </section>
    <!-- About-area / -->

    <!-- Feature-area -->
    <section class=" section-padding-top" id="feature-area" style="margin-bottom:0px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <div class="title-box box-white">

                        <h2 class="title">Frequently Asked Questions</h2>


                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-12 wow fadeInUp" data-wow-delay="0.3s" >
                    <div class="feature-box">

                        <h3 class="title">1.Why SkilEx?</h3>
                        <p><ul>
                          <li>One stop solution for all your Home Service requirements.</li>
                          <li>Top quality service which a local vendor will not provide.</li>
                          <li>Efficient feedback and complaint redressal system.</li>
                        </ul></p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-12 wow fadeInUp" data-wow-delay="0.4s" >
                    <div class="feature-box">

                        <h3 class="title">2.What are the services you provide?</h3>
                        <p>We provide all services related to maintenance of your home and upkeep of your premises so that you can focus on your business. Services include plumbing, electrical, carpentry, cleaning, pest control, painting, appliance repair and maintenance and more.</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-12 wow fadeInUp" data-wow-delay="0.4s" >
                    <div class="feature-box">
                        <h3 class="title">3.Which business you serve?</h3>
                        <p>Our clients are spread across different industry verticals, housing societies and more. If still not sure, get in touch with us and we will take care of your requirements.</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-12 wow fadeInUp" data-wow-delay="0.4s" >
                    <div class="feature-box">
                        <h3 class="title">4.How does it work?</h3>
                        <p>Once you reach out to us, our SkilEx partner will meet you to get a clear understanding of your requirements. Our experts will visit your premises for an inspection to get a clear scope of work. Post this we will share the custom estimate with detailed scope of work and the requested service will be performed.</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-12 wow fadeInUp" data-wow-delay="0.4s" >
                    <div class="feature-box">
                        <h3 class="title">5.What are the rates?</h3>
                        <p>Our rates depend on the scope of work after inspection and your actual requirements. There are no hidden costs and the rate card will be provided for each Service.</p>
                    </div>
                </div>




            </div>

        </div>
    </section>
    <!-- Feature-area / -->


    <!-- Subscribe-area -->
    <div class="section-padding subscribe-area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
                    <div class="title-box">
                        <h5 class="top-title">Connect With Our Community</h5>

                        <div class="space-40"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="subscribe">
                        <form id="newletter_form" method="post" action="">
                            <input type="email" name="email" placeholder="Enter Your Email Address" id="mc-email" class="input-box" required>
                            <button class="bttn-4" type="submit">Send <i class="icofont-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Subscribe-area / -->

    <!-- Contact-Area -->
    <section class="contact-area section-padding" id="contact-area">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div class="title-box left">
                        <!-- <h5 class="top-title wow fadeInUp" data-wow-delay="0.3s" >Social Marketing &amp; Analytics</h5> -->
                        <h2 class="title wow fadeInUp" data-wow-delay="0.5s" >Stay in touch</h2>

                    </div>
                    <div class="space-30"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="contact-info">
                                <li class="wow fadeInUp" data-wow-delay="0.9s" ><span class="icon"><i class="icofont-home"></i></span>Virtual Automation Services Private Ltd ("SkilEx") <br>30/6, New Damunagar,<br> Pappanaickenpalayam, <br>Coimbatore-641037.</li>
                                <li class="wow fadeInUp" data-wow-delay="1.1s" ><span class="icon"><i class="icofont-envelope-open"></i></span>dhanasekar@virtualgroup.net.in</li>
                                <li class="wow fadeInUp" data-wow-delay="1.3s" ><span class="icon"><i class="icofont-telephone"></i></span>+91 984 321 8272</li>
                                <!-- <li class="wow fadeInUp" data-wow-delay="1.5s" ><span class="icon"><i class="icofont-globe"></i></span>www.skilex.com</li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="space-60 hidden visible-xs visible-sm"></div>
                </div>
                <div class="col-xs-12 col-md-7">
                    <!-- Contact-Form -->
                    <form class="contact-form" id="contact_form" method="post" acction="">
                        <div class="form-double">
                            <div class="form-box">
                                <input type="text" name="name" id="name" class="input-box" placeholder="Name"  >
                            </div>
                            <div class="form-box left">
                                <input type="email" name="email" id="email" placeholder="Email" class="input-box" >
                            </div>
                        </div>
                        <div class="form-box">
                            <input type="text" name="phone_number" id="phone_number" placeholder="Phone Number" class="input-box" >
                        </div>
                        <div class="form-box">
                            <input type="text" name="subject" id="form-subject" placeholder="Subject" class="input-box" >
                        </div>
                        <div class="form-box">
                            <textarea class="input-box" id="message" placeholder="Message" cols="30" rows="4" name="message" ></textarea>
                        </div>
                        <div class="form-box">
                            <button class="bttn-4" type="submit">Send Message <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                        </div>
                    </form>
                    <!-- Contact-Form / -->
                </div>
            </div>
        </div>
    </section>
    <!-- Contact-Area / -->


    <!-- Footer-Area -->
    <footer class="footer-area gray-bg">

        <!-- Footer-Top-Content -->
        <!-- Footer-Bottom-Content-->
        <div class="footer-bottom">
            <div class="container">
                <div class="row row-flex">
                    <div class="col-xs-12 col-sm-6">
                        <div class="widget footer-widget">
                            <p><a href="https://happysanztech.com/" target="_blank">Developed by Happy Sanz Tech.</a></p>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 text-right">
                        <div class="space-10 hidden visible-xs"></div>
                        <div class="widget footer-widget">
                            <div class="social-menu">
                                <!-- <a href="#"><i class="icofont-facebook"></i></a>
                                <a href="#"><i class="icofont-twitter"></i></a>
                                <a href="#"><i class="icofont-linkedin"></i></a>
                                <a href="#"><i class="icofont-instagram"></i></a>
                                <a href="#"><i class="icofont-dribbble"></i></a> -->
                            </div>
                        </div>
                        <ul class="content_links">
                          <li><a href="<?php echo base_url(); ?>terms">Terms & Conditions </a></li> &nbsp;
                          <li><a href="<?php echo base_url(); ?>privacy">Privacy Policy </a></li> &nbsp;
                          <li><a href="<?php echo base_url(); ?>refund">Refund Policy </a></li>
                       </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer-Bottom-Content /-->
    </footer>
    <!-- Footer-Area / -->
    <!--Vendor JS-->

    <script src="<?php echo base_url(); ?>assets/js/vendor/jquery-1-12-4-min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/vendor/bootstrap-min.js"></script>
    <!--Plugin JS-->
    <script src="<?php echo base_url(); ?>assets/js/slick-min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/scrollUp-min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/YTPlayer.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/wow-min.js"></script>
    <!--Active JS-->
    <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/additional-methods.min.js"></script>
    <script>

    $('#contact_form').validate({
    rules: {
        phone_number: {
            required: true,digits:true,minlength:10,maxlength:10
        },
        name: {
            required: true
        },
        email: {
            required: true,email:true
        },
        subject: {
            required: true
        },
        message: {
            required: true
        }

    },
    messages: {
        phone_number: {
          required:"Please Enter Mobile number",
          maxlength:"Maximum 10 digits",
          minlength:"Minimum 10 digits"
        },
        name: {
          required:"Please Enter Name"
        },
        email: {
          required:"Please Enter Email",email:"Please Enter Valid Email"
        },
        subject: {
          required:"Please Enter Subject"
        },
        message: {
          required:"Please Enter Message"
        },
    },
    submitHandler: function(form) {
    $.ajax({
               url: "<?php echo base_url(); ?>home/contact_form",
               type: 'POST',
               data: $('#contact_form').serialize(),
               dataType: "json",
               success: function(response) {
                  var stats=response.status;
                   if (stats=="success") {
                     alert("Thank you for contacting us. We will get back to you soon.");
                     setTimeout(function(){
                        location.reload();
                    }, 1000)
                 }else{
                    alert(stats);
                     }
               }
           });
         }
    });

    $('#newletter_form').validate({
    rules: {
        email: {
            required: true,email:true
        }
    },
    messages: {

        email: {
          required:"Please Enter Email",email:"Please Enter Valid Email"
        }
    },
    submitHandler: function(form) {
    $.ajax({
               url: "<?php echo base_url(); ?>home/newsletter_form",
               type: 'POST',
               data: $('#newletter_form').serialize(),
               dataType: "json",
               success: function(response) {
                  var stats=response.status;
                   if (stats=="success") {
                     alert("Thank you for subscribing! ");
                     setTimeout(function(){
                        location.reload();
                    }, 1000)
                 }else{
                   alert(stats);
                     }
               }
           });
         }
    });


    </script>
</body>

</html>
