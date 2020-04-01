<?php if(empty($tot_points)){
		$total_points =  '0';
		$points_to_claim =  '0';
		$claimed_points =  '0';
		$earned_amount =  '0';
	
}else{
	foreach($tot_points as $rows){
		$total_points =  $rows->total_points;
		$points_to_claim =  $rows->points_to_claim;
		$claimed_points =  $rows->claimed_points;
		$earned_amount =  $rows->earned_amount;
	} 
}
?>

<div class="container-fluid page-body-wrapper">
      <div class="main-panel">

        <div class="content-wrapper">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Customer Referal Points </span></li>
            </ol>
          </nav>

          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Customer Points <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
			  
			<div class="card card-statistics">

            <div class="row">
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
					<img src="<?php echo base_url(); ?>assets/admin/images/total_providers.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Total Points</p>
                        <div class="fluid-container"><h3 class="card-title mb-0"><?php echo $total_points; ?></h3>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <img src="<?php echo base_url(); ?>assets/admin/images/total_serviceman.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Points Claim</p>
                        <div class="fluid-container"><h3 class="card-title mb-0"><?php echo $points_to_claim; ?></h3></div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <img src="<?php echo base_url(); ?>assets/admin/images/total_customer.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                       <p class="card-text mb-0">Claimed Points</p>
                         <div class="fluid-container"><h3 class="card-title mb-0"><?php echo $claimed_points; ?></h3></div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <img src="<?php echo base_url(); ?>assets/admin/images/paid_orders.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Earned Amount</p>
                         <div class="fluid-container"><h3 class="card-title mb-0"><?php echo $earned_amount; ?></h3></div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
			<hr>
		<div class="row">
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
					<img src="<?php echo base_url(); ?>assets/admin/images/total_providers.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">User Earned Points</p>
                        <div class="fluid-container"><h3 class="card-title mb-0"><?php echo $earned_points['total_earned_points']; ?></h3>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
			      <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
					<img src="<?php echo base_url(); ?>assets/admin/images/total_providers.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">User Referal Points</p>
                        <div class="fluid-container"><h3 class="card-title mb-0"><?php echo $earned_points['total_referal_points']; ?></h3>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
              </div>
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
              </div>
         </div>

          </div>
		  
              <div class="row" style="padding-top:50px;">
                  <div class="col-md-12">
				  <h4 class="card-title">Referal Points History</h4>
                <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>S.no</th>
                <th>Referal date</th>
                <th>Referral code</th>
                <!-- <th>Service Expert</th> -->
                <th>Referral points</th>
            </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($referal_points as $rows){ ?>


            <tr>
                  <td><?php echo $i; ?></td>
                  <td><?php echo  date('d-m-Y',strtotime($rows->created_at)) ?></td>
                  <td><?php echo $rows->referral_code; ?></td>
                  <td><?php echo $rows->referral_points; ?></td>
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
