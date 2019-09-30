<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>verifyprocess/get_vendor_verify_list">Service Associate  </a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Service Expert list</span></li>
            </ol>
          </nav>
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Service Expert list <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
              <div class="container">
                  <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered  "  >
        <thead>
            <tr>
                <th >S.no</th>
                <th>Name <br> Phone No <br> Email</th>
                <th>Profile Pic</th>
                <th>Login status</th>
                <th>Verify status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($res as $rows){ ?>


            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $rows->full_name; ?> <br><br> <?php echo $rows->phone_no; ?><br><br> <?php echo $rows->email; ?></td>
                <td><?php echo $rows->profile_pic; ?></td>


                <td><?php if($rows->status=='Inactive'){ ?>
                  <button type="button" class="btn btn-danger ">Inactive</button>
              <?php   }else{ ?>
                <button type="button" class="btn btn-success ">Active</button>
              <?php   }
                 ?></td>
                 <td><?php echo $rows->serv_pers_verify_status; ?></td>
                <td><a title="View details" href="<?php echo base_url(); ?>verifyprocess/service_person_details/<?php echo base64_encode($rows->user_master_id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;

                  <a title="Document list" href="<?php echo base_url(); ?>verifyprocess/person_doc_details/<?php echo base64_encode($rows->user_master_id*98765); ?>"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
                  &nbsp;
                  &nbsp;
                    <a href="<?php echo base_url(); ?>verifyprocess/get_skills_details/<?php echo base64_encode($rows->user_master_id*98765); ?>"><i class="fa fa-list-ol" aria-hidden="true"></i></a>
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
