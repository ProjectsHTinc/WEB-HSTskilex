<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Edit Distance Rates</span></li>
            </ol>
          </nav>
          <div class="row">
		<?php foreach($res as $rows){ $id = $rows->id; $old_surge_distance = $rows->surge_distance; } ?>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit Distance Rates </h4>
                  <form class="forms-sample" id="distance_rate" method="post" action="<?php echo base_url(); ?>masters/update_distance_rates" enctype="multipart/form-data">

                    <div class="form-group">
                      <label>Surge Distance</label>
                      <select class="form-control form-control-sm" id="frm_km" name="frm_km">
					  <option value="">Select</option>
						<?php for ($n = 20; $n <= 100; $n++) { ?>
							<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
						<?php  } ?>                      
                      </select><script>$('#frm_km').val('<?php echo $rows->surge_distance; ?>')</script>
                    </div>
					<div class="form-group">
                      <label>Distance Rates</label>
                      <input type="text" class="form-control" id="rates" name="rates" placeholder="Rates" maxlength='5' value = "<?php echo $rows->surge_price; ?>">
                    </div>
					<div class="form-group">
                      <label>Status</label>
						<select class="form-control form-control-sm" id="status" name="status">
							<option value="Active">Active</option>
							<option value="Inactive">Inactive</option>
						</select><script>$('#status').val('<?php echo $rows->status; ?>')</script>
                    </div>
					 <input type="hidden" class="form-control" id="rate_id" name="rate_id"  value="<?php echo base64_encode($rows->id*98765); ?>">
                    <button type="submit" class="btn btn-primary mr-2">Update</button>
                  </form>
                </div>
              </div>
            </div>


          </div>
        </div>
      </div>
    </div>
<script>
	$('#rates').keypress(function(event) {
	  if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
		event.preventDefault();
	  }
	});

	$('#distance_rate').validate({
      rules: {
			frm_km:{required:true,
			remote: {
               url: "<?php echo base_url(); ?>masters/check_km_exist/<?php echo $id;?>",
               type: "post"
            }
           },
			rates: {required: true},
			status: { required: true}
      },
      messages: {
			frm_km:{ required :"Select Kms",remote: "Already Exist"},
			rates: {required: "Enter Rates" },
			status: {required: "select status." }
      }
      });
</script>
