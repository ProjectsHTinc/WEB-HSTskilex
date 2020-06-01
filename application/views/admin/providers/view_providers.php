<style>
.formlabel{
  text-align: left;
  justify-content: flex-start !important;
}
.badge-danger{
  background-color: #c41c1c !important;
}
.badge-success{
  background-color: #478e2e !important;
}
.dropdown-toggle::after{
  display: none;
}
</style>

<div class="container-fluid page-body-wrapper">
      <div class="main-panel">

        <div class="content-wrapper">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Commandos List</span></li>
            </ol>
          </nav>
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">View Commandos list</h4>
              <div class="row">
                  <div class="col-md-12">
                <form action="<?php echo base_url(); ?>home/search_provider" method="post" class="form-inline">
                    <div class="col-md-4">
                        <label class="formlabel">Category list</label>

                        <select id="lstFruits" class="form-control" name="category_id" required>
                          <option value="">--Select--</option>
                          <?php foreach($res_category as $rows_cat){ ?>
                            <option value="<?php echo $rows_cat->id; ?>"><?php echo $rows_cat->main_cat_name; ?></option>
                          <?php } ?>
                        </select>
                        <script>$('#lstFruits').val('<?php echo $category_id; ?>');</script>

                    </div>
                    <div class="col-md-2">
                        <label class="formlabel">Type</label>
                        <select name="type" id="type" class="form-control">
                          <option value="All">All</option>
                            <option value="Company">Company</option>
                            <option value="Individual">Individual</option>
                        </select>
                        <script>$('#type').val('<?php echo $type; ?>');</script>
                    </div>
                    <div class="col-md-2">
                        <label class="formlabel"></label><br>
                        <input type="submit" class="btn btn-primary" value="Search">
                    </div>

                    <div class="col-md-2">
                        <label class="formlabel"></label><br>
                        <a href="<?php echo base_url(); ?>home/get_all_provider_list" class="btn btn-primary" value="Search">Show all</a>
                    </div>
                </form>
              </div>
              </div>
              <div class="row">
                  <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>S.no <br>
                User ID</th>
                <th>Name</th>
                <th>Online status</th>
                <th>Login status</th>
                <th>Company Status</th>
                <th>Last login</th>

                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($res as $rows){ ?>


            <tr>
                  <td><?php echo $i; ?> - &nbsp; <?php  echo $rows->id; ?></td>
                <td><?php echo $rows->owner_full_name; ?></td>
                <td><?php if($rows->online_status=='Online'){ ?>
                  <button type="button" class="badge badge-success ">Online</button>
              <?php   }else{ ?>
                <button type="button" class="badge badge-danger">Offline</button>
              <?php   }
                 ?></td>
                <td><?php if($rows->status=='Inactive'){ ?>
                  <button type="button" class="badge badge-danger ">Inactive</button>
              <?php   }else{ ?>
                <button type="button" class="badge badge-success">Active</button>
              <?php   }
                 ?></td>
                <td><?php echo $rows->company_status; ?></td>
                <td><?php echo  date('d-m-Y H:i:s',strtotime($rows->updated_at)) ?></td>

                <td>
                  <a title="Order list" href="<?php echo base_url(); ?>home/get_provider_orders/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-list"></i></a> &nbsp;&nbsp;
                  <!-- <a href="<?php echo base_url(); ?>home/get_staff_details/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> -->
                  &nbsp;   <a  title="View Experts list" href="<?php echo base_url(); ?>home/get_all_person_list/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-users" aria-hidden="true"></i></a>
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
    <script type="text/javascript">




    </script>
