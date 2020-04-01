<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Tax & Commission</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <?php foreach($res as $rows){} ?>
                  <h4 class="card-title">Update  Commission </h4>

                  <form class="forms-sample" id="update_commission" method="post">
                    <div class="form-group">
                      <label for="username">Skilex Commission Percentage</label>
                      <input type="text" class="form-control" id="internal_commission" maxlength="2" name="internal_commission" value="<?php echo $rows->internal_commission; ?>" placeholder="Skilex Commission Percentage">
                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">Commando Commission Percentage</label>
                      <input type="text" class="form-control" id="external_commission" readonly name="external_commission" value="<?php echo $rows->external_commission; ?>" placeholder="Associate Commission Percentage" >
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Update Commission</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Update Tax</h4>

                  <form class="forms-sample" id="update_tax" method="post">
                    <div class="form-group">
                      <label for="username">SGST Percentage</label>
                      <input type="text" class="form-control" id="cgst" name="cgst" maxlength="2" placeholder="CGST" value="<?php echo $rows->cgst; ?>">
                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">CGST Percentage</label>
                      <input type="text" class="form-control" id="sgst" name="sgst"  readonly placeholder="SGST" value="<?php echo $rows->sgst; ?>">
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Update Tax</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Deposit Amount</h4>

                  <form class="forms-sample" id="deposit_amt_form" method="post">
                    <div class="form-group">
                      <label for="username">Amount</label>
                      <input type="text" class="form-control" id="deposit_amt" name="deposit_amt" placeholder="Deposit Amount" value="<?php echo $rows->deposit_amt; ?>">
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Update</button>
                  </form>
                </div>
              </div>
            </div>

          </div>

<div class="row">

            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <?php foreach($referal as $rows){} ?>
                  <h4 class="card-title">Referal Points </h4>

                  <form class="forms-sample" id="update_referal" method="post">
                    <div class="form-group">
                      <label for="username">Referal Points</label>
                      <input type="text" class="form-control" maxlength="3" name="referal_points" id="referal_points"  value="<?php echo $rows->referral_points; ?>" placeholder="Referal Points">
                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">Minimum Points Claim</label>
                      <input type="text" class="form-control"  maxlength="3" name="minimum_points_claim" id="minimum_points_claim" value="<?php echo $rows->minimum_points_to_claim; ?>" placeholder="Minimum Points Claim" >
                    </div>
					<div class="form-group">
                      <label for="city_ta_name">Division by</label>
                      <input type="text" class="form-control" maxlength="2" name="division_points" id="division_points" value="<?php echo $rows->division_points; ?>" placeholder="Division by" >
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Update</button>
                  </form>
                </div>
              </div>
            </div>
</div>

        </div>
      </div>
    </div>
    <script>
    $("#internal_commission").change(function(){
        var inter=$(this).val();
        var external=inter-100;
        $('#external_commission').val(Math.abs(external));
      });

      $("#cgst").change(function(){
          var cgst=$(this).val();
          var sgst=cgst;
          $('#sgst').val(sgst);
        });

    $('#update_tax').validate({
    rules: {
        sgst: {required: true,number:true,maxlength:2 },
        cgst: {required: true,number:true,maxlength:2 }
    },
    messages: {
        sgst:{
            required :"Enter the SGST"
        },
        cgst:{
            required :"Enter the CGST"
          },
    },
    submitHandler: function(form) {
    $.ajax({
               url: "<?php echo base_url(); ?>masters/update_tax_percentage",
               type: 'POST',
               data: $('#update_tax').serialize(),
               dataType: "json",
               success: function(response) {
                  var stats=response.status;
                   if (stats=="success") {
                     swal('Tax Updated successfully')
                     window.setTimeout(function () {
                      location.href = "<?php echo base_url();  ?>masters/tax_commission";
                  }, 1000);

                 }else{
                    swal(stats);
                     }
               }
           });
         }

    });
    $('#update_commission').validate({
    rules: {
        internal_commission: {required: true,number:true,maxlength:2 },
        external_commission: {required: true,number:true,maxlength:2 }
    },
    messages: {
        internal_commission:{
            required :"Enter the skilex Commission"
        },
        internal_commission:{
            required :"Enter the Associate Commission"
          },
    },
    submitHandler: function(form) {
    $.ajax({
               url: "<?php echo base_url(); ?>masters/update_commission_percentage",
               type: 'POST',
               data: $('#update_commission').serialize(),
               dataType: "json",
               success: function(response) {
                  var stats=response.status;
                   if (stats=="success") {
                     swal('Commission Updated successfully')
                     window.setTimeout(function () {
                      location.href = "<?php echo base_url();  ?>masters/tax_commission";
                  }, 1000);

                 }else{
                    swal(stats);
                     }
               }
           });
         }

    });

    $('#deposit_amt_form').validate({
    rules: {
        deposit_amt: {required: true,number:true }
    },
    messages: {
        deposit_amt:{
            required :"Enter the deposit amount"
        }
    },
    submitHandler: function(form) {
    $.ajax({
               url: "<?php echo base_url(); ?>masters/update_deposit_amt",
               type: 'POST',
               data: $('#deposit_amt_form').serialize(),
               dataType: "json",
               success: function(response) {
                  var stats=response.status;
                   if (stats=="success") {
                     swal('Deposit amount Updated successfully')
                     window.setTimeout(function () {
                      location.href = "<?php echo base_url();  ?>masters/tax_commission";
                  }, 1000);

                 }else{
                    swal(stats);
                     }
               }
           });
         }

    });
	
	
	
	$('#update_referal').validate({
    rules: {
        referal_points: {required: true,number:true,maxlength:3 },
        minimum_points_claim: {required: true,number:true,maxlength:3 },
		division_points: {required: true,number:true,maxlength:2 }
    },
    messages: {
        referal_points:{
            required :"Enter Referal Points"
        },
        minimum_points_claim:{
            required :"Enter Minimum Points Claim"
          },
		division_points:{
            required :"Division Value"
          },
    },
    submitHandler: function(form) {
    $.ajax({
               url: "<?php echo base_url(); ?>masters/update_referal_details",
               type: 'POST',
               data: $('#update_referal').serialize(),
               dataType: "json",
               success: function(response) {
                  var stats=response.status;
                   if (stats=="success") {
                     swal('Refer Details Updated successfully')
                     window.setTimeout(function () {
                      location.href = "<?php echo base_url();  ?>masters/tax_commission";
                  }, 1000);

                 }else{
                    swal(stats);
                     }
               }
           });
         }

    });
    </script>
