<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <?php if($this->session->flashdata('msg')) {
            $message = $this->session->flashdata('msg');?>
            <div class="<?php echo $message['class'] ?>">
              <?php echo $message['message']; ?>
            </div>
          <?php  }  ?>
            <div class="card-body">
              <h4 class="card-title">Staff list</h4>
              <div class="row">
                  <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>S.no</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email <br>Phone </th>
                <th>Permission</th>

                <th>Profile Picture</th>

                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($res as $rows){ ?>


            <tr>
                  <td><?php echo $i; ?></td>
                <td><?php echo $rows->name; ?></td>
                <td><?php echo $rows->username; ?></td>
                <td><?php echo $rows->email; ?><br><br>
                <?php echo $rows->phone; ?></td>
                <td><?php echo $rows->role_name; ?></td>

                  <td><img src="<?php echo base_url(); ?>assets/profile/<?php echo $rows->profile_pic; ?>" style="width:100px;"></td>
                
                <td><?php if($rows->status=='Inactive'){ ?>
                  <button type="button" class="badge badge-danger">Inactive</button>
              <?php   }else{ ?>
                <button type="button" class="badge badge-success">Active</button>
              <?php   }
                 ?></td>
                <td><a title="Edit " href="<?php echo base_url(); ?>home/get_staff_details/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                  <!-- <a href="<?php echo base_url(); ?>home/get_staff_details/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> -->
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
        <!-- content-wrapper ends -->

      </div>

    </div>
