<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Data table</h4>
              <div class="row">
                  <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>S.no</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
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
                <td><?php echo $rows->email; ?></td>
                <td><?php echo $rows->phone; ?></td>
                <td><?php echo $rows->gender; ?></td>
                <td><?php if($rows->status=='Inactive'){ ?>
                  <button type="button" class="btn btn-danger btn-fw">Inactive</button>
              <?php   }else{ ?>
                <button type="button" class="btn btn-success btn-fw">Active</button>
              <?php   }
                 ?></td>
                <td><a href="<?php echo base_url(); ?>home/get_staff_details/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                  <a href="<?php echo base_url(); ?>home/get_staff_details/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a>
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
    <script>
      $('#example').DataTable();
    </script>
