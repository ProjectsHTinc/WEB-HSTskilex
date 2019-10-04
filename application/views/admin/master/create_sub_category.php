<?php  $role=$this->session->userdata('user_role'); ?>
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Category</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span> Sub Category</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Create  Sub Category  <br> <?php  $string =$this->uri->segment(4);
                  echo preg_replace("/[^a-zA-Z]/", " ", $string) ?>  </h4>

                  <form class="forms-sample" id="create_sub_category" method="post" action="<?php echo base_url(); ?>masters/sub_category_creation" enctype="multipart/form-data">

                    <div class="form-group">
                      <label for="username">Sub Category name (English)</label>
                      <input type="text" class="form-control" id="main_cat_name" name="sub_cat_name" placeholder="Sub Category Name">
                      <input type="hidden" class="form-control" id="main_cat_id" name="main_cat_id" value="<?php echo $this->uri->segment(3); ?>">
                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">Sub Category Name(Tamil)</label>
                      <input type="text" class="form-control" id="sub_cat_ta_name" name="sub_cat_ta_name" placeholder="Sub Category Tamil Name" >
                    </div>
                    <div class="form-group">
                      <label for="latitude">Sub Category Picture</label>
                      <input type="file" class="form-control" id="sub_cat_pic" name="sub_cat_pic" placeholder="">
                    </div>


                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Status</label>
                      <select class="form-control form-control-sm" id="status" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>

                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Create Sub category</button>

                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">

                <?php if($this->session->flashdata('msg')) {
                $message = $this->session->flashdata('msg');?>
                <div class="<?php echo $message['class'] ?>">
                  <?php echo $message['message']; ?>
                </div>
              <?php  }  ?>

                <div class="card-body">
                  <h4 class="card-title">List of Sub Category <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
              <table id="example" class="table table-striped table-bordered">
      <thead>
          <tr>
              <th>S.no</th>
              <th>Category name</th>
              <th>Category Picture</th>
              <th>Status</th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($res as $rows){ ?>


          <tr>
                <td><?php echo $i; ?></td>
              <td><?php echo $rows->sub_cat_name; ?> <br><br><?php echo $rows->sub_cat_ta_name; ?>
              </td>
              <td><img src="<?php echo base_url(); ?>assets/category/<?php echo $rows->sub_cat_pic; ?>" class="img-responsive" style="width:100px;    height: auto;"> </td>
                <td><?php if($rows->status=='Inactive'){ ?>
                <button type="button" class="badge badge-danger">Inactive</button>
            <?php   }else{ ?>
              <button type="button" class="badge badge-success">Active</button>
            <?php   }
               ?></td>
              <td>

                  <?php if($role=='6'){ ?>

                  <?php }else{ ?>
                    <a href="<?php echo base_url(); ?>masters/get_sub_category_edit/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                  <?php } ?>
                <a title="Add Service" href="<?php echo base_url(); ?>masters/create_service/<?php echo base64_encode($rows->id*98765); ?>/<?php echo $rows->sub_cat_name; ?>/<?php echo $rows->sub_cat_ta_name; ?>"><i class="fa fa-plus-square"></i></a>
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

      $('#create_sub_category').validate({
      rules: {

          sub_cat_name: { required: true
                    // remote: {
                    //        url: "<?php echo base_url(); ?>masters/checksubcategory",
                    //        type: "post"
                    //     }
              },
          sub_cat_ta_name: { required: true
                    // remote: {
                    //        url: "<?php echo base_url(); ?>masters/checksubcategorytamil",
                    //        type: "post"
                    //     }
           },
          sub_cat_pic: {required: true,extension: "jpg,jpeg,png" }
      },
      messages: {
          sub_cat_pic:{
              required :"Please Select Sub Category Picture",extension:"File must be JPG OR PNG"
          },
          sub_cat_name: {
      					 required: "Please Enter Sub Category Name.",
      					 remote: "Sub Category Name  already in Exist!"
      							 },
           sub_cat_ta_name: {
                 required: "Please Enter Sub Category Tamil Name.",
                 remote: "Sub Category Tamil Name  Already in Exist!"
                     },

      }
      });
    </script>
