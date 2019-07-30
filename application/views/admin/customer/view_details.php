<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
                    <nav aria-label="breadcrumb">
                      <ol class="breadcrumb breadcrumb-custom">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home/get_all_customer_details">Customers List</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><span>Info</span></li>
                      </ol>
                    </nav>
            <?php foreach($res as $row_customer_info){} ?>
          <div class="row">
            <div class="col-md-4 col-sm-6 grid-margin stretch-card">
							<div class="card text-center">
								<div class="card-body">
									<img src="<?php echo base_url(); ?>assets/profile/profile.jpg" class="img-lg rounded-circle mb-2" alt="profile image">
									<h4><?php echo $row_customer_info->full_name; ?></h4>
									<p class="text-muted">	<?php echo $row_customer_info->gender; ?></p>
									<p class="mt-4 card-text">
									
									</p>
									<button class="btn btn-primary btn-sm mt-3 mb-4" onclick="change_status(<?php echo $row_customer_info->status; ?>)"><?php echo $row_customer_info->status; ?></button>
									<div class="border-top pt-3">
										<div class="row">
											<div class="col-6">
												<h6>5896</h6>
												<p>Total Orders</p>
											</div>
											<div class="col-6">
												<h6>1596</h6>
												<p>Cancelled</p>
											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
            <div class="col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body pb-0">
                  <div class="wrapper border-bottom">
                    <h5 class="mb-0 text-gray">Total Paid Through Online</h5>
                    <h1 class="mb-0">598,486</h1>
                    <p class="mb-4">6.5% change from today</p>
                  </div>
                  <div class="pt-4 wrapper">
                    <h5 class="mb-0 text-gray">Total Paid Through Cash</h5>
                    <h1 class="mb-0">323,360</h1>
                    <p>2.5% change from yesterday</p>
                  </div>
                </div>
                <canvas id="product-area-chart" height="200" style="display: block;" width="352" class="chartjs-render-monitor"></canvas>
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="w-75 mx-auto">
                    <div class="d-flex justify-content-between text-center mb-2">
                      <div class="wrapper">
                        <h4>6,256</h4>
                        <small class="text-muted">Total sales</small>
                      </div>
                      <div class="wrapper">
                        <h4>8569</h4>
                        <small class="text-muted">Open Campaign</small>
                      </div>
                    </div>
                  </div>
                  <div id="morris-line-example" style="height: 250px; position: relative; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">

                  </div>
                  <div class="w-75 mx-auto">
                    <div class="d-flex justify-content-between text-center mt-5">
                      <div class="wrapper">
                        <h4>5136</h4>
                        <small class="text-muted">Online Sales</small>
                      </div>
                      <div class="wrapper">
                        <h4>4596</h4>
                        <small class="text-muted">Store Sales</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>
        <!-- content-wrapper ends -->

      </div>
      <!-- main-panel ends -->
    </div>

<script>
function change_status(status){
  alert(status);
}
</script>
