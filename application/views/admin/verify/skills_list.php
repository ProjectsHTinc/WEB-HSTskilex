<!-- <script src="<?php  echo base_url(); ?>assets/admin/js/modal-demo.js"></script> -->
<style>

</style>
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>verifyprocess/get_vendor_verify_list">Service Provider list </a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Service Provider Document list</span></li>
            </ol>
          </nav> -->
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Skills  list  <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>

              <div class="container">
                  <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered  "  >

        <thead>
            <tr>
                <th >S.no</th>
                <th>Main Category</th>
                <th>Sub Category</th>
                <th>Service</th>
                <th>Status</th>

            </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($res as $rows) { ?>


            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $rows->main_cat_name; ?></td>
                <td><?php echo $rows->sub_cat_name; ?></td>
                <td><?php echo $rows->service_name; ?></td>
                <td><?php echo $rows->status; ?></td>

            </tr>
          <?php  $i++;  }  ?>


        </tbody>

    </table>
              </div>
            </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->

      </div>

    </div>
