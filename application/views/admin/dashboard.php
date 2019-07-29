<!-- partial -->
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-12 grid-margin">
          <div class="card card-statistics">
            <div class="row">
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                      <i class="mdi mdi-account-multiple-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Total Providers</p>
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
                      <i class="mdi mdi-checkbox-marked-circle-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Total Service men</p>
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
                      <i class="mdi mdi-trophy-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Total Customers</p>
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
                      <i class="mdi mdi-target text-primary mr-0 mr-sm-4 icon-lg"></i>
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Total Paid orders</p>
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
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 grid-margin">
          <div class="card card-statistics">
            <div class="row">
              <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                      <i class="mdi mdi-account-multiple-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Pending Orders</p>
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
                      <i class="mdi mdi-checkbox-marked-circle-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Cancelled Orders</p>
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
                      <i class="mdi mdi-trophy-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Ongoing Orders</p>
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
                      <i class="mdi mdi-target text-primary mr-0 mr-sm-4 icon-lg"></i>
                      <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">Total Transaction</p>
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
        </div>
      </div>


      <div class="row">
        <div class="col-12 grid-margin">
          <div class="card">
            <div class="table-responsive">
              <table class="table center-aligned-table">
                <thead>
                  <tr class="bg-light">
                    <th class="border-bottom-0">ID</th>
                    <th class="border-bottom-0">Assignee</th>
                    <th class="border-bottom-0">Task Details</th>
                    <th class="border-bottom-0">Payment Method</th>
                    <th class="border-bottom-0">Payment Status</th>
                    <th class="border-bottom-0">Amount</th>
                    <th class="border-bottom-0">Tracking Number</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>#320</td>
                    <td>Mark C.Diaz</td>
                    <td>Support of thteme</td>
                    <td>Credit card</td>
                    <td><label class="badge badge-success">Approved</label></td>
                    <td>$12,245</td>
                    <td>JPBBN435893458</td>
                  </tr>
                  <tr>
                    <td>#321</td>
                    <td>Jose D</td>
                    <td>Verify your email address !</td>
                    <td>Internet banking</td>
                    <td><label class="badge badge-warning">Pending</label></td>
                    <td>$12,245</td>
                    <td>BDYBN435893325</td>
                  </tr>
                  <tr>
                    <td>#322</td>
                    <td>Philips T</td>
                    <td>Item support message send</td>
                    <td>Credit card</td>
                    <td><label class="badge badge-success">Approved</label></td>
                    <td>$12,245</td>
                    <td>JSNTN435884258</td>
                  </tr>
                  <tr>
                    <td>#323</td>
                    <td>Luke Pixel</td>
                    <td>New submission on website</td>
                    <td>Cash on delivery</td>
                    <td><label class="badge badge-danger">Rejected</label></td>
                    <td>$12,245</td>
                    <td>JPABT435893678</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>


    </div>
    <!-- content-wrapper ends -->

  </div>
  <!-- main-panel ends -->
</div>
