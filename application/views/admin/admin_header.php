<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Skilex-Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/iconfonts/mdi/css/materialdesignicons.min.css">

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/style.css">
   <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/iconfonts/font-awesome/css/font-awesome.min.css" />
  <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/admin/images/favicon.png" />
  <script   src="https://code.jquery.com/jquery-3.4.1.js"   integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="  crossorigin="anonymous"></script>
  <script src="<?php echo base_url();  ?>assets/admin/js/main.js" ></script>
  <!-- <script src="<?php echo base_url();  ?>assets/admin/js/data-table.js"></script> -->
  <script src="<?php echo base_url(); ?>assets/admin/js/jquery.validate.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/admin/js/additional-methods.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>

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
              <a href="#" class="nav-link"><i class="link-icon mdi mdi-android-studio"></i><span class="menu-title">Users</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <div class="col-group-wrapper row">
                  <div class="col-group col-md-3">
                    <p class="category-heading">Staff</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>home/create_staff">Create Staff</a></li>
                      <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>home/get_all_staff">Staff list</a></li>
                    </ul>
                  </div>
                  <div class="col-group col-md-3">
                    <p class="category-heading">Service Provider</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="">List of Provider </a></li>


                    </ul>
                  </div>
                  <div class="col-group col-md-3">
                    <p class="category-heading">Service Person</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="">List of Service person </a></li>

                    </ul>
                  </div>
                </div>
              </div>
            </li>
            <li class="nav-item mega-menu">
              <a href="#" class="nav-link"><i class="link-icon mdi mdi-atom"></i><span class="menu-title">UI ELEMENTS</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <div class="col-group-wrapper row">
                  <div class="col-group col-md-4">
                    <div class="row">
                      <div class="col-12">
                        <p class="category-heading">Basic Elements</p>
                      </div>
                      <div class="col-md-6">
                        <ul class="submenu-item">
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/accordions.html">Accordion</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/buttons.html">Buttons</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/badges.html">Badges</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/breadcrumbs.html">Breadcrumbs</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/dropdowns.html">Dropdown</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/modals.html">Modals</a></li>
                        </ul>
                      </div>
                      <div class="col-md-6">
                        <ul class="submenu-item">
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/progress.html">Progress bar</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/pagination.html">Pagination</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/tabs.html">Tabs</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/typography.html">Typography</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/tooltips.html">Tooltip</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="col-group col-md-4">
                    <div class="row">
                      <div class="col-12">
                        <p class="category-heading">Advanced Elements</p>
                      </div>
                      <div class="col-md-6">
                        <ul class="submenu-item">
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/dragula.html">Dragula</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/carousel.html">Carousel</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/clipboard.html">Clipboard</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/context-menu.html">Context Menu</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/loaders.html">Loader</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/slider.html">Slider</a></li>
                        </ul>
                      </div>
                      <div class="col-md-6">
                        <ul class="submenu-item">
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/tour.html">Tour</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/popups.html">Popup</a></li>
                          <li class="nav-item"><a class="nav-link" href="pages/ui-features/notifications.html">Notification</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="col-group col-md-2">
                    <p class="category-heading">Table</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="pages/tables/basic-table.html">Basic Table</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/tables/data-table.html">Data Table</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/tables/js-grid.html">Js-grid</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/tables/sortable-table.html">Sortable Table</a></li>
                    </ul>
                  </div>
                  <div class="col-group col-md-2">
                    <p class="category-heading">Icons</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="pages/icons/flag-icons.html">Flag Icons</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/icons/font-awesome.html">Font Awesome</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/icons/simple-line-icon.html">Simple Line Icons</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/icons/themify.html">Themify Icons</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </li>
            <li class="nav-item mega-menu">
              <a href="#" class="nav-link"><i class="link-icon mdi mdi-flag-outline"></i><span class="menu-title">PAGES</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <div class="col-group-wrapper row">
                  <div class="col-group col-md-3">
                    <p class="category-heading">User Pages</p>
                    <ul class="submenu-item">
                      <li class="nav-item"><a class="nav-link" href="pages/samples/login.html">Login</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/samples/login-2.html">Login 2</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/samples/register.html">Register</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/samples/register-2.html">Register 2</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/samples/lock-screen.html">Lockscreen</a></li>
                      <li class="nav-item"><a class="nav-link" href="pages/samples/landing.html">Landing screen</a></li>
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
