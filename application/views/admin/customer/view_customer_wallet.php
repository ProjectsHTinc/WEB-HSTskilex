<?php if(empty($wallet_details)){
		$total_amt_in_wallet =  '0';
		$amt_in_wallet =  '0';
		$total_amt_used =  '0';
}else{
	foreach($wallet_details as $rows){
		$total_amt_in_wallet =  $rows->total_amt_in_wallet;
		$amt_in_wallet =  $rows->amt_in_wallet;
		$total_amt_used =  $rows->total_amt_used;
	} 
}
?>

<div class="container-fluid page-body-wrapper">
      <div class="main-panel">

        <div class="content-wrapper">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Customer Wallet Details </span></li>
            </ol>
          </nav>

          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Customer Wallet <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
			  
			<div class="card card-statistics">

            <div class="row">
              <div class="card-col col-xl-4 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
					<img src="<?php echo base_url(); ?>assets/admin/images/paid_orders.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Total Amount</p>
                        <div class="fluid-container"><h3 class="card-title mb-0"><?php echo $total_amt_in_wallet; ?></h3>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-col col-xl-4 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <img src="<?php echo base_url(); ?>assets/admin/images/paid_orders.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Amount in Wallet</p>
                        <div class="fluid-container"><h3 class="card-title mb-0"><?php echo $amt_in_wallet; ?></h3></div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-col col-xl-4 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <img src="<?php echo base_url(); ?>assets/admin/images/paid_orders.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Wallet Amount Used</p>
                         <div class="fluid-container"><h3 class="card-title mb-0"><?php echo $total_amt_used; ?></h3></div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>


          </div>
		  
              <div class="row" style="padding-top:50px;">
                  <div class="col-md-12">
				  <h4 class="card-title">Wallet History</h4>
                <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>S.no</th>
                <th>Transaction date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
		  <?php if (count($wallet_list) >0){
		  $i=1; foreach($wallet_list as $rows){ ?>
            <tr>
                  <td><?php echo $i; ?></td>
                  <td><?php echo  date('d-m-Y',strtotime($rows->created_at)) ?></td>
                  <td><?php echo $rows->transaction_amt; ?></td>
                  <td><?php echo $rows->status; ?></td>
				  <td><?php echo $rows->notes; ?></td>
            </tr>
          <?php  $i++;  } } ?>


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
