<?php  $role=$this->session->userdata('user_role'); ?>
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Category </a></li>
                    <li class="breadcrumb-item"><a href="#">Service </a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>Update Service</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Update Service <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
                  <?php foreach($res as $rows){} ?>
                  <form class="forms-sample" id="update_category" method="post" action="<?php echo base_url(); ?>masters/service_update" enctype="multipart/form-data">


                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Service name (English)</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Service Name" value="<?php echo $rows->service_name; ?>">
                            <input type="hidden" class="form-control" id="service_id" name="service_id"  value="<?php echo base64_encode($rows->id*98765); ?>">
                            <input type="hidden" class="form-control" id="cat_old_img" name="cat_old_img"  value="<?php echo $rows->service_pic; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Service Name(Tamil)</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control" id="service_ta_name" name="service_ta_name" placeholder="Service Tamil Name"  value="<?php echo $rows->service_ta_name; ?>">
                              <input type="hidden" class="form-control" id="sub_cat_id" name="sub_cat_id" placeholder="Service Tamil Name"  value="<?php echo $rows->sub_cat_id; ?>">
                          </div>
                        </div>
                      </div>
                    </div>


                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Rate Card</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control" id="rate_card" name="rate_card" placeholder="Rate Card" value="<?php echo $rows->rate_card; ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">

                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Enable Advance Amt</label>
                          <div class="col-sm-7">
                            <select class="form-control form-control-sm" id="is_advance_payment" name="is_advance_payment">
                              <option value=""> -Select--</option>
                              <option value="Y">Yes</option>
                              <option value="N">No</option>
                            </select>
                              <script>$('#is_advance_payment').val('<?php echo $rows->is_advance_payment; ?>');</script>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6" id="adv_amt">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Advance Amt</label>
                          <div class="col-sm-7">
                              <input type="text" class="form-control" id="advance_amount" name="advance_amount" placeholder="Advance Amount "  value="<?php echo $rows->advance_amount; ?>">
                          </div>
                        </div>
                      </div>


                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Rate Card Details(English)</label>
                          <div class="col-sm-7">
                            <textarea rows="4" class="form-control" id="rate_card_details" name="rate_card_details" placeholder="Rate Card Details"><?php echo $rows->rate_card_details; ?></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Rate Card Details(Tamil)</label>
                          <div class="col-sm-7">
                              <textarea rows="4" class="form-control" class="form-control" id="rate_card_details_ta" name="rate_card_details_ta" placeholder="Rate Card Details" ><?php echo $rows->rate_card_details_ta; ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Inclusions (English)</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control" id="inclusions" name="inclusions" placeholder="Inclusions " value="<?php echo $rows->inclusions; ?>">

                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Inclusions (Tamil)</label>
                          <div class="col-sm-7">
                              <input type="text" class="form-control" id="inclusions_ta" name="inclusions_ta" placeholder="Inclusions " value="<?php echo $rows->inclusions_ta; ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Exclusions (English)</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control" id="exclusions" name="exclusions" placeholder="Exclusions" value="<?php echo $rows->exclusions; ?>">

                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Exclusions (Tamil)</label>
                          <div class="col-sm-7">
                              <input type="text" class="form-control" id="exclusions_ta" name="exclusions_ta" placeholder="Exclusions" value="<?php echo $rows->exclusions_ta; ?>">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Service Procedure (English)</label>
                          <div class="col-sm-7">
                            <textarea rows="4" type="text" class="form-control" id="service_procedure" name="service_procedure" placeholder="Service Procedure"><?php echo $rows->service_procedure; ?></textarea>

                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Service Procedure (Tamil)</label>
                          <div class="col-sm-7">
                              <textarea rows="4" type="text" class="form-control" id="service_procedure_ta" name="service_procedure_ta" placeholder="Service Procedure" ><?php echo $rows->service_procedure_ta; ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Others (English)</label>
                          <div class="col-sm-7">
                            <textarea rows="4" type="text" class="form-control" id="others" name="others" placeholder="Others"><?php echo $rows->others; ?></textarea>

                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Others (Tamil)</label>
                          <div class="col-sm-7">
                              <textarea rows="4" type="text" class="form-control" id="others_ta" name="others_ta" placeholder="Others" ><?php echo $rows->others_ta; ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Service New images</label>
                          <div class="col-sm-7">
                            <input type="file" class="form-control" id="service_pic" name="service_pic" placeholder="">

                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Old Image</label>
                          <div class="col-sm-7">
                            <img src="<?php echo base_url(); ?>assets/category/<?php echo $rows->service_pic; ?>" style="width:100px;">
                          </div>
                        </div>
                      </div>
                    </div>



                    <?php if($role=='7'){ ?>

                    <?php  }else{ ?>
                      <div class="form-group">
                        <label for="exampleFormControlSelect3">Status</label>
                        <select class="form-control form-control-sm" id="status" name="status">
                          <option value="Active">Active</option>
                          <option value="Inactive">Inactive</option>

                        </select>
                        <script>$('#status').val('<?php echo $rows->status; ?>');</script>
                      </div>
                    <?php  } ?>


                    <button type="submit" class="btn btn-primary mr-2">Update Service</button>

                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="row">

            <div class="col-md-6">
              <div class="category_img" style="margin-top:200px;">
                <p>Old Image</p>

              </div>
            </div>


          </div>
        </div>
      </div>
    </div>
    <?php if($rows->is_advance_payment=='Y'){ ?>
      <style>#adv_amt{ display: block; }</style>
    <?php }else{ ?>
      <style>#adv_amt{ display: none;}</style>
<?php    } ?>
    <script>

    $("#is_advance_payment").bind("change  keyup", function() {

       var ad=$(this).val();
       if(ad=='Y'){
         $('#adv_amt').show();
       }else{
          $('#adv_amt').hide();
       }
    });
    $('#update_category').validate({
    rules: {

        service_name: { required: true

                  // remote: {
                  //        url: "<?php echo base_url(); ?>masters/checkserviceexist/<?php echo $rows->id; ?>",
                  //        type: "post"
                  //     }
            },
        service_ta_name: { required: true
                  // remote: {
                  //        url: "<?php echo base_url(); ?>masters/checkservicetamilexist/<?php echo $rows->id; ?>",
                  //        type: "post"
                  //     }
         },
         is_advance_payment:{required:true},
         advance_amount:{required:true,number:true},
         rate_card:{required:true},
         rate_card_details:{required:true },
         rate_card_details_ta:{required:true },
         inclusions:{required:true},
         inclusions_ta:{required:true },
         exclusions:{required:true },
         exclusions_ta:{required:true },
         service_procedure:{required:true },
         service_procedure_ta:{required:true },
         others:{required:true },
         others_ta:{required:true },
        service_pic: {required: false,extension: "jpg,jpeg,png" }
    },
    messages: {
        service_pic:{
            required :"Please Select Service Picture",extension:"File must be JPG OR PNG"
        },
        is_advance_payment:{required:"Select Advance type"},
        advance_amount:{required:"Enter the amount",number:"Enter only numbers"},
        rate_card:{required:"Enter rate card value",digits: true},
        rate_card_details:{required:"Enter rate card details in english " },
        rate_card_details_ta:{required:"Enter the rate card details in tamil" },
        inclusions:{required:"Enter the inclusions"},
        inclusions_ta:{required:"Enter the inclusions in tamil" },
        exclusions:{required:"Enter the exclusion in english" },
        exclusions_ta:{required:"enter the exclusion in tamil" },
        service_procedure:{required:"enter the procedure in english" },
        service_procedure_ta:{required:"enter the procedure in tamil" },
        others:{required:"enter the other details in english" },
        others_ta:{required:"enter the other details in tamil" },
        service_name: {
    					 required: "Please Enter Service Name.",
    					 remote: "Service Name  already in Exist!"
    							 },
         service_ta_name: {
               required: "Please Enter Service Tamil Name.",
               remote: "Service Tamil Name  Already in Exist!"
                   },

    }
    });
    </script>
