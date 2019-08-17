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
            <div class="auto-form-wrapper" id="forgot_password_mobile_number_section">
              <form action="#" method="post" id="forgot_password_form">
                  <center> <img src="<?php echo base_url(); ?>assets/logo.png"> </center>
                  <br>
                <div class="form-group">
                  <label for="phone_number">Enter Mobile Number</label>
                  <input id="phone_number" class="form-control" name="phone_number"  type="text" >
                </div>
                <p id="res"></p>
                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block">Reset Password</button>
                </div>
                <div class="form-group d-flex justify-content-center">
                    <a href="<?php echo base_url(); ?>login" class="text-small forgot-password text-black">Back to Login</a>
                </div>
              </form>
            </div>
            <div class="auto-form-wrapper" id="reset_otp_form_section">
              <form action="#" method="post" id="reset_otp_form">
                  <center> <img src="<?php echo base_url(); ?>assets/logo.png"> </center>
                <div class="form-group">
                  <!-- <?php echo $this->input->cookie('cookie_phone');?> -->
                  <label for="phone_number_otp">Enter OTP </label>
                  <input id="phone_number_otp" class="form-control" name="phone_number_otp"  type="text" >
                </div>
                <p id="res_otp"></p>
                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block">Submit</button>
                </div>

              </form>
            </div>


            <div class="auto-form-wrapper" id="password_form_section">
              <form action="#" method="post" id="reset_password_form">
                  <center> <img src="<?php echo base_url(); ?>assets/logo.png"> </center>
                <div class="form-group">
                  <label for="new_password">New Password </label>
                  <input id="new_password" class="form-control" name="new_password"  type="text" >
                </div>
                <div class="form-group">
                  <label for="confrim_password">Confrim Password </label>
                  <input id="confrim_password" class="form-control" name="confrim_password"  type="text" >
                </div>

                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block">Submit</button>
                </div>

              </form>
            </div>





          </div>
        </div>
      </div>







    </div>
  </div>


</body>
<script>
$('#forgot_password_mobile_number_section').show();
$('#reset_otp_form_section').hide();
$('#password_form_section').hide();
</script>

</html>
