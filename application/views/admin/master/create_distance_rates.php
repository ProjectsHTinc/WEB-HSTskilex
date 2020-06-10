<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Distance Rates</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Create Distance Rates </h4>
                  <form class="forms-sample" id="distance_rate" method="post" action="<?php echo base_url(); ?>masters/add_distance_rates" enctype="multipart/form-data">

                    <div class="form-group">
                      <label>Surge Distance</label>
                      <select class="form-control form-control-sm" id="frm_km" name="frm_km">
					  <option value="">Select</option>
						<?php for ($n = 1; $n <= 100; $n++) { ?>
							<option value="<?php echo $n; ?>"><?php echo $n; ?></option>
						<?php  } ?>
                      </select>
                    </div>
					<div class="form-group">
                      <label>Distance Rates</label>
                      <input type="text" class="form-control" id="rates" name="rates" placeholder="Rates" maxlength='5'>
                    </div>
					<div class="form-group">
                      <label>Status</label>
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
					<div class="<?php echo $message['class'] ?>"><?php echo $message['message']; ?></div>
              <?php  }  ?>

                <div class="card-body">
                  <h4 class="card-title">List Distance Rates </h4>
              <table id="example" class="table table-striped table-bordered">
      <thead>
          <tr>
              <th>S.no</th>
              <th>surge_distance</th>
              <th>surge_price</th>
              <th>Status</th>
             <th>Actions</th>
          </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($result as $rows){ ?>

          <tr>
                <td><?php echo $i; ?></td>
              <td><?php echo $rows->surge_distance; ?>  </td>
                <td><?php echo $rows->surge_price; ?></td>
                <td><?php if($rows->status=='Inactive'){ ?>
                <button type="button" class="btn btn-danger btn-fw">Inactive</button>
            <?php   }else{ ?>
              <button type="button" class="btn btn-success btn-fw">Active</button>
            <?php   }
               ?></td>

                 <td><a href="<?php echo base_url(); ?>masters/edit_distance_rates/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;

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
	$('#rates').keypress(function(event) {
	  if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
		event.preventDefault();
	  }
	});

	$('#distance_rate').validate({
      rules: {
			frm_km:{required:true,
			remote: {
               url: "<?php echo base_url(); ?>masters/check_km",
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
