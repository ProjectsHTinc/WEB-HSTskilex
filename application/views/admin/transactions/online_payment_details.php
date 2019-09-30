
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
              <li class="breadcrumb-item active" aria-current="page"><span>Online Payment details</span></li>
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
                <h4 class="card-title">Online Payment Details <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
                <?php foreach($res as $rows){} ?>
                    <div class="row">
                      <div class="col-md-3 bor">Order id</div><div class="col-md-3 bor"><?php echo $rows->order_id; ?></div>
                      <div class="col-md-3 bor">Track id</div><div class="col-md-3 bor"><?php echo $rows->track_id; ?></div>
                      <div class="col-md-3 bor">Bank Ref no.</div><div class="col-md-3 bor"><?php echo $rows->bank_ref_no; ?></div>
                      <div class="col-md-3 bor">Order Status</div><div class="col-md-3 bor"><?php echo $rows->order_status; ?></div>
                      <div class="col-md-3 bor">Failure message</div><div class="col-md-3 bor"><?php echo $rows->failure_message; ?></div>
                      <div class="col-md-3 bor">Payment mode</div><div class="col-md-3 bor"><?php echo $rows->payment_mode; ?></div>
                      <div class="col-md-3 bor">Card Name</div><div class="col-md-3 bor"><?php echo $rows->card_name; ?></div>
                      <div class="col-md-3 bor">Status code</div><div class="col-md-3 bor"><?php echo $rows->status_code; ?></div>
                      <div class="col-md-3 bor">Status Message</div><div class="col-md-3 bor"><?php echo $rows->status_message; ?></div>
                      <div class="col-md-3 bor">currency</div><div class="col-md-3 bor"><?php echo $rows->currency; ?></div>
                      <div class="col-md-3 bor">Amount</div><div class="col-md-3 bor"><?php echo $rows->amount; ?></div>
                      <div class="col-md-3 bor">Trans Date</div><div class="col-md-3 bor"><?php echo $rows->trans_date; ?></div>


                    </div>
                </div>
              </div>
            </div>
      </div>
        </div>
      </div>
    </div>

    <style>
.bor{
  border: 1px solid #d2d2d2;
    padding-top: 10px;
    padding-bottom: 10px;
}
table.dataTable thead th, table.dataTable thead td {
    padding: 0px;
    text-align: center;
}
    </style>
