<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>verifyprocess/get_vendor_verify_list">Pending orders </a></li>
              <li class="breadcrumb-item active" aria-current="page"><span> Service orders details</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">View Service Order details details <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
                  <?php foreach($res as $rows){} ?>
                  <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Phone number :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" value="<?php echo $rows->phone_no; ?>" readonly>
                              <!-- <input type="hidden" class="form-control" id="serv_prov_id" name="serv_prov_id"  value="<?php  echo base64_encode($rows->user_master_id*98765); ?>"> -->
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Service :</label>
                          <div class="col-sm-8">
                              <input type="text" class="form-control" readonly value="<?php echo $rows->service_name; ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Contact Person Name :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo $rows->contact_person_name; ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Contact Person Number :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo $rows->contact_person_number; ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Service Date :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo  date('d-m-Y',strtotime($rows->order_date)) ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Service Timeslot :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo date("g:i ", strtotime($rows->from_time)); ?> - <?php    echo date("g:i", strtotime($rows->to_time)); ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Advance Amount :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo $rows->advance_amount_paid; ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Advance status :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php  if($rows->advance_payment_status=='NA'){ echo "Not Available";}else if($rows->advance_payment_status=='N'){ echo "Advance Unpaid"; }else{ echo "Advance Paid";} ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Service Rate :</label>
                          <div class="col-sm-8">
                                <input type="text" class="form-control" readonly value="<?php echo $rows->service_rate_card; ?>">
                            </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Service Location :</label>
                          <div class="col-sm-8">
                                <textarea type="text" class="form-control" readonly><?php echo $rows->service_location; ?></textarea>
                                </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-4 col-form-label">Service Location :</label>
                          <div class="col-sm-8">
                                <textarea type="text" rows="5" class="form-control" readonly><?php echo $rows->service_address; ?></textarea>
                                </div>
                        </div>
                      </div>
                    </div>

                  <h4 class="card-title">List of additional Services</h4>
                  <table id="example1" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>S.no</th>
                          <th>Service name</th>
                          <th>service Rate</th>

                        </tr>
                      </thead>
                    <tbody>
                    <?php $i=1;
                        if(empty($res_additional)){ ?>
                        <td colspan="4" style="width:100%">No Record Found</td>
                        <?php  }else{
                        foreach($res_additional as $rows_ad){ ?>
                        <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rows_ad->service_name; ?>  </td>
                        <td><?php echo $rows_ad->ad_service_rate_card; ?>  </td>

                        </tr>
                        <?php  $i++;  }  ?>
                        <?php   } ?>
                    </tbody>
                  </table>
                  <br>
                  <h4 class="card-title">List of Service Order History <span class="pull-right"><a style="border:1px solid #777777;" class="open-AddBookDialog btn assign_btn" data-toggle="modal" data-target="#exampleModal-4">Assign Associate</a></span></h4>
                  <table id="example" class="table table-striped table-bordered ">
                      <thead>
                        <tr>
                          <th>S.no</th>
                          <th>Name</th>
                          <th>Role</th>
                          <th>Requested on</th>
                          <th>status</th>
                        </tr>
                      </thead>
                    <tbody>
                    <?php $i=1;
                        if(empty($res_prov)){ ?>
                        <td colspan="5" style="width:100%">No Record Found</td>
                        <?php  }else{
                        foreach($res_prov as $rows_prov){ ?>
                        <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rows_prov->name; ?>  </td>
                        <td><?php echo $rows_prov->role_name; ?>  </td>
                        <td><?php echo  date('d-m-Y H:i:s',strtotime($rows_prov->created_at)) ?></td>
                        <td>
                          <?php $stats=$rows_prov->status;
                           if($stats=="Requested"){
                             $color="btn-primary";
                          }else if($stats=="Expired"){
                            $color="btn-danger";
                          }else{
                            $color="btn-success";
                          }?>
                          <button type="button" class="btn  <?php echo $color; ?>"><?php echo $stats; ?></button>

                        </td>
                        </tr>
                        <?php  $i++;  }  ?>
                        <?php   } ?>
                    </tbody>
                  </table>
                  <br>
                  <h4 class="card-title">Service Payment</h4>
                  <table id="example" class="table table-striped table-bordered ">
                      <thead>
                        <tr>
                          <th>S.no</th>
                          <th>Advance amt</th>
                          <th>Service amt</th>
                          <th>Additional Service amt</th>
                          <th>Discount amt</th>
                          <th>Tax amt (CGST + SGST)</th>
                          <th>Skilex commission amt</th>
                          <th>Associate commission amt</th>
                          <th>Payable amount</th>
                        </tr>
                      </thead>
                    <tbody>
                    <?php $i=1;
                        if(empty($res_payments)){ ?>
                        <td colspan="9" style="width:100%">No Record Found</td>
                        <?php  }else{
                        foreach($res_payments as $rows_pay){ ?>
                        <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rows_pay->paid_advance_amount; ?>  </td>
                        <td><?php echo $rows_pay->service_amount; ?>  </td>
                        <td><?php echo $rows_pay->ad_service_amount; ?>  </td>
                        <td><?php echo $rows_pay->discount_amt; ?></td>
                        <td><?php echo $rows_pay->skilex_tax_amount; ?></td>
                        <td><?php echo $rows_pay->skilex_net_amount; ?></td>
                        <td><?php echo $rows_pay->serv_pro_net_amount; ?></td>
                        <td><?php echo $rows_pay->payable_amount; ?></td>
                        </tr>
                        <?php  $i++;  }  ?>
                        <?php   } ?>
                    </tbody>
                  </table>
                  <br>
                  <h4 class="card-title">Payment history</h4>
                  <table id="example" class="table table-striped table-bordered ">
                      <thead>
                        <tr>
                          <th>S.no</th>
                          <th>Payment Type</th>
                          <th>Payment Tract id</th>
                          <th>CC Avenue</th>
                          <th>Notes</th>
                          <th>status</th>
                          <th>Updated on</th>
                        </tr>
                      </thead>
                    <tbody>
                    <?php $i=1;
                        if(empty($res_pay_history)){ ?>
                        <td colspan="7" style="width:100%">No Record Found</td>
                        <?php  }else{
                        foreach($res_pay_history as $rows_history){ ?>
                        <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rows_history->payment_type; ?>  </td>
                        <td><?php echo $rows_history->payment_order_id; ?>  </td>
                        <td><?php echo $rows_history->ccavenue_track_id; ?>  </td>
                        <td><?php echo $rows_history->notes; ?></td>
                        <td><?php echo $rows_history->status; ?></td>
                        <td><?php echo  date('d-m-Y H:i:s',strtotime($rows_history->created_at)) ?></td>
                        </tr>
                        <?php  $i++;  }  ?>
                        <?php   } ?>
                    </tbody>
                  </table>
                  <br>

                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

      <div class="modal fade" id="exampleModal-4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-4" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel-4">Assign Associate</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
              <div class="modal-body">
                    <form action="" method="post" id="doc_status_form">
                        <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Select Associate</label>
                            <select class="form-control form-control-sm" id="provider_list" name="provider_id">
                              <option value="">--Select--</option>
                          <?php foreach($res_provider_list as $rows_p_list){ ?>
                              <option value="<?php echo $rows_p_list->user_id; ?>"><?php   echo $rows_p_list->owner_full_name; ?></option>
                        <?php  } ?>
                            </select>
                        </div>
                        <div class="text-center">
                          <input type="hidden" name="service_order_id" id="service_order_id" value="<?php echo base64_encode($rows->id*98765);  ?>"/>

                        </div>



                    </form>
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
.assign_btn{
  background-color: #105ea4;
  color: #fff !important;
}
</style>
<script>

$('#exampleModal-4').on('shown.bs.modal', function() {

})
$(document).on("click", ".open-AddBookDialog", function () {
 var myBookId = $(this).data('id');
 $(".modal-body #doc_dd_id").val( myBookId );
 var doc_status = $(this).data('doc-status');
 $("doc_status").val(doc_status);
 $(".modal-body #doc_status").val( doc_status );

});

$('#provider_list').change(function(){
    var prov_id=$(this).val();
    var id=$("#service_order_id").val();
    if (confirm('Are you sure you want to submit this Change?')) {
      $.ajax({
                 url: "<?php echo base_url(); ?>service_orders/assign_orders",
                 type: 'POST',
                 data: {
                     'prov_id': prov_id,
                     'id': id
                   },
                 dataType: "json",
                 success: function(response) {
                    var stats=response.status;
                     if (stats=="success") {
                       swal('Provider assigned successfully')
                       setTimeout(function() {
                           location.reload();
                       }, 1000);

                   }else{
                      swal(stats);
                       }
                 }
             });
      } else {
       swal('cancelled')
      }

})





</script>
