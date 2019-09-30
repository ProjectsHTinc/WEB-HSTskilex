
<style>
th{
  padding: 0px 0px 0px 0px;
}
table.dataTable thead th, table.dataTable thead td{
  padding: 0px;
}

</style><div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Transactions</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Date between Transaction</span></li>
            </ol>
          </nav>
          <div class="row">
            <div class="container" id="list">

              <div class="card">

                 <?php if($this->session->flashdata('msg')){ ?>
                <div class="alert alert-success" role="alert">
                   <button type="button" class="close" data-dismiss="alert">x</button>
                        <?php  echo $this->session->flashdata('msg'); ?>
                </div>
              <?php } ?>

                <div class="card-body"  >
                  <h4 class="card-title">Date between transactions</h4>

              <table id="example" class="table table-striped table-bordered">
      <thead >
          <tr style="height:90px;">
              <th>S.no</th>
              <th>Associate Name</th>
              <th >Service date</th>
              <!-- <th>Total Service per day</th> -->
              <th>Total Amount</th>
              <th>Associate Comm Amt</th>
              <th>Skilex Com Amt</th>
              <!-- <th>Online Amt</th>
              <th>Offline Amt</th>
              <th>Online Skilex comm Amt</th>
              <th>Offline Skilex comm Amt</th>
              <th>Online Skilex Provider Amt</th>
              <th>Offline Skilex Provider Amt</th>
              <th>Taxable Amount</th> -->
              <th>Balance</th>

              <th>Skilex Closing </th>
              <th>Associate Closing </th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($res as $rows){ ?>


          <tr>




                <td><?php echo $i; ?></td>
                <td><?php echo $rows->owner_full_name; ?></td>
                <td style="width:80px;"><?php echo date('d-m-Y', strtotime($rows->service_date));  ?></td>
                <!-- <td><?php echo $rows->total_service_per_day; ?></td> -->
                <td><?php echo $rows->serv_total_amount; ?></td>
                <td><?php echo $rows->serv_prov_commission_amt; ?></td>
                <td><?php echo $rows->skilex_commission_amt; ?></td>
                <!-- <td><?php echo $rows->online_transaction_amt; ?></td>
                <td><?php echo $rows->offline_transaction_amt; ?></td>
                <td><?php echo $rows->online_skilex_commission; ?></td>
                <td><?php echo $rows->offline_skilex_commission; ?></td>
                <td><?php echo $rows->online_serv_prov_commission; ?></td>
                <td><?php echo $rows->offline_serv_prov_commission; ?></td>
                <td><?php echo $rows->taxable_amount; ?></td> -->
                <td><?php echo $rows->pay_to_serv_prov; ?></td>
                <td><?php if($rows->skilex_closing_status=='Unpaid'){ ?>
                  <input type="hidden" id="daily_id" value="<?php echo $rows->id; ?>">
                  <a class="unpaid_text" class="open-AddBookDialog_1 btn" onclick="update_status('skilex')" data-toggle="modal" data-target="#exampleModal-5"
                    >Unpaid</a>
              <?php  }else{ ?>
                <p class="paid_text"><?php echo $rows->skilex_closing_status; ?></p>

                <?php } ?></td>
                <td>
                  <?php if($rows->serv_prov_closing_status=='Unpaid'){
                   ?>
                    <!-- <a class="unpaid_text" class="open-AddBookDialog_1 btn" onclick="update_status('provider')" data-toggle="modal" data-target="#exampleModal-5"
                      >Unpaid</a> -->
                      <p class="paid_text">Unpaid</p>
                <?php  }else{ ?>
                  <p class="paid_text"><?php echo $rows->serv_prov_closing_status; ?></p>

                  <?php } ?></td>

                <td><a style="border:1px solid #777777;" class="open-AddBookDialog btn" data-toggle="modal" data-target="#exampleModal-4"
                  data-id="<?php echo $rows->id; ?>"
                  data-owner_full_name="<?php echo $rows->owner_full_name; ?>"
                  data-service_date="<?php echo date('d-m-Y', strtotime($rows->service_date)); ?>"
                  data-total_service_per_day="<?php echo $rows->total_service_per_day; ?>"
                  data-serv_total_amount="<?php echo $rows->serv_total_amount; ?>"
                  data-serv_prov_commission_amt="<?php echo $rows->serv_prov_commission_amt; ?>"
                  data-skilex_commission_amt="<?php echo $rows->skilex_commission_amt; ?>"
                  data-online_transaction_amt="<?php echo $rows->online_transaction_amt; ?>"
                  data-offline_transaction_amt="<?php echo $rows->offline_transaction_amt; ?>"
                  data-online_skilex_commission="<?php echo $rows->online_skilex_commission; ?>"
                  data-offline_skilex_commission="<?php echo $rows->offline_skilex_commission; ?>"
                  data-online_serv_prov_commission="<?php echo $rows->online_serv_prov_commission; ?>"
                  data-offline_serv_prov_commission="<?php echo $rows->offline_serv_prov_commission; ?>"
                  data-taxable_amount="<?php echo $rows->taxable_amount; ?>"
                  data-pay_to_serv_prov="<?php echo $rows->pay_to_serv_prov; ?>"
                  data-skilex_closing_status="<?php echo $rows->skilex_closing_status; ?>"
                  data-serv_prov_closing_status="<?php echo $rows->serv_prov_closing_status; ?>"
                  data-transaction_notes="<?php echo $rows->transaction_notes; ?>"
                  data-order_id="<?php echo $rows->order_id; ?>"
                  data-ccavenue_track_id="<?php echo $rows->ccavenue_track_id; ?>"


                  >View</a></td>



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



    <div class="modal  " id="exampleModal-5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel54" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel-4">Add Notes</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
            <div class="modal-body" style="padding:10px;">

                  <form action="" method="post" id="doc_status_form">
                    <input type="hidden" id="daily_id" name="daily_id" value="">
                    <input type="hidden" id="status" name="status" value="">



                    <div class="form-group">
                      <label for="message-text" class="col-form-label">Bank Refrence number Or Notes:</label>
                      <textarea class="form-control" name="transaction_notes" id="notes"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Update status to Paid</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

                  </form>



            </div>
            </div>
        </div>
    </div>


          <div class="modal  " id="exampleModal-4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-4" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel-4">Transaction Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-3 bor">Associate name</div><div class="col-md-3 bor"><p id="owner_full_name"></p></div>

                      <div class="col-md-3 bor">Service Date</div><div class="col-md-3 bor"><p id="service_date"></p></div>
                      <div class="col-md-3 bor">Service Per Day</div><div class="col-md-3 bor"><p id="total_service_per_day"></p></div>
                      <div class="col-md-3 bor">Service Total Amount</div><div class="col-md-3 bor"><p id="serv_total_amount"></p></div>
                      <div class="col-md-3 bor">Associate Commission Amount</div><div class="col-md-3 bor"><p id="serv_prov_commission_amt"></p></div>
                      <div class="col-md-3 bor">Skilex Commission amount</div><div class="col-md-3 bor"><p id="skilex_commission_amt"></p></div>

                      <table id="example" class="table table-striped table-bordered ">
                          <thead>
                            <tr>
                              <th>Online Amount</th>
                              <th>Offline amount</th>
                              <th>Online Skilex commission</th>
                              <th>Offline skilex commission</th>
                              <th>Online Associate commission</th>
                              <th>Offline Associate Commission</th>
                              <th>Taxable amount</th>
                            </tr>
                          </thead>
                        <tbody>

                            <tr>
                            <td><p id="online_transaction_amt"></p> </td>
                            <td><p id="offline_transaction_amt"></p></td>
                            <td><p id="online_skilex_commission">  </td>
                            <td><p id="offline_skilex_commission"></p></td>
                            <td><p id="online_serv_prov_commission"></p></td>
                            <td><p id="offline_serv_prov_commission"></p></td>
                            <td><p id="taxable_amount"></p></td>

                            </tr>

                        </tbody>
                      </table>
                      <!-- <div class="col-md-3 bor">Online Amount</div><div class="col-md-3 bor"><p id="online_transaction_amt"></p></div>
                      <div class="col-md-3 bor">Offline amount</div><div class="col-md-3 bor"><p id="offline_transaction_amt"></p></div>
                      <div class="col-md-3 bor">Online Skilex commission</div><div class="col-md-3 bor"><p id="online_skilex_commission"></p></div>
                      <div class="col-md-3 bor">Offline skilex commission</div><div class="col-md-3 bor"><p id="offline_skilex_commission"></p></div>
                      <div class="col-md-3 bor">Online Provider commission</div><div class="col-md-3 bor"><p id="online_serv_prov_commission"></p></div>
                      <div class="col-md-3 bor">Offline Provider Commission</div><div class="col-md-3 bor"><p id="offline_serv_prov_commission"></p></div> -->
                      <!-- <div class="col-md-3 bor">Taxable amount</div><div class="col-md-3 bor"><p id="taxable_amount"></p></div> -->
                        </div>
                    <div class="row" style="margin-top:0px;">
                      <div class="col-md-2 bor">Amount</div><div class="col-md-2 bor"><p id="amt_from_provider"></p></div>
                      <div class="col-md-2 bor">Skilex closing status</div><div class="col-md-2 bor"><p id="skilex_closing_status"></p></div>
                      <div class="col-md-2 bor">Associate Closing status</div><div class="col-md-2 bor"><p id="serv_prov_closing_status"></p></div>
                      <div class="col-md-2 bor">Transaction notes</div><div class="col-md-2 bor"><p id="transaction_notes"></p></div>
                      <div class="col-md-2 bor">Order id</div><div class="col-md-2 bor"><p id="order_id"></p></div>
                      <div class="col-md-2 bor">Payment Track</div><div class="col-md-2 bor"><p id="ccavenue_track_id"></p></div>

                    </div>


                  </div>
                  </div>
              </div>
          </div>


    <style>
    .modal-header{
      background-color: #fff;
    }
    .modal-content{
      background-color: #fff;
    }
.unpaid_text{
  color: #fb0000 !important;
  font-weight: 600;
  padding-top: 10px;
}
.paid_text {
    color: #1b0bf7 !important;
    font-weight: 600;
    padding-top: 10px;
}
.bor{
  border: 1px solid #ececec;
  padding-top: 5px;
}
table.dataTable thead th, table.dataTable thead td {
    padding: 0px;
    text-align: center;
}
    </style>
    <script>

function update_status(msg){
  var status=msg;
  $(".modal-body #status").val( status );
  var daily_id=$('#daily_id').val();
  $(".modal-body #daily_id").val( daily_id );
}





  $(document).on("click", ".open-AddBookDialog", function () {


   var owner_full_name = $(this).data('owner_full_name');
   $(".modal-body #owner_full_name").html( owner_full_name );
   var service_date = $(this).data('service_date');
   $(".modal-body #service_date").html( service_date );
   var total_service_per_day = $(this).data('total_service_per_day');
   $(".modal-body #total_service_per_day").html( total_service_per_day );
   var serv_total_amount = $(this).data('serv_total_amount');
   $(".modal-body #serv_total_amount").html( serv_total_amount );
   var serv_prov_commission_amt = $(this).data('serv_prov_commission_amt');
   $(".modal-body #serv_prov_commission_amt").html( serv_prov_commission_amt );
   var skilex_commission_amt = $(this).data('skilex_commission_amt');
   $(".modal-body #skilex_commission_amt").html( skilex_commission_amt );
   var online_transaction_amt = $(this).data('online_transaction_amt');
   $(".modal-body #online_transaction_amt").html( online_transaction_amt );
   var offline_transaction_amt = $(this).data('offline_transaction_amt');
   $(".modal-body #offline_transaction_amt").html( offline_transaction_amt );
   var online_skilex_commission = $(this).data('online_skilex_commission');
   $(".modal-body #online_skilex_commission").html( online_skilex_commission );
   var offline_skilex_commission = $(this).data('offline_skilex_commission');
   $(".modal-body #offline_skilex_commission").html( offline_skilex_commission );
   var online_serv_prov_commission = $(this).data('online_serv_prov_commission');
   $(".modal-body #online_serv_prov_commission").html( online_serv_prov_commission );
   var offline_serv_prov_commission = $(this).data('offline_serv_prov_commission');
   $(".modal-body #offline_serv_prov_commission").html( offline_serv_prov_commission );
   var taxable_amount = $(this).data('taxable_amount');
   $(".modal-body #taxable_amount").html( taxable_amount );
   var pay_to_serv_prov = $(this).data('pay_to_serv_prov');
    $(".modal-body #amt_from_provider").html( pay_to_serv_prov );
    var transaction_notes = $(this).data('transaction_notes');
     $(".modal-body #transaction_notes").html( transaction_notes );
    var skilex_closing_status = $(this).data('skilex_closing_status');
    $(".modal-body #skilex_closing_status").html( skilex_closing_status );
    var serv_prov_closing_status = $(this).data('serv_prov_closing_status');
    $(".modal-body #serv_prov_closing_status").html( serv_prov_closing_status );
    var order_id = $(this).data('order_id');
    $(".modal-body #order_id").html( order_id );
    var ccavenue_track_id = $(this).data('ccavenue_track_id');
    $(".modal-body #ccavenue_track_id").html( ccavenue_track_id );



});



$('#doc_status_form').validate({
rules: {

      transaction_notes :{
        required: true
      }
},
messages: {

    transaction_notes: {
      required:"Please Enter Some notes"
    }

},
submitHandler: function(form) {
$.ajax({
           url: "<?php echo base_url(); ?>transaction/update_trans_status",
           type: 'POST',
           data: $('#doc_status_form').serialize(),
           dataType: "json",
           success: function(response) {
              var stats=response.status;
               if (stats=="success") {
              swal('Status Updated')
               window.setTimeout(function(){location.reload()},1000)


             }else{

                   swal(stats)
                 }
           }
       });
     }
});


    </script>
