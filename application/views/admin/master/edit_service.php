<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masters/create_category">Category </a></li>
                    <li class="breadcrumb-item"><a href="#">Service </a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Update Service</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Update Service </h4>
                  <?php foreach($res as $rows){} ?>
                  <form class="forms-sample" id="update_category" method="post" action="<?php echo base_url(); ?>masters/service_update" enctype="multipart/form-data">

                    <div class="form-group">
                      <label for="username">Service name (English)</label>
                      <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Service Name" value="<?php echo $rows->service_name; ?>">
                      <input type="hidden" class="form-control" id="service_id" name="service_id"  value="<?php echo base64_encode($rows->id*98765); ?>">
                      <input type="hidden" class="form-control" id="cat_old_img" name="cat_old_img"  value="<?php echo $rows->service_pic; ?>">

                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">Service Name(Tamil)</label>
                      <input type="text" class="form-control" id="service_ta_name" name="service_ta_name" placeholder="Service Tamil Name"  value="<?php echo $rows->service_ta_name; ?>">
                        <input type="hidden" class="form-control" id="sub_cat_id" name="sub_cat_id" placeholder="Service Tamil Name"  value="<?php echo $rows->sub_cat_id; ?>">
                    </div>
                    <div class="form-group">
                      <label for="latitude">Service New images</label>
                      <input type="file" class="form-control" id="service_pic" name="service_pic" placeholder="">
                    </div>


                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Status</label>
                      <select class="form-control form-control-sm" id="status" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>

                      </select>
                      <script>$('#status').val('<?php echo $rows->status; ?>');</script>
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Update Service</button>

                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="category_img" style="margin-top:200px;">
                <p>Old Image</p>
                <img src="<?php echo base_url(); ?>assets/category/<?php echo $rows->service_pic; ?>" style="width:200px;">
              </div>
            </div>


          </div>
        </div>
      </div>
    </div>
    <script>

    $('#update_category').validate({
    rules: {

        service_name: { required: true,
                  remote: {
                         url: "<?php echo base_url(); ?>masters/checkserviceexist/<?php echo $rows->id; ?>",
                         type: "post"
                      }
            },
        service_ta_name: { required: true,
                  remote: {
                         url: "<?php echo base_url(); ?>masters/checkservicetamilexist/<?php echo $rows->id; ?>",
                         type: "post"
                      }
         },
        service_pic: {required: false,extension: "jpg,jpeg,png" }
    },
    messages: {
        service_pic:{
            required :"Please Select Service Picture",extension:"File must be JPG OR PNG"
        },
        service_name: {
    					 required: "Please Enter Service Name.",
    					 remote: "Service Name  already in Exist!"
    							 },
         service_ta_name: {
               required: "Please Enter Service Tamil Name.",
               remote: "Service Tamil Name  Already in Exist!"
                   },

    }
    });
    </script>
