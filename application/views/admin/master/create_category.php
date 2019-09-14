<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Category</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Create Category </h4>

                  <form class="forms-sample" id="create_category" method="post" action="<?php echo base_url(); ?>masters/category_creation" enctype="multipart/form-data">

                    <div class="form-group">
                      <label for="username">Category name (English)</label>
                      <input type="text" class="form-control" id="main_cat_name" name="main_cat_name" placeholder="Category Name">
                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">Category Name(Tamil)</label>
                      <input type="text" class="form-control" id="main_cat_ta_name" name="main_cat_ta_name" placeholder="Category Tamil Name" >
                    </div>
                    <div class="form-group">
                      <label for="latitude">Category Picture</label>
                      <input type="file" class="form-control" id="cat_pic" name="cat_pic" placeholder="">
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
                  <h4 class="card-title">List of Category </h4>
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
              <td><?php echo $rows->main_cat_name; ?> <br><br><?php echo $rows->main_cat_ta_name; ?>
              </td>
              <td><img src="<?php echo base_url(); ?>assets/category/<?php echo $rows->cat_pic; ?>" class="img-responsive" style="width:100px;    height: auto;"> </td>
                <td><?php if($rows->status=='Inactive'){ ?>
                <button type="button" class="btn btn-danger btn-fw">Inactive</button>
            <?php   }else{ ?>
              <button type="button" class="btn btn-success btn-fw">Active</button>
            <?php   }
               ?></td>
              <td><a href="<?php echo base_url(); ?>masters/get_category_edit/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                <a title="Add Sub-Category" href="<?php echo base_url(); ?>masters/create_sub_category/<?php echo base64_encode($rows->id*98765); ?>/<?php echo $rows->main_cat_name; ?>/<?php echo $rows->main_cat_ta_name; ?>"><i class="fa fa-plus-square"></i></a>
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
      // $('#example').DataTable();
var table = $('#example').DataTable();
new $.fn.dataTable.Responsive( table, {
    details: false
} );
    </script>
