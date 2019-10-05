<div class="container-fluid page-body-wrapper">
      <div class="main-panel">

        <div class="content-wrapper">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Service Experts List</span></li>
            </ol>
          </nav>
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">View Service Experts list <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
              <div class="row">
                  <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>S.no</th>
                <th>Name</th>
                <th>Online status</th>
                <th>Login status</th>

                <th>Last login</th>

                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($res as $rows){ ?>


            <tr>
                  <td><?php echo $i; ?></td>
                <td><?php echo $rows->full_name; ?></td>
                <td><?php if($rows->online_status=='Online'){ ?>
                  <button type="button" class="badge badge-success ">Online</button>
              <?php   }else{ ?>
                <button type="button" class="badge badge-danger">Offline</button>
              <?php   }
                 ?></td>
                <td><?php if($rows->status=='Inactive'){ ?>
                  <button type="button" class="badge badge-success ">Inactive</button>
              <?php   }else{ ?>
                <button type="button" class="badge badge-danger">Active</button>
              <?php   }
                 ?></td>

                <td><?php echo  date('d-m-Y H:i:s',strtotime($rows->updated_at)) ?></td>

                <td><a href="<?php echo base_url(); ?>home/get_provider_orders/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;
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
