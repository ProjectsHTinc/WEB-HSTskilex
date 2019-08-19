<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">

            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Update Profile info </h4>

                  <form class="forms-sample" id="profile_update" method="post">
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
                    </div>
                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Gender</label>
                      <select class="form-control form-control-sm" id="gender" name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>

                      </select>
                        <script>$('#gender').val('<?php echo $rows->gender; ?>');</script>
                    </div>

                    <!-- <div class="form-group">
                      <label for="exampleInputCity1">City</label>
                      <input type="text" class="form-control" id="exampleInputCity1" placeholder="Location" name="city" value="<?php echo $rows->city; ?>">
                    </div> -->

                    <div class="form-group">
                      <label for="exampleTextarea1">Address</label>
                      <textarea class="form-control" id="exampleTextarea1" rows="2" name="address"><?php echo $rows->address; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Update</button>

                  </form>
                </div>
              </div>
            </div>




          </div>
        </div>
      </div>
    </div>
