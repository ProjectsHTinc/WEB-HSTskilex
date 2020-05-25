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
                <th width="10%">S.no</th>
                <th width="50%">Main Category</th>
                <th width="20%">Status</th>
                <th width="20%">Action</th>
            </tr>
        </thead>
        <tbody>
          <?php
			$i=1;
				foreach($res as $rows) {
				$user_master_id = $rows->user_master_id;
		  ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $rows->main_cat_name; ?></td>
                <!-- <td><?php echo $rows->sub_cat_name; ?></td>
                <td><?php echo $rows->service_name; ?></td> -->
				<td><?php echo $rows->status; ?></td><td><?php if (count($res) >2) { ?>&nbsp <a href="<?php echo base_url(); ?>verifyprocess/delete_skills/<?php echo $rows->id; ?>/<?php echo base64_encode($user_master_id*98765);?>" onclick="return confirm('Are you sure want to delete?');" style="cursor:pointer" class="btn go_back_btn pull-right">Delete</a><?php } ?>
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
        <!-- content-wrapper ends -->

      </div>

    </div>
<script>


function confirm_remove(id){

  swal({
      title: '',
      text: "Are you sure want to remove?",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
  }).then(function(){
		window.location.href='<?php echo base_url(); ?>reviews/remove_review/'+id;
  }).catch(function(reason){

  });
}
</script>
