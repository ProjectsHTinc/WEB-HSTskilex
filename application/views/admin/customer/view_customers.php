<div class="container-fluid page-body-wrapper">
      <div class="main-panel">

        <div class="content-wrapper">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Customers List</span></li>
            </ol>
          </nav>
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">View Customer Details</h4>
              <div class="row">
                  <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered" width="100%">
        <thead>
            <tr>
                <th>S.no</th>
                <th>Name/Username</th>
                
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Status</th>
                <th>Order details</th>
            </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($res as $rows){ ?>


            <tr>
                  <td><?php echo $i; ?></td>
                <td><?php echo $rows->full_name; ?><br> <?php echo $rows->username; ?></td>

                <td><?php echo $rows->email; ?></td>
                <td><?php echo $rows->phone_no; ?></td>
                <td><?php echo $rows->gender; ?></td>
                <td><?php if($rows->status=='Inactive'){ ?>
                  <button type="button" class="btn btn-danger btn-fw">Inactive</button>
              <?php   }else{ ?>
                <button type="button" class="btn btn-success btn-fw">Active</button>
              <?php   }
                 ?></td>
                <td>
                  <!-- <a title="Customer details" href="<?php echo base_url(); ?>home/get_customer_details/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp; -->
                  <!-- <a href="<?php echo base_url(); ?>home/get_staff_details/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> -->
                  <a title="Order list" href="<?php echo base_url(); ?>home/get_customer_orders/<?php echo base64_encode($rows->user_master_id*98765); ?>"><i class="fa fa-list"></i></a> &nbsp;&nbsp;

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


    </script>
