<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>verifyprocess/get_vendor_verify_list">Commando list </a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>View Commando details</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">View Commando details <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
                  <?php foreach($res as $rows){} ?>
                  <div class="row">
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Full Name :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" value="<?php echo $rows->owner_full_name; ?>" readonly>
                              <!-- <input type="hidden" class="form-control" id="serv_prov_id" name="serv_prov_id"  value="<?php  echo base64_encode($rows->user_master_id*98765); ?>"> -->
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Phone No :</label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" readonly value="<?php echo $rows->phone_no; ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Email :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo $rows->email; ?>">
                            </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Gender :</label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" readonly value="<?php echo $rows->gender; ?>">
                              <input type="hidden" class="form-control" id="serv_prov_id" name="serv_prov_id"  value="<?php echo base64_encode($rows->user_master_id*98765); ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Address :</label>
                          <div class="col-sm-8">
                                <textarea type="text" class="form-control" readonly><?php echo $rows->address; ?></textarea>
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">City :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo $rows->city; ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Zip Code :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo $rows->zip; ?>">
                            </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Company :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo $rows->company_status; ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Service Expert Count :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo $rows->no_of_service_person; ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label"></label>
                          <div class="col-sm-8">
                                <!-- <input type="text" class="form-control" readonly value="<?php echo $rows->also_service_person; ?>"> -->
                                <div class="form-check form-check-flat">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" disabled=""<?php if($rows->also_service_person === 'Y') echo 'checked="checked"';?>>
                              Aslo Service Expert
                              <i class="input-helper"></i></label>
                            </div>
                            </div>
                        </div>
                      </div>
                    </div>

                      <h4 class="card-title">Bank Details</h4>
                    <div class="row">

                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Bank Name :</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control" readonly value="<?php echo $rows->bank_name; ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Branch Name :</label>
                          <div class="col-sm-8">
                                  <input type="text" class="form-control" readonly value="<?php echo $rows->bank_branch_name; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Account No :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo $rows->bank_acc_no; ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Bank IFSC code :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo $rows->bank_ifsc_code; ?>">
                            </div>
                        </div>
                      </div>

                    </div>
                      <h4 class="card-title">Deposit Details</h4>
                      <form class="forms-sample" id="deposit_status_form" method="post" action="" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Deposit Amt :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo $rows->refundable_deposit; ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Deposit Status :</label>
                          <div class="col-sm-8">
                                <!-- <input type="text" class="form-control" value="<?php echo $rows->deposit_status; ?>"> -->
                                <select class="form-control form-control-sm border-info" id="deposit_status" name="deposit_status">
                                  <option value="Paid">Paid</option>
                                  <option value="Unpaid">Unpaid</option>
                                </select>
                                  <script>$('#deposit_status').val('<?php echo $rows->deposit_status; ?>');</script>
                            </div>
                        </div>
                      </div>
                    </div>

                </form>
                    <h4 class="card-title">Verification Details</h4>
                      <form class="forms-sample" id="verify_status_form" method="post" action="" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Commando verify :</label>
                        <div class="col-sm-8">
                          <select class="form-control form-control-sm border-info" id="serv_prov_verify_status" name="serv_prov_verify_status">
                            <option value="Pending">Pending</option>
                            <option value="Rejected">Rejected</option>
                            <option value="Approved">Approved</option>
                          </select>
                            <script>$('#serv_prov_verify_status').val('<?php echo $rows->serv_prov_verify_status; ?>');</script>

                          </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Login  Status :</label>
                        <div class="col-sm-8">
                              <!-- <input type="text" class="form-control" value="<?php echo $rows->status; ?>"> -->
                              <select class="form-control form-control-sm border-info" id="serv_prov_display_status" name="serv_prov_display_status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                              </select>
                                <script>$('#serv_prov_display_status').val('<?php echo $rows->status; ?>');</script>
                          </div>
                      </div>
                    </div>
                  </div>

              </form>




                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <script>


    </script>
<style>
.form-check .form-check-label .input-helper:after{
  background-color: green;
}
.form-check .form-check-label .input-helper:before{
    background-color: red;
    border: 1px solid;
    color: #fff;
    content: "x";
    position: absolute;
    text-align: center;
    font-weight: 600;

}

</style>
<script>
$('#serv_prov_display_status').change(function(){
    var status=$(this).val();
    var id=$("#serv_prov_id").val();

    if (confirm('Are you sure you want to submit this Change?')) {
      $.ajax({
                 url: "<?php echo base_url(); ?>verifyprocess/update_serv_display_status",
                 type: 'POST',
                 data: {
                     'status': status,
                     'id': id
                   },
                 dataType: "json",
                 success: function(response) {
                    var stats=response.status;
                     if (stats=="success") {
                       swal('Status  Updated successfully')
                   }else{
                      swal(stats);
                       }
                 }
             });
      } else {
       swal('cancelled')
      }

})
$('#serv_prov_verify_status').change(function(){
    var status=$(this).val();
    var id=$("#serv_prov_id").val();
    if (confirm('Are you sure you want to submit this Change?')) {
      $.ajax({
                 url: "<?php echo base_url(); ?>verifyprocess/update_serv_verify_status",
                 type: 'POST',
                 data: {
                     'status': status,
                     'id': id
                   },
                 dataType: "json",
                 success: function(response) {
                    var stats=response.status;
                     if (stats=="success") {
                       swal('Status  Updated successfully')
                   }else{
                      swal(stats);
                       }
                 }
             });
      } else {
       swal('cancelled')
      }
})
$('#deposit_status').change(function(){
    var status=$(this).val();
    var id=$("#serv_prov_id").val();
    if (confirm('Are you sure you want to submit this Change?')) {
      $.ajax({
                 url: "<?php echo base_url(); ?>verifyprocess/update_deposit_status",
                 type: 'POST',
                 data: {
                     'status': status,
                     'id': id
                   },
                 dataType: "json",
                 success: function(response) {
                    var stats=response.status;
                     if (stats=="success") {
                       swal('Status  Updated successfully')
                   }else{
                      swal(stats);
                       }
                 }
             });
      } else {
       swal('cancelled')
      }

});



</script>
