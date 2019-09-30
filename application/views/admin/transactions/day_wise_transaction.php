
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
              <li class="breadcrumb-item active" aria-current="page"><span>Date based  transactions</span></li>
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
                  <h4 class="card-title">Date wise Transaction</h4>

              <table id="example" class="table table-striped table-bordered">
      <thead >
          <tr style="height:90px;">
              <th>S.no</th>
              <th>Date</th>
              <th>Service Per day</th>
              <th>Amount</th>

          </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($res as $rows){ ?>
          <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo date('d-m-Y', strtotime($rows->service_date));  ?></td>
                <td><?php echo $rows->service_per_day; ?></td>
                <td><?php echo $rows->total_amt; ?></td>
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
