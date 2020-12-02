
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

                          <div class="container-fluid d-flex justify-content-between">
                            <div class="col-lg-3 pl-0">
                              <p class="mb-0 mt-5">Date : <?php echo  date('d-m-Y',strtotime($from_date)) ?> - <?php echo  date('d-m-Y',strtotime($to_date)) ?></p>

                            </div>
                          </div>
                          <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                            <div class="table-responsive">
                                <table class="table-bordered" style="font-size:13px;" >
                                  <thead>
                                    <tr class="bg-light">
                                        <th width="3%" class="text-center">#</th>
                                        <th width="7%" class="text-center">Date</th>
                                        <th width="5%" class="text-center">Invoice</th>
										<th width="10%" class="text-center">Customer Name</th>
                                        <th width="10%" class="text-center">Vendor name</th>
										<th width="10%" class="text-center">Service Category </th>
                                        <th width="5%" class="text-center">Advance Amount</th>
										<th width="5%" class="text-center">Service Amount</th>
                                        <th width="5%" class="text-center">Vendor Amount</th>
										<th width="5%" class="text-center">Skilex Amount</th>
										<th width="5%" class="text-center">GST 18%</th>
										<th width="5%" class="text-center">SGST 9%</th>
										<th width="5%" class="text-center">CGST 9%</th>
                                      </tr>
                                  </thead>
                                  <tbody>
								    <?php $i=1; foreach($res as $rows){ ?>
                                    <tr>
                                      <td class="text-center"><?php echo $i; ?></td>
                                      <td class="text-center"><?php echo date('d-m-Y', strtotime($rows->order_date)); ?></td>
                                      <td class="text-center"><?php echo $rows->so_id; ?></td>
									  <td><?php echo $rows->contact_person_name; ?></td>
									  <td><?php echo $rows->spv_name; ?></td>
                                      <td><?php echo $rows->service_name; ?></td>
                                      <td><?php echo $rows->paid_advance_amount; ?></td>
									  <td><?php echo $rows->net_service_amount; ?></td>
									  <td><?php echo $rows->serv_pro_net_amount; ?></td>
                                      <td><?php echo $rows->skilex_net_amount; ?></td>
                                      <td><?php echo $rows->skilex_tax_amount; ?></td>
									  <td><?php echo $rows->sgst_amount; ?></td>
									  <td><?php echo $rows->cgst_amount; ?></td>
                                    </tr>
									<?php $i++; } ?>
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
