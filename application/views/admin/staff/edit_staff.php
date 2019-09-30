<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Update Profile info </h4>
                  <form class="forms-sample" id="staff_profile_update" method="post" action="<?php echo base_url(); ?>home/update_staff_profile" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-md-6">
                      <?php foreach($res as $rows){} ?>
                      <div class="form-group">
                        <label for="exampleInputName1">Username</label>
                        <input type="text" class="form-control" id="exampleInputName1" name="Username" placeholder="Username" value="<?php echo $rows->username; ?>" readonly>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" id="exampleInputName1" name="name" placeholder="Name" value="<?php echo $rows->name; ?>">
                      </div>
                      <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="exampleInputEmail3" name="email" placeholder="Email" value="<?php echo $rows->email; ?>">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">Phone</label>
                        <input type="text" class="form-control" id="exampleInputEmail3" name="phone" placeholder="Phone number" value="<?php echo $rows->phone; ?>">
                        <input type="hidden" class="form-control" id="exampleInputEmail3" name="id" placeholder="Phone number" value="<?php echo base64_encode($rows->id*98765); ?>">
                      </div>
                      <div class="form-group">
                        <label for="exampleFormControlSelect3">Gender</label>
                        <select class="form-control form-control-sm" id="gender" name="gender">
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>

                        </select>
                          <script>$('#gender').val('<?php echo $rows->gender; ?>');</script>
                      </div>
                      <div class="form-group">
                        <label for="exampleFormControlSelect3">Role Permission</label>
                        <select class="form-control form-control-sm" id="role_id" name="role_id">
                          <?php foreach($res_role as $rows_role){ ?>
                            <option value="<?php echo $rows_role->id; ?>"><?php echo $rows_role->role_name; ?></option>
                        <?php  } ?>
                        </select>
                        <script>$('#role_id').val('<?php echo $rows->admin_type; ?>');</script>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="exampleInputCity1">City</label>
                        <input type="text" class="form-control" id="exampleInputCity1" placeholder="Location" name="city" value="<?php echo $rows->city; ?>">

                      </div>
                      <div class="form-group">
                        <label for="exampleInputCity1">Profile Picture</label>
                        <input type="file" class="form-control" id="exampleInputCity1" placeholder="Location" name="profile_pic">
                        <input type="hidden" name="old_profile_pic" value="<?php echo $rows->profile_pic; ?>">
                        <input type="hidden" name="id" value="<?php echo $rows->id; ?>">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputCity1">Qualification</label>
                        <input type="text" class="form-control" id="exampleInputCity1" placeholder="Qualification" name="qualification" value="<?php echo $rows->qualification; ?>">
                      </div>


                      <div class="form-group">
                        <label for="exampleTextarea1">Address</label>
                        <textarea class="form-control" id="exampleTextarea1" rows="2" name="address"><?php echo $rows->address; ?></textarea>
                      </div>
                      <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control form-control-sm" id="status" name="status">
                          <option value="Active">Active</option>
                          <option value="Inactive">Inactive</option>
                        </select>
                        <script>$('#status').val('<?php echo $rows->status; ?>');</script>
                      </div>
                      <button type="submit" class="btn btn-primary mr-2">Update</button></div>
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

    $('#staff_profile_update').validate({
    rules: {
        name: {required: true },
        email: { email: true,required: true,
                  remote: {
                         url: "<?php echo base_url(); ?>home/check_staff_email_exist/<?php echo $rows->id; ?>",
                         type: "post"
                      } },
        gender: {required: true },
        address: {required: true },
        city: {required: true },
        phone: {required: true,digits:true,minlength:10,maxlength:10,  remote: {
                 url: "<?php echo base_url(); ?>home/check_staff_phone_exist/<?php echo $rows->id; ?>",
                 type: "post"
              }
             }
    },
    messages: {
        name:{
          required :"Please enter name"
        },
        city:{
          required :"Please enter city"
        },
        address:{
          required :"Please enter address"
        },
        gender:{
            required :"Select Gender"
          },
        email: {
               required: "Please enter Email.",
               remote: "Email  already in Exist!"
                   },
       phone: {
                 required: "Please enter phone number.",
                 remote: "Phone number  already in Exist!"
                     },

    }
    // submitHandler: function(form) {
    // $.ajax({
    //            url: "<?php echo base_url(); ?>home/update_staff_profile",
    //            type: 'POST',
    //            data: $('#staff_profile_update').serialize(),
    //            dataType: "json",
    //            success: function(response) {
    //               var stats=response.status;
    //                if (stats=="success") {
    //                  swal('Profile Updated')
    //                  window.setTimeout(function () {
    //                   location.href = "<?php echo base_url(); ?>home/get_all_staff";
    //               }, 1000);
    //
    //              }else{
    //                  $('#res').html(response.msg)
    //                  }
    //            }
    //        });
    //      }

    });



    </script>
