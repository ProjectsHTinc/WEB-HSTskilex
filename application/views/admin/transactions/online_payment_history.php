
<style>
th{
  padding: 0px 0px 0px 0px;
}
table.dataTable thead th, table.dataTable thead td{
  padding: 0px;
}

</style><div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Transactions</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Online Payment history</span></li>
            </ol>
          </nav>
          <div class="row">
            <div class="container" id="list">

              <div class="card">

                 <?php if($this->session->flashdata('msg')){ ?>
                <div class="alert alert-success" role="alert">
                   <button type="button" class="close" data-dismiss="alert">x</button>
                        <?php  echo $this->session->flashdata('msg'); ?>
                </div>
              <?php } ?>

                <div class="card-body"  >
                  <h4 class="card-title">Online Payment history</h4>

              <table id="example" class="table table-striped table-bordered">
      <thead >
          <tr style="height:90px;">
              <th>S.no</th>
              <th>Order id</th>
              <th>Track id</th>
              <th>Status</th>
              <th>Amount</th>
              <th>Payment date</th>
              <th>Action</th>

          </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($res as $rows){ ?>


          <tr>




                <td><?php echo $i; ?></td>
                <td><?php echo $rows->order_id; ?></td>
                <td><?php echo $rows->track_id; ?></td>
                <td><?php echo $rows->order_status; ?></td>
                <td><?php echo $rows->amount; ?></td>
                <td><?php echo $rows->trans_date; ?></td>
                <td><a href="<?php echo base_url(); ?>transaction/online_payment_details/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-list-alt"></i></a></td>
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

    <style>

table.dataTable thead th, table.dataTable thead td {
    padding: 0px;
    text-align: center;
}
    </style>
