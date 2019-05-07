<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>LibertyUI Premium Bootstrap Admin Dashboard Template</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/style.css">
  <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/admin/images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_horizontal-navbar.html -->
    <nav class="navbar horizontal-layout col-lg-12 col-12 p-0">
      <div class="container d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top">
          <a class="navbar-brand brand-logo" href="index.html"><img src="images/logo-inverse.svg" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="index.html"><img src="images/logo-mini.svg" alt="logo"/></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center">
          <form class="search-field ml-auto" action="#">
            <div class="form-group mb-0">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                </div>
                <input type="text" class="form-control">
              </div>
            </div>
          </form>
          <ul class="navbar-nav navbar-nav-right mr-0">
            <li class="nav-item dropdown ml-4">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count bg-warning">4</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                <a class="dropdown-item py-3">
                  <p class="mb-0 font-weight-medium float-left">You have 4 new notifications
                  </p>
                  <span class="badge badge-pill badge-inverse-info float-right">View all</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-inverse-success">
                      <i class="mdi mdi-alert-circle-outline mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal text-dark mb-1">Application Error</h6>
                    <p class="font-weight-light small-text mb-0">
                      Just now
                    </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-inverse-warning">
                      <i class="mdi mdi-comment-text-outline mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal text-dark mb-1">Settings</h6>
                    <p class="font-weight-light small-text mb-0">
                      Private message
                    </p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-inverse-info">
                      <i class="mdi mdi-email-outline mx-0"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <h6 class="preview-subject font-weight-normal text-dark mb-1">New user registration</h6>
                    <p class="font-weight-light small-text mb-0">
                      2 days ago
                    </p>
                  </div>
                </a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                <img class="img-xs rounded-circle" src="images/faces/face1.jpg" alt="Profile image">
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <a class="dropdown-item p-0">
                  <div class="d-flex border-bottom w-100">
                    <div class="py-3 px-4 d-flex align-items-center justify-content-center flex-grow-1"><i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i></div>
                    <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right flex-grow-1"><i class="mdi mdi-account-outline mr-0 text-gray"></i></div>
                    <div class="py-3 px-4 d-flex align-items-center justify-content-center flex-grow-1"><i class="mdi mdi-alarm-check mr-0 text-gray"></i></div>
                  </div>
                </a>
                <a class="dropdown-item mt-2">
                  Manage Accounts
                </a>
                <a class="dropdown-item">
                  Change Password
                </a>
                <a class="dropdown-item">
                  Check Inbox
                </a>
                <a class="dropdown-item">
                  Sign Out
                </a>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </div>
      <div class="nav-bottom">
        <div class="container">
          <ul class="nav page-navigation">
            <li class="nav-item">
              <a href="index.html" class="nav-link"><i class="link-icon mdi mdi-television"></i><span class="menu-title">DASHBOARD</span></a>
            </li>
            <li class="nav-item">
              <a href="pages/widgets.html" class="nav-link"><i class="link-icon mdi mdi-apple-safari"></i><span class="menu-title">WIDGETS</span></a>
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
              <a href="#" class="nav-link"><i class="link-icon mdi mdi-asterisk"></i><span class="menu-title">APPS</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <ul class="submenu-item">
                  <li class="nav-item"><a class="nav-link" href="pages/apps/email.html">Email</a></li>
                  <li class="nav-item"><a class="nav-link" href="pages/apps/calendar.html">Calendar</a></li>
                  <li class="nav-item"><a class="nav-link" href="pages/apps/todo.html">Todo List</a></li>
                  <li class="nav-item"><a class="nav-link" href="pages/apps/gallery.html">Gallery</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
