<style>
.dash_icons{
  width: 50px;
  margin-left: 10px;
  margin-right: 10px;
}
.welcome_text{
  text-align: center;
  font-size: 20px;
}
</style>

<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-12 grid-margin" style="margin-top:60px;">
          <?php   $role=$this->session->userdata('user_role'); ?>
          <?php if($role=='7'|| $role=='6'){ ?>
                <div class="row">
                  <div class="col-md-12">
                    <p class="text-center welcome_text">Welcome to SkilEx</p>
                  </div>

                </div>
            <?php  }else{ ?>
          <div class="card card-statistics">

            <div class="row">
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                      <img src="<?php echo base_url(); ?>assets/admin/images/total_providers.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                          <a href="<?php echo base_url(); ?>home/get_all_provider_list"><p class="card-text mb-0">Total Associates</p></a>
                        <div class="fluid-container">
                          <?php if(empty($res_provider_count)){
                          }else{
                            foreach($res_provider_count as $rows_provider_count){} ?>
                            <h3 class="card-title mb-0"><?php echo $rows_provider_count->provider_count; ?></h3>
                          <?php } ?>
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
                        <a href="<?php echo base_url(); ?>home/get_all_person_list"><p class="card-text mb-0">Total Experts </p></a>
                        <div class="fluid-container">
                          <?php if(empty($res_person_count)){
                          }else{
                            foreach($res_person_count as $rows_person_count){} ?>
                              <h3 class="card-title mb-0"><?php echo $rows_person_count->person_count; ?></h3>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <img src="<?php echo base_url(); ?>assets/admin/images/total_customer.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <a href="<?php echo base_url(); ?>home/get_all_customer_details"><p class="card-text mb-0">Total Customers</p></a>
                        <div class="fluid-container">
                          <?php if(empty($res_cust_count)){
                          }else{
                            foreach($res_cust_count as $rows_cust_count){} ?>
                              <h3 class="card-title mb-0"><?php echo $rows_cust_count->customer_count; ?></h3>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <img src="<?php echo base_url(); ?>assets/admin/images/paid_orders.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <a href="<?php echo base_url(); ?>service_orders/completed_orders"><p class="card-text mb-0">Total Paid orders</p></a>
                        <div class="fluid-container">
                          <?php if(empty($res_paid_count)){
                          }else{
                            foreach($res_paid_count as $rows_paid_count){} ?>
                              <h3 class="card-title mb-0"><?php echo $rows_paid_count->paid_orders; ?></h3>
                          <?php } ?>
                        </div>
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
                    <img src="<?php echo base_url(); ?>assets/admin/images/pending_orders.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <a href="<?php echo base_url(); ?>service_orders/pending_orders"><p class="card-text mb-0">Pending Orders</p></a>
                        <div class="fluid-container">
                          <?php if(empty($res_pending_count)){
                          }else{
                            foreach($res_pending_count as $rows_pending_count){} ?>
                              <h3 class="card-title mb-0"><?php echo $rows_pending_count->pending_orders; ?></h3>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                      <img src="<?php echo base_url(); ?>assets/admin/images/cancel_orders.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <a href="<?php echo base_url(); ?>service_orders/cancelled_orders"><p class="card-text mb-0">Cancelled Orders</p></a>
                        <div class="fluid-container">
                          <?php if(empty($res_cancelled_count)){
                          }else{
                            foreach($res_cancelled_count as $rows_cancelled_count){} ?>
                              <h3 class="card-title mb-0"><?php echo $rows_cancelled_count->cancelled_orders; ?></h3>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                      <img src="<?php echo base_url(); ?>assets/admin/images/ongoing_orders.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <a href="<?php echo base_url(); ?>service_orders/ongoing_orders"><p class="card-text mb-0">Ongoing Orders</p></a>
                        <div class="fluid-container">
                          <?php if(empty($res_onging_count)){
                          }else{
                            foreach($res_onging_count as $rows_onging_count){} ?>
                              <h3 class="card-title mb-0"><?php echo $rows_onging_count->ongoing_orders; ?></h3>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                      <img src="<?php echo base_url(); ?>assets/admin/images/total_transaction.png" class="img-responsive dash_icons">
                      <div class="wrapper text-center text-sm-left">
                        <a href="<?php echo base_url(); ?>transaction/daily_transaction"><p class="card-text mb-0">Total Transaction</p></a>
                        <div class="fluid-container">
                          <?php if(empty($res_total_trans_count)){
                          }else{
                            foreach($res_total_trans_count as $rows_total_trans_count){} ?>
                              <h3 class="card-title mb-0"><?php echo $rows_total_trans_count->total_transactions; ?></h3>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>

          </div>
            <?php } ?>
        </div>
      </div>




    </div>
    <!-- content-wrapper ends -->

  </div>
  <!-- main-panel ends -->
</div>
