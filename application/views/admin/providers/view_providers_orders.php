<div class="container-fluid page-body-wrapper">
      <div class="main-panel">

        <div class="content-wrapper">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Commandos order List</span></li>
            </ol>
          </nav>
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">View Commandos orders <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
              <div class="row">
                  <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>S.no</th>
                <th>Order date</th>
                <th>Service</th>
                <th>Service men</th>
                <th>Order Iniated</th>
                <th>Order status</th>

            </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($res as $rows){ ?>


            <tr>
                  <td><?php echo $i; ?></td>
                  <td><?php echo  date('d-m-Y',strtotime($rows->order_date)) ?></td>
                  <td><?php echo $rows->service_name; ?></td>
                <td><?php echo $rows->full_name; ?></td>
                <td><?php echo  date('d-m-Y H:i:s',strtotime($rows->iniate_datetime)) ?></td>
                <td>
                  <button type="button" class="badge badge-info "><?php echo $rows->status; ?></button>
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
