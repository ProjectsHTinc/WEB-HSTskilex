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

            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <?php foreach($res as $rows){} ?>
                  <h4 class="card-title">Update  Commission </h4>

                  <form class="forms-sample" id="update_commission" method="post">
                    <div class="form-group">
                      <label for="username">Skilex Commission Percentage</label>
                      <input type="text" class="form-control" id="internal_commission" name="internal_commission" value="<?php echo $rows->internal_commission; ?>" placeholder="Skilex Commission Percentage">
                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">Associate Commission Percentage</label>
                      <input type="text" class="form-control" id="external_commission" name="external_commission" value="<?php echo $rows->external_commission; ?>" placeholder="Associate Commission Percentage" >
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Upate Commission</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Update Tax</h4>

                  <form class="forms-sample" id="update_tax" method="post">
                    <div class="form-group">
                      <label for="username">SGST Percentage</label>
                      <input type="text" class="form-control" id="cgst" name="cgst" placeholder="CGST" value="<?php echo $rows->cgst; ?>">
                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">CGST Percentage</label>
                      <input type="text" class="form-control" id="sgst" name="sgst" placeholder="SGST" value="<?php echo $rows->sgst; ?>">
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Upate Tax</button>
                  </form>
                </div>
              </div>
            </div>






          </div>
        </div>
      </div>
    </div>
    <script>

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
    </script>
