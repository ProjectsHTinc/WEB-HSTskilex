<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>SkilEx-Admin</title>
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/css/vendor.bundle.addons.css"> -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/style.css">
  <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/admin/images/favicon.png" />
  <script   src="<?php echo base_url(); ?>assets/admin/js/jquery.js"></script>
  <script src="<?php echo base_url();  ?>assets/admin/js/main.js" ></script>
  <script src="<?php echo base_url(); ?>assets/admin/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/additional-methods.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/admin/js/swal.js"></script>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
        <div class="row w-100 mx-auto">
          <div class="col-lg-4 mx-auto">
            <div class="auto-form-wrapper">


              <form action="#" method="post" id="forgot_password_form">
                  <center> <img src="<?php echo base_url(); ?>assets/logo.png"> </center>

                <div class="form-group">
                        <label for="email">Enter Email</label>
                        <input id="email" class="form-control" name="email"  type="text" >
                </div>

                <p id="res"></p>

                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block">Reset Password</button>
                </div>
                <div class="form-group d-flex justify-content-between">

                    <a href="<?php echo base_url(); ?>login" class="text-small forgot-password text-black">Back to Login</a>
                </div>


              </form>
            </div>


          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>


</body>

</html>
