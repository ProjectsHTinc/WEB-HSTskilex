<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <!-- <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>verifyprocess/get_vendor_verify_list">Service Provider list </a></li> -->
              <li class="breadcrumb-item active" aria-current="page"><span>Commando verify list</span></li>
            </ol>
          </nav>
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Commando verify list <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
              <div class="container">
                  <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered  "  >
        <thead>
            <tr>
                <th >S.no</th>
                <th>Name <br> Phone No <br> Email</th>
                <th>Document Verify status</th>
                <!-- <th>profile_pic</th> -->
                <th>Company status</th>
                <th>Service Expert Count</th>
                <th>Deposit status</th>
                <th>Login Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($res as $rows){ ?>


            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $rows->owner_full_name; ?> <br><br> <?php echo $rows->phone_no; ?><br><br> <?php echo $rows->email; ?></td>
                <td><?php echo $rows->serv_prov_verify_status; ?></td>
                <!-- <td><?php echo $rows->profile_pic; ?></td> -->
                <td><?php echo $rows->company_status; ?></td>
                <td><?php echo $rows->no_of_service_person; ?></td>
                <td>
                  <?php if($rows->deposit_status=='Unpaid'){ ?>
                    <button type="button" class="badge badge-danger">Unpaid</button>
                <?php   }else{ ?>
                  <button type="button" class="badge badge-success">Paid</button>
                <?php   }
                   ?>
                </td>

                <td><?php if($rows->login_status=='Inactive'){ ?>
                  <button type="button" class="badge badge-danger ">Inactive</button>
              <?php   }else{ ?>
                <button type="button" class="badge badge-success ">Active</button>
              <?php   }
                 ?></td>
                <td>
                  <a title="Edit" href="<?php echo base_url(); ?>verifyprocess/get_vendor_details/<?php echo base64_encode($rows->user_master_id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;
                  <a title="View Documents " href="<?php echo base_url(); ?>verifyprocess/get_vendor_doc_status/<?php echo $rows->company_status; ?>/<?php echo base64_encode($rows->user_master_id*98765); ?>"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>&nbsp;
                                  <a  title="View Experts list" href="<?php echo base_url(); ?>verifyprocess/service_person_list/<?php echo base64_encode($rows->user_master_id*98765); ?>"><i class="fa fa-user" aria-hidden="true"></i></a>&nbsp;

                      <a  title="Category list" href="<?php echo base_url(); ?>verifyprocess/get_skills_details/<?php echo base64_encode($rows->user_master_id*98765); ?>"><i class="fa fa-list-ol" aria-hidden="true"></i></a>



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
    <style>
    .fa{
      color:  #0b205dad;
    }
    </style>
