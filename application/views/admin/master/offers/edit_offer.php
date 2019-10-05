<?php  $role=$this->session->userdata('user_role'); ?>
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Offers</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Update Offer </h4>
                  <?php foreach($res as $rows){} ?>
                  <form class="forms-sample" id="create_offer" method="post" action="<?php echo base_url(); ?>offers/update_offers" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-6"></div>
                      <div class="col-md-6"></div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="username">Offer  Title</label>
                          <input type="text" class="form-control" id="offer_title" name="offer_title" placeholder="offer  title" value="<?php echo $rows->offer_title; ?>">
                          <input type="hidden" class="form-control" id="offer_id" name="offer_id"  value="<?php echo base64_encode($rows->id*98765); ?>">
                        </div>

                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Offer  Code</label>
                            <input type="text" class="form-control" id="offer_code" name="offer_code" placeholder="offer code" value="<?php echo $rows->offer_code; ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="username">Offer Percent</label>
                          <input type="text" class="form-control" id="offer_percent" name="offer_percent" placeholder="offer percentage" value="<?php echo $rows->offer_percent; ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                            <label for="username">Maximum Amount</label>
                            <input type="text" class="form-control" id="max_offer_amount" name="max_offer_amount" value="<?php echo $rows->max_offer_amount; ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                            <label for="username">Minimum Purchase Amount</label>
                            <input type="text" class="form-control" id="minimum_purchase_amt" name="minimum_purchase_amt" value="<?php echo $rows->minimum_purchase_amt; ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Offer Descriptions</label>
                            <textarea id="offer_description" class="form-control" name="offer_description"><?php echo $rows->offer_description; ?></textarea>
                        </div>
                      </div>
                      <?php if($role=='7'||$role=='6'){ ?>

                   <?php  }else{ ?>
                     <div class="col-md-6">
                       <div class="form-group">
                         <label for="exampleFormControlSelect3">Status</label>
                         <select class="form-control form-control-sm" id="status" name="status">
                           <option value="Active">Active</option>
                           <option value="Inactive">Inactive</option>
                         </select>
                         <script>$('#status').val('<?php echo $rows->status;?>');</script>
                       </div>
                     </div>

                   <?php  } ?>

                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Update offer</button>
                  </form>
                </div>
              </div>
            </div>






          </div>
        </div>
      </div>
    </div>
    <script>

      $('#create_offer').validate({
      rules: {

          offer_title: { required: true,
            remote: {
                    url: "<?php echo base_url(); ?>offers/checkoffer_title_exist/<?php echo $rows->id; ?>",
                   type: "post"
                }
              },
          offer_code: { required: true,
            remote: {
                    url: "<?php echo base_url(); ?>offers/checkoffer_code_exist/<?php echo $rows->id; ?>",
                   type: "post"
                }
              },
          offer_percent:{required:true},
          max_offer_amount: {required:true,number: true },
          minimum_purchase_amt:{required:true,number: true },
          offer_description:{required:true},
          status: { required: true}
      },
      messages: {
        offer_title: {
      					 required: "Enter offer title.",
      					 remote: "offer title  already in Exist!"
      							 },
         offer_code: {
     					 required: "Enter offer code.",
     					 remote: "offer code  already in Exist!"
     							 },
          offer_percent:{required:"enter the percentage"},
          max_offer_amount: {required: "enter the max amount." },
          minimum_purchase_amt: {required: "enter the Minimum Pruchase amount." },
          offer_description:{required:"enter the description"},
          status: {required: "select status." },

      }
      });


    </script>
