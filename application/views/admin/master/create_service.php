<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masters/create_category">Category</a></li>
              <li class="breadcrumb-item"><a href="#">Sub Category</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span> Services</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Create  Service  <br> <?php echo $this->uri->segment(4); ?> </h4>

                  <form class="forms-sample" id="create_service" method="post" action="<?php echo base_url(); ?>masters/service_creation" enctype="multipart/form-data">

                    <div class="form-group">
                      <label for="username">Service name (English)</label>
                      <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Category Name">
                      <input type="hidden" class="form-control" id="sub_cat_id" name="sub_cat_id" value="<?php echo $this->uri->segment(3); ?>">
                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">Service Name(Tamil)</label>
                      <input type="text" class="form-control" id="service_ta_name" name="service_ta_name" placeholder="Category Tamil Name" >
                    </div>
                    <div class="form-group">
                      <label for="latitude">Service Picture</label>
                      <input type="file" class="form-control" id="service_pic" name="service_pic" placeholder="">
                    </div>


                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Status</label>
                      <select class="form-control form-control-sm" id="status" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>

                      </select>
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Create Service</button>

                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">List of Service </h4>
              <table id="example" class="table table-striped table-bordered">
      <thead>
          <tr>
              <th>S.no</th>
              <th>Service</th>
              <th>Service Pciture</th>
              <th>Status</th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($res as $rows){ ?>


          <tr>
                <td><?php echo $i; ?></td>
              <td><?php echo $rows->service_name; ?> <br><br><?php echo $rows->service_ta_name; ?>
              </td>
              <td><img src="<?php echo base_url(); ?>assets/category/<?php echo $rows->service_pic; ?>" class="img-responsive" style="width:100px;    height: auto;"> </td>
                <td><?php if($rows->status=='Inactive'){ ?>
                <button type="button" class="btn btn-danger btn-fw">Inactive</button>
            <?php   }else{ ?>
              <button type="button" class="btn btn-success btn-fw">Active</button>
            <?php   }
               ?></td>
              <td><a href="<?php echo base_url(); ?>masters/get_service_edit/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                <!-- <a title="Add Service" href="<?php echo base_url(); ?>masters/create_service/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-plus-square"></i></a> -->
              </td>
          </tr>
        <?php  $i++;  }  ?>


      </tbody>

  </table>

</div>
</div>
</div>
          </div>
        </div>
      </div>
    </div>
    <script>
      $('#example').DataTable();
      $('#create_service').validate({
      rules: {

          service_name: { required: true,
                    remote: {
                           url: "<?php echo base_url(); ?>masters/checkservice",
                           type: "post"
                        }
              },
          service_ta_name: { required: true,
                    remote: {
                           url: "<?php echo base_url(); ?>masters/checkservicetamil",
                           type: "post"
                        }
           },
          service_pic: {required: true,extension: "jpg,jpeg,png" }
      },
      messages: {
          service_pic:{
              required :"Please Select Service Picture",extension:"File must be JPG OR PNG"
          },
          service_name: {
      					 required: "Please Enter Service.",
      					 remote: "Service Name  already in Exist!"
      							 },
           service_ta_name: {
                 required: "Please Enter Service Tamil Name.",
                 remote: "Service Tamil Name  Already in Exist!"
                     },

      }
      });
    </script>
