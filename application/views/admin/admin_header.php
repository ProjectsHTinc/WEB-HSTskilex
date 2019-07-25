<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Skilex-Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/iconfonts/mdi/css/materialdesignicons.min.css">

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/style.css">
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/icheck/skins/all.css"> -->
   <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/iconfonts/font-awesome/css/font-awesome.min.css" />
  <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/admin/images/favicon.png" />
  <script   src="<?php echo base_url(); ?>assets/admin/js/jquery.js"></script>
  <script src="<?php echo base_url();  ?>assets/admin/js/main.js" ></script>
  <!-- <script src="<?php echo base_url();  ?>assets/admin/js/data-table.js"></script> -->
  <script src="<?php echo base_url(); ?>assets/admin/js/jquery.validate.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/admin/js/additional-methods.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/admin/js/swal.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/admin/css/datatable.css"/>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/js/datatable.js"></script>
  <script src="<?php echo base_url(); ?>assets/admin/js/bootstrap-min.js"></script>
  <script src="<?php echo base_url(); ?>assets/admin/js/tether.js"></script>


  <!-- <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script> -->

</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_horizontal-navbar.html -->
    <nav class="navbar horizontal-layout col-lg-12 col-12 p-0">
      <div class="container d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top">
          <a class="navbar-brand brand-logo" href="<?php echo base_url(); ?>dashboard"><img src="<?php echo base_url(); ?>assets/logo.png" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="<?php echo base_url(); ?>dashboard"><img src="<?php echo base_url(); ?>assets/logo.png" alt="logo"/></a>
        </div>

      </div>
      <div class="nav-bottom">
        <div class="container">
          <ul class="nav page-navigation">
            <li class="nav-item">
              <a href="<?php echo base_url(); ?>dashboard" class="nav-link"><i class="link-icon mdi mdi-television"></i><span class="menu-title">DASHBOARD</span></a>
            </li>

            <li class="nav-item mega-menu">
              <a href="#" class="nav-link"><i class="link-icon mdi mdi-android-studio"></i><span class="menu-title">Main Menu</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <div class="col-group-wrapper row">
                   <div class="col-group col-md-2 col-md-offset-1">
                     <p class="category-heading">Master Creation</p>
                     <ul class="submenu-item">
                       <!-- <li class="nav-item"><a class="nav-link" href="<?php echo base_url();  ?>masters/create_city">City </a></li> -->
                        <li class="nav-item"><a class="nav-link" href="<?php echo base_url();  ?>masters/banner_list">Banners </a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo base_url();  ?>offers">Offers </a></li>
                       <li class="nav-item"><a class="nav-link" href="<?php echo base_url();  ?>masters/create_category">Category </a></li>
                     </ul>
                   </div>
                  <div class="col-group col-md-2">
                    <p class="category-heading">Staff</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>home/create_staff">Create staff</a></li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>home/get_all_staff">Staff list</a></li>
                    </ul>
                  </div>
                  <div class="col-group col-md-2">
                    <p class="category-heading">Verify Provider</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>verifyprocess/get_vendor_verify_list">Provider list </a></li>


                    </ul>
                  </div>
                  <div class="col-group col-md-2">
                    <p class="category-heading">Service Provider</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="">List of Provider </a></li>


                    </ul>
                  </div>
                  <div class="col-group col-md-2">
                    <p class="category-heading">Service Person</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="">List of Service person </a></li>

                    </ul>
                  </div>
                  <div class="col-group col-md-2">
                    <p class="category-heading">Customers</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>home/get_all_customer_details">List of Customers </a></li>

                    </ul>
                  </div>
                </div>
              </div>
            </li>


            <!-- <li class="nav-item mega-menu">
              <a href="#" class="nav-link"><i class="link-icon mdi mdi-flag-outline"></i><span class="menu-title">Orders</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <div class="col-group-wrapper row">
                  <div class="col-group col-md-3">
                    <p class="category-heading">Services Orders</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="#">Waiting Orders</a></li>
                      <li class="nav-item"><a class="nav-link" href="#">Rejected Orders</a></li>
                      <li class="nav-item"><a class="nav-link" href="#">Completed Orders</a></li>
                      <li class="nav-item"><a class="nav-link" href="#">Ongoing  Orders</a></li>

                    </ul>
                  </div>
                  <div class="col-group col-md-3">
                    <p class="category-heading">Error Pages</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="pages/samples/error-400.html">400</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/samples/error-404.html">404</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/samples/error-500.html">500</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/samples/error-505.html">505</a></li>
                    </ul>
                  </div>
                  <div class="col-group col-md-3">
                    <p class="category-heading">E-commerce</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="pages/samples/invoice.html">Invoice</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/samples/pricing-table.html">Pricing Table</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/samples/orders.html">Orders</a></li>
                    </ul>
                  </div>
                  <div class="col-group col-md-3">
                    <div class="row">
                      <div class="col-12">
                        <p class="category-heading">Layout</p>
                        <ul class="submenu-item">
                          <li class="nav-item"><a class="nav-link" href="pages/layouts/rtl.html">RTL Layout</a></li>
                        </ul>
                      </div>
                      <div class="col-12 mt-3">
                        <p class="category-heading">Documentation</p>
                        <ul class="submenu-item">
                          <li class="nav-item"><a class="nav-link" href="pages/documentation.html">Documentation</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </li> -->
            <li class="nav-item">
              <a href="#" class="nav-link"><i class="link-icon mdi mdi-asterisk"></i><span class="menu-title">Service Orders</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <ul class="submenu-item">

                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>service_orders/pending_orders">Pending Orders</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>service_orders/ongoing_orders">OnGoing Orders</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Completed Orders</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Ongoing  Orders</a></li>

                </ul>
              </div>
            </li>
            <li class="nav-item mega-menu">
              <a href="#" class="nav-link"><i class="link-icon mdi mdi-android-studio"></i><span class="menu-title">FORMS</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <div class="col-group-wrapper row">
                  <div class="col-group col-md-3">
                    <p class="category-heading">Basic Elements</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Basic Elements</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/forms/advanced_elements.html">Advanced Elements</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/forms/validation.html">Validation</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/forms/wizard.html">Wizard</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/forms/text_editor.html">Text Editor</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/forms/code_editor.html">Code Editor</a></li>
                    </ul>
                  </div>
                  <div class="col-group col-md-3">
                    <p class="category-heading">Charts</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="pages/charts/chartjs.html">Chart Js</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/charts/morris.html">Morris</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/charts/flot-chart.html">Flaot</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/charts/google-charts.html">Google Chart</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/charts/sparkline.html">Sparkline</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/charts/c3.html">C3 Chart</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/charts/chartist.html">Chartist</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/charts/justGage.html">JustGage</a></li>
                    </ul>
                  </div>
                  <div class="col-group col-md-3">
                    <p class="category-heading">Maps</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="pages/maps/mapeal.html">Mapeal</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/maps/vector-map.html">Vector Map</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/maps/google-maps.html">Google Map</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link"><i class="link-icon mdi mdi-asterisk"></i><span class="menu-title">Setting</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <ul class="submenu-item">

                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>profile">Profile</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>change_password">Password</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>logout">Logout</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
