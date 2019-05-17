<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>masters/create_city">City</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Edit City</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Edit City </h4>
                  <?php  foreach($res as $row){} ?>
                  <form class="forms-sample" id="edit_city" method="post">
                    <input type="hidden" class="form-control" id="city_id" name="city_id" placeholder="city_id" value="<?php echo base64_encode($row->id*98765); ?>">

                    <div class="form-group">
                      <label for="username">City name (English)</label>
                      <input type="text" class="form-control" id="city_name" name="city_name" placeholder="Username" value="<?php echo $row->city_name; ?>">
                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">City Name(Tamil)</label>
                      <input type="text" class="form-control" id="city_ta_name" name="city_ta_name" placeholder="Name"  value="<?php echo $row->city_ta_name; ?>">
                    </div>
                    <?php
                    $myString = $row->city_latlon;
                    $myArray = explode(',', $myString);
                    ?>
                    <div class="form-group">
                      <label for="latitude">Latitude</label>
                      <input type="text" class="form-control" id="latitude" name="latitude" placeholder="latitude" value="<?php echo $myArray[0]; ?>">
                    </div>
                    <div class="form-group">
                      <label for="longitude">Longitude</label>
                      <input type="text" class="form-control" id="longitude" name="longitude" placeholder="longitude" value="<?php  echo $myArray[1]; ?>">
                    </div>

                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Status</label>
                      <select class="form-control form-control-sm" id="status" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>

                      </select>
                        <script>$('#status').val('<?php echo $row->status; ?>');</script>
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

$('#edit_city').validate({
rules: {

    city_name: { required: true,
              remote: {
                     url: "<?php echo base_url(); ?>masters/checkcityexist/<?php echo $row->id; ?>",
                     type: "post"
                  }
        },
    city_ta_name: { required: true,
              remote: {
                     url: "<?php echo base_url(); ?>masters/checkcitytamilexist/<?php echo $row->id; ?>",
                     type: "post"
                  }
     },
    latitude: {required: true },
    longitude: {required: true }
},
messages: {
    longitude:{
        required :"Enter the longitude"
    },
    latitude:{
        required :"Enter the latitude"
      },
    city_name: {
					 required: "Please Enter City Name.",
					 remote: "City Name  already in Exist!"
							 },
     city_ta_name: {
           required: "Please Enter City Tamil Name.",
           remote: "City Tamil Name  Already in Exist!"
               },

},
submitHandler: function(form) {
$.ajax({
           url: "<?php echo base_url(); ?>masters/update_locations",
           type: 'POST',
           data: $('#edit_city').serialize(),
           dataType: "json",
           success: function(response) {
              var stats=response.status;
               if (stats=="success") {
                 swal('City Updated successfully')
                 window.setTimeout(function () {
                  location.href = "<?php echo base_url();  ?>masters/create_city";
              }, 1000);

             }else{
                swal(stats);
                 }
           }
       });
     }

});
</script>
