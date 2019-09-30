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

            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Create Banner </h4>

                  <form class="forms-sample" id="create_banner" method="post" action="<?php echo base_url(); ?>masters/create_banner" enctype="multipart/form-data">

                    <div class="form-group">
                      <label for="username">Title</label>
                      <input type="text" class="form-control" id="banner_title" name="banner_title" placeholder="Banner title">
                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">Banner image</label>
                      <input type="file" class="form-control" id="banner_img" name="banner_img" placeholder="" >
                    </div>

                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Status</label>
                      <select class="form-control form-control-sm" id="status" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>

                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Create</button>

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
                  <h4 class="card-title">List of Banners </h4>
              <table id="example" class="table table-striped table-bordered">
      <thead>
          <tr>
              <th>S.no</th>
              <th>Title</th>
              <th>Image</th>
              <th>Status</th>
              <?php if($role=='6'){}else{ ?><th>Actions</th><?php } ?>
          </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($res as $rows){ ?>


          <tr>
                <td><?php echo $i; ?></td>
              <td><?php echo $rows->banner_title; ?>  </td>
                <td><img src="<?php echo base_url(); ?>assets/banners/<?php echo $rows->banner_img; ?>" style="width:150px;height:100px;">  </td>
                <td><?php if($rows->status=='Inactive'){ ?>
                <button type="button" class="btn btn-danger btn-fw">Inactive</button>
            <?php   }else{ ?>
              <button type="button" class="btn btn-success btn-fw">Active</button>
            <?php   }
               ?></td>
               <?php if($role=='6'){

               }else{ ?>
                 <td><a href="<?php echo base_url(); ?>masters/get_banner_edit/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;

                 </td>
              <?php } ?>

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

      $('#create_banner').validate({
      rules: {

          banner_title: { required: true},
          status: { required: true},
          banner_img: {required: true,extension: "jpg,jpeg,png" }
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
