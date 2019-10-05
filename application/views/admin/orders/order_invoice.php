
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>verifyprocess/get_vendor_verify_list">Service Orders </a></li>
              <li class="breadcrumb-item active" aria-current="page"><span> Invoice </span></li>
            </ol>
          </nav>

          <div class="row">
              <div class="col-lg-12">
                  <div class="card px-2">
                      <div class="card-body"  id="printableArea">
                          <div class="container-fluid">
                              <?php foreach($res as $rows)  ?>
                            <h3 class="text-right">Invoice&nbsp;&nbsp;#SKILEX-<?php echo $rows->id; ?><?php echo $rows->so_id;  ?></h3>
                            <hr>
                          </div>

                          <!-- <div class="container-fluid d-flex justify-content-between">
                            <div class="col-lg-3 pl-0">
                              <p class="mt-5 mb-2"><b>LibertyUI</b></p>
                              <p>104,<br>Minare SK,<br>Canada, K1A 0G9.</p>
                            </div>
                            <div class="col-lg-3 pr-0">
                              <p class="mt-5 mb-2 text-right"><b>Invoice to</b></p>
                              <p class="text-right">Gaala &amp; Sons,<br> C-201, Beykoz-34800,<br> Canada, K1A 0G9.</p>
                            </div>
                          </div> -->
                          <div class="container-fluid d-flex justify-content-between">
                            <div class="col-lg-3 pl-0">
                              <p class="mb-0 mt-5">Order  Date : <?php echo  date('d-m-Y',strtotime($rows->order_date)) ?></p>

                            </div>
                          </div>
                          <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                            <div class="table-responsive w-100">
                                <table class="table">
                                  <thead>
                                    <tr class="bg-light">
                                        <th>#</th>
                                        <th>Description</th>
                                        <th class="text-right">Total</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    <tr class="text-right">
                                        <td class="text-left">1</td>
                                      <td class="text-left">Service amount</td>
                                      <td><?php echo $rows->service_amount; ?></td>
                                    </tr>
                                    <tr class="text-right">
                                      <td class="text-left">2</td>
                                      <td class="text-left">Advance amount</td>
                                      <td><?php echo $rows->paid_advance_amount; ?></td>
                                    </tr>
                                    <tr class="text-right">
                                      <td class="text-left">2</td>
                                      <td class="text-left">Additional Service amount</td>
                                      <td><?php echo $rows->ad_service_amount; ?></td>
                                    </tr>
                                    <tr class="text-right">
                                      <td class="text-left">2</td>
                                      <td class="text-left">Discount amount &nbsp; <b>(<?php echo $rows->offer_code; ?>)</b></td>
                                      <td><?php echo $rows->discount_amt; ?></td>
                                    </tr>

                                  </tbody>
                                </table>
                              </div>
                          </div>
                          <div class="container-fluid mt-5 w-100">

                            <!-- <p class="text-right">CGST (8%) : <?php echo $rows->cgst_amount; ?></p>
                              <p class="text-right">SGST (8%) : <?php echo $rows->sgst_amount; ?></p> -->
                            <h4 class="text-right mb-5">Total : <?php echo $rows->payable_amount; ?></h4>
                            <hr>
                          </div>

                      </div>
                      <div class="" style="    margin-top: -50px;    margin-bottom: 20px;    color: #fff;">
                        <a target="_blank" onclick="printDiv('printableArea')" class="btn btn-primary float-right mt-4 ml-2"><i class="mdi mdi-printer mr-1"></i>Print</a>

                      </div>

                  </div>
              </div>
          </div>
        </div>
    </div>
</div>

<script>


function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
