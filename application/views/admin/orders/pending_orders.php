
<style>
th{
  width:200px;
}
</style><div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Pending Orders</span></li>
            </ol>
          </nav>
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card" id="list">

              <div class="card">

                 <?php if($this->session->flashdata('msg')){ ?>
                <div class="alert alert-success" role="alert">
                   <button type="button" class="close" data-dismiss="alert">x</button>
                        <?php  echo $this->session->flashdata('msg'); ?>
                </div>
              <?php } ?>

                <div class="card-body"  >
                  <h4 class="card-title">List of Pending Orders </h4>

              <table id="example" class="table table-striped table-bordered">
      <thead >
          <tr>
              <th>S.no</th>
              <th>Customer Phone</th>
              <th>Service Name ()</th>
              <th>Contact Person / Number</th>
              <th>Order date / Timeslot</th>
              <th>Service Amount</th>
              <th>Advance Amount/ status</th>
              <th>Status</th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($res as $rows){ ?>


          <tr>
                <td><?php echo $i; ?></td>
              <td><?php echo $rows->phone_no; ?>  </td>
                <td><?php echo $rows->service_name; ?> (<?php echo $rows->number_of_orders; ?>)</td>
                  <td><?php echo $rows->contact_person_name; ?> <br><br><?php echo $rows->contact_person_number; ?>
                  </td>
                  <td><?php echo  date('d-m-Y',strtotime($rows->order_date)) ?> <br><br>
                  <?php  echo date("g:i ", strtotime($rows->from_time)); ?> - <?php    echo date("g:i", strtotime($rows->to_time));?>
                 </td>
                  <td><?php echo $rows->service_rate_card; ?></td>
                    <td><?php echo $rows->advance_amount_paid; ?> <br> <?php if($rows->advance_payment_status=='NA'){ echo "Not Available";}else if($rows->advance_payment_status=='N'){ echo "Advance Unpaid"; }else{ echo "Advance Paid";} ?>  </td>
                <td>
                <button type="button" class="btn btn-danger"><?php echo $rows->status; ?></button>

               </td>
              <td><a title="View order details" href="<?php echo base_url(); ?>service_orders/get_order_details/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-info-circle"></i></a> &nbsp;&nbsp;
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
      </div>
    </div>
