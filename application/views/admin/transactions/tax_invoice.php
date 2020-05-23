
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
                              <?php foreach($res as $rows) {}	  ?>
                            <h3 class="text-right">Invoice&nbsp;&nbsp;#SKILEX</h3>
                            <hr>
                          </div>


                          <div class="container-fluid d-flex justify-content-between">
                            <div class="col-lg-3 pl-0">
                              <!-- <p class="mb-0 mt-5">Order  Date : <?php echo  date('d-m-Y',strtotime($rows->order_date)) ?></p> -->

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
                                      <td class="text-left">Service total amount</td>
                                      <td><?php echo $rows->tot_amount; ?></td>
                                    </tr>
                                    <tr class="text-right">
                                      <td class="text-left">2</td>
                                      <td class="text-left">Commondo amount</td>
                                      <td><?php echo $rows->sp_commission; ?></td>
                                    </tr>
                                    <tr class="text-right">
                                      <td class="text-left">3</td>
                                      <td class="text-left">Skilex amount</td>
                                      <td><?php echo $rows->sk_commision + $rows->tax_amount ; ?></td>
                                    </tr>
                                    <tr class="text-right">
                                      <td class="text-left">3</td>
                                      <td class="text-left">SGST</td>
                                      <td><?php echo $rows->tax_amount/2; ?></td>
                                    </tr>
                                    <tr class="text-right">
                                      <td class="text-left">3</td>
                                      <td class="text-left">CGST</td>
                                      <td><?php echo $rows->tax_amount/2; ?></td>
                                    </tr>


                                  </tbody>
                                </table>
                              </div>
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
