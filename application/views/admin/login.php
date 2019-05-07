<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>SkilEx-Admin</title>
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendors/css/vendor.bundle.addons.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/style.css">
  <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/admin/images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
        <div class="row w-100 mx-auto">
          <div class="col-lg-4 mx-auto">
            <div class="auto-form-wrapper">
              
              <form action="#">
                <div class="form-group">
                  <label class="label">Username</label>
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Username">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="mdi mdi-check-circle-outline"></i></span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="label">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" placeholder="*********">
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="mdi mdi-check-circle-outline"></i></span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <button class="btn btn-primary submit-btn btn-block">Login</button>
                </div>
                <div class="form-group d-flex justify-content-between">

                  <a href="#" class="text-small forgot-password text-black">Forgot Password</a>
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
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="<?php echo base_url(); ?>assets/admin/vendors/js/vendor.bundle.base.js"></script>
  <script src="<?php echo base_url(); ?>assets/admin/vendors/js/vendor.bundle.addons.js"></script>
  <script src="<?php echo base_url(); ?>assets/admin/js/template.js"></script>
</body>
</html>
