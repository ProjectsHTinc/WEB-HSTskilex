<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <form class="forms-sample" id="create_staff" method="post" action="<?php echo base_url(); ?>home/get_register_staff" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <?php if($this->session->flashdata('msg')) {
                $message = $this->session->flashdata('msg');?>
                <div class="<?php echo $message['class'] ?>">
                  <?php echo $message['message']; ?>
                </div>
              <?php  }  ?>
                <div class="card-body">
                  <h4 class="card-title">Create staff </h4>
                  <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="username">Username</label>
                      <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Name" >
                    </div>
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                      <label for="phone">Phone</label>
                      <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone number">
                    </div>
                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Gender</label>
                      <select class="form-control form-control-sm" id="exampleFormControlSelect3" name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Role Permission</label>
                      <select class="form-control form-control-sm" id="role_id" name="role_id">
                        <?php foreach($res as $rows_role){ ?>
                          <option value="<?php echo $rows_role->id; ?>"><?php echo $rows_role->role_name; ?></option>
                      <?php  } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputCity1">City</label>
                      <input type="text" class="form-control" id="exampleInputCity1" placeholder="Location" name="city">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputCity1">Profile Picture</label>
                      <input type="file" class="form-control" id="exampleInputCity1" placeholder="Location" name="profile_pic">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputCity1">Qualification</label>
                      <input type="text" class="form-control" id="qualification" placeholder="Qualification" name="qualification">
                    </div>

                    <div class="form-group">
                      <label for="exampleTextarea1">Address</label>
                      <textarea class="form-control" id="exampleTextarea1" rows="2" name="address"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Status</label>
                      <select class="form-control form-control-sm" id="exampleFormControlSelect3" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>

                      </select>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary mr-2">Create</button>
                  </div>
                </div>

                </div>
              </div>
            </div>
          </div>
            </form>
        </div>
      </div>
    </div>
    <script>

     // Staff creation

    $('#create_staff').validate({
    rules: {
        name: {required: true },
        email: { email: true,required: true,
                  remote: {
                         url: "checkemail",
                         type: "post"
                      }
            },
        username: { required: true,
                  remote: {
                         url: "checkusername",
                         type: "post"
                      }
         },
        gender: {required: true },
        address: {required: true },
        city: {required: true },
        qualification: {required: true },
        phone: {required: true, digits:true,minlength:10,maxlength:10,  remote: {
                 url: "checkphone",
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
        qualification:{
          required :"Please enter qualification"
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
         username: {
               required: "Please enter Username.",
               remote: "Username  already in Exist!"
                   },
       phone: {
       					 required: "Please enter phone number.",
       					 remote: "Phone number  already in Exist!"
       							 },

    }
    // submitHandler: function(form) {
    // $.ajax({
    //            url: "get_register_staff",
    //            type: 'POST',
    //            data: $('#create_staff').serialize(),
    //            dataType: "json",
    //            success: function(response) {
    //               var stats=response.status;
    //                if (stats=="success") {
    //                  swal('User Created successfully')
    //                  window.setTimeout(function () {
    //                   location.href = "get_all_staff";
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
