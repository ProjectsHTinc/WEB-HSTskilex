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
  <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<style>
.fa{
  color: #125ba1;
}
</style>

</head>

<body>
  <?php  $role=$this->session->userdata('user_role'); ?>
  <div class="container-scroller">
    <!-- partial:partials/_horizontal-navbar.html -->
    <nav class="navbar horizontal-layout col-lg-12 col-12 p-0">
      <div class="container d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top">
          <a class="navbar-brand brand-logo" href="<?php echo base_url(); ?>dashboard"><img src="<?php echo base_url(); ?>assets/logo.png" alt="logo" style="width:100%;"/></a>

        </div>

      </div>
      <div class="nav-bottom">
        <div class="container">
          <ul class="nav page-navigation">
            <li class="nav-item">
              <a href="<?php echo base_url(); ?>dashboard" class="nav-link"><i class="fa fa-desktop" aria-hidden="true"></i> &nbsp; <span class="menu-title">DASHBOARD</span></a>
            </li>
              <?php  if($role=='1' || $role=='2' || $role=='7'){ ?>
            <li class="nav-item mega-menu">
              <a href="#" class="nav-link"><i class="fa fa-bars" aria-hidden="true"></i></i>&nbsp;
                <span class="menu-title">Main Menu</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <div class="col-group-wrapper row">

                   <div class="col-group col-md-2 col-md-offset-1">
                     <p class="category-heading">Masters</p>
                     <ul class="submenu-item">
                       <!-- <li class="nav-item"><a class="nav-link" href="<?php echo base_url();  ?>masters/create_city">City </a></li> -->
                        <li class="nav-item"><a class="nav-link" href="<?php echo base_url();  ?>masters/create_category">Category </a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo base_url();  ?>masters/banner_list">Banners </a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo base_url();  ?>offers">Offers </a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo base_url();  ?>masters/tax_commission">Tax & Commission </a></li>
                        <?php if($role=='7' ||$role=='6' ||$role=='2'){ ?>
                        <?php  }else{ ?>
                         <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>home/create_staff">Create staff</a></li>
                       <?php  } ?>



                     </ul>
                   </div>

                     <div class="col-group col-md-2">
                       <p class="category-heading">List</p>
                       <ul class="submenu-item">
                         <?php if($role=='7' ||$role=='6' ||$role=='2'){ ?>
                         <?php  }else{ ?>
                     <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>home/get_all_staff">Staff</a></li>
                        <?php  } ?>
                     <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>home/get_all_provider_list">Associate </a></li>
                     <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>home/get_all_person_list">Expert </a></li>
                     <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>home/get_all_customer_details">Customers </a></li>
                     </ul>
                     </div>
                     <div class="col-group col-md-3">
                       <p class="category-heading">Recent Associates and Experts</p>
                       <ul class="submenu-item">
                         <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>verifyprocess/get_vendor_verify_list">Associate list </a></li>
                       </ul>
                     </div>





                </div>
              </div>
            </li>
            <?php   }else{ }?>
            <?php  if($role=='6'){ ?>
            <li class="nav-item">
              <a href="#" class="nav-link"><i class="fa fa-suitcase" aria-hidden="true"></i>&nbsp;
                <span class="menu-title">Masters</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <ul class="submenu-item">

                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url();  ?>masters/create_category">Category </a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url();  ?>masters/banner_list">Banners </a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url();  ?>offers">Offers </a></li>

                </ul>
              </div>
            </li>
            <?php  }else{} ?>

            <?php  if($role=='1' || $role=='2' || $role=='7'){ ?>
            <li class="nav-item">
              <a href="#" class="nav-link"><i class="fa fa-suitcase" aria-hidden="true"></i>&nbsp;
                <span class="menu-title">Service Orders</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <ul class="submenu-item">

                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>service_orders/pending_orders">Pending Orders</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>service_orders/ongoing_orders">OnGoing Orders</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>service_orders/completed_orders">Completed Orders</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>service_orders/cancelled_orders">Cancelled Orders</a></li>

                </ul>
              </div>
            </li>
            <?php  }else{} ?>
            <?php  if($role=='1' || $role=='2'){ ?>
            <li class="nav-item">
              <a href="#" class="nav-link"><i class="fa fa-university" aria-hidden="true"></i>&nbsp;<span class="menu-title">Transaction</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <ul class="submenu-item">

                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>transaction/daily_transaction">Daily transaction</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>transaction/day_wise_transaction">Date wise transaction</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>transaction/from_date_and_to_date_transactions">From & To date </a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>transaction/provider_based_transaction">Associate transactions</a></li>
                  <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>transaction/online_payment_history">Online Payment History</a></li>

                </li>


                </ul>
              </div>
                </li>
                <?php  }else{} ?>



            <li class="nav-item">
              <a href="#" class="nav-link"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp;<span class="menu-title">Settings</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <ul class="submenu-item">
                  <?php  if($role=='1' || $role=='2'){ ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>home/view_contact_form">Contact Box</a></li>
                  <?php }else{}?>
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
