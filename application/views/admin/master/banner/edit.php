<?php  $role=$this->session->userdata('user_role'); ?>
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Banners</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <?php foreach($res as $rows){} ?>
                  <h4 class="card-title">Edit Banner </h4>

                  <form class="forms-sample" id="create_banner" method="post" action="<?php echo base_url(); ?>masters/update_banner" enctype="multipart/form-data">

                    <div class="form-group">
                      <label for="username">Title</label>
                      <input type="text" class="form-control" id="banner_title" name="banner_title" placeholder="Banner Title" value="<?php echo $rows->banner_title; ?>">

                    <input type="hidden" class="form-control" id="ban_id" name="ban_id"  value="<?php echo base64_encode($rows->id*98765); ?>">
                    <input type="hidden" class="form-control" id="banner_old_img" name="banner_old_img"  value="<?php echo $rows->banner_img; ?>">
                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">Banner image</label>
                      <input type="file" class="form-control" id="banner_img" name="banner_img" placeholder="City Tamil Name" >
                    </div>
                    <?php if($role=='7'|| $role=='6'){ ?>

                  <?php  }else{ ?>
                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Status</label>
                      <select class="form-control form-control-sm" id="status" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>

                      </select>
                      <script>$('#status').val(<?php echo $rows->status; ?>)</script>
                    </div>
                  <?php  } ?>

                    <button type="submit" class="btn btn-primary mr-2">Update</button>

                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <p>Current banner image</p>
              <img src="<?php echo base_url(); ?>assets/banners/<?php echo $rows->banner_img; ?>" style="width:200px;">
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>

      $('#create_banner').validate({
      rules: {

          banner_title: { required: true},
          status: { required: true},
          banner_img: {required: false,extension: "jpg,jpeg,png" }
      },
      messages: {
          banner_img:{
              required :"Select  Picture",extension:"File must be JPG OR PNG"
          },
          banner_title: {
                 required: "Enter Banner title.",
                 remote: "Banner title  already in Exist!"
                     },
           status: {required: "select status." },

      }
      });
    </script>
