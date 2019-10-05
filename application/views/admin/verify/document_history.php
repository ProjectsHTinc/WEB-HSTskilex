<!-- <script src="<?php  echo base_url(); ?>assets/admin/js/modal-demo.js"></script> -->
<style>
th{
  width:100px;
}
</style>
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>verifyprocess/get_vendor_verify_list">Service Associate list </a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Service Associate Document list</span></li>
            </ol>
          </nav>
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Document  uploaded history <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>

              <div class="container">
                  <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered  "  >

        <thead>
            <tr>
                <th >S.no</th>
                <th>Notes</th>
                <th>Status</th>
                <th>Updated by</th>
                <th>Updated on</th>

            </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($res as $rows) { ?>


            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $rows->notes; ?></td>
                <td><?php echo $rows->status; ?></td>
                <td><?php echo $rows->name; ?></td>
                <td><?php echo  date('d-m-Y H:i:s',strtotime($rows->created_at)) ?></td>

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
