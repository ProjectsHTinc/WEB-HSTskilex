<?php  $role=$this->session->userdata('user_role'); ?>

<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Category</a></li>
              <li class="breadcrumb-item"><a href="#">Sub Category</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span> Services</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Create  Service   <br> <?php  $string =$this->uri->segment(4);
                  echo preg_replace("/[^a-zA-Z]/", " ", $string) ?> <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4><br>

                  <form class="forms-sample" id="create_service" method="post" action="<?php echo base_url(); ?>masters/service_creation" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Service name (English)</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Service Name">
                            <input type="hidden" class="form-control" id="sub_cat_id" name="sub_cat_id" value="<?php echo $this->uri->segment(3); ?>">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Service Name(Tamil)</label>
                          <div class="col-sm-7">
                              <input type="text" class="form-control" id="service_ta_name" name="service_ta_name" placeholder="Service Tamil Name" >
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Rate Card</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control" id="rate_card" name="rate_card" placeholder="Rate Card">
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
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6" id="adv_amt">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Advance Amt</label>
                          <div class="col-sm-7">
                              <input type="text" class="form-control" id="advance_amount" name="advance_amount" placeholder="Advance Amount " >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Rate Card Details(English)</label>
                          <div class="col-sm-7">
                            <textarea rows="4" class="form-control" id="rate_card_details" name="rate_card_details" placeholder="Rate Card Details"></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Rate Card Details(Tamil)</label>
                          <div class="col-sm-7">
                              <textarea rows="4" class="form-control" class="form-control" id="rate_card_details_ta" name="rate_card_details_ta" placeholder="Rate Card Details" ></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Inclusions (English)</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control" id="inclusions" name="inclusions" placeholder="Inclusions ">

                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Inclusions (Tamil)</label>
                          <div class="col-sm-7">
                              <input type="text" class="form-control" id="inclusions_ta" name="inclusions_ta" placeholder="Inclusions " >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Exclusions (English)</label>
                          <div class="col-sm-7">
                            <input type="text" class="form-control" id="exclusions" name="exclusions" placeholder="Exclusions">

                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Exclusions (Tamil)</label>
                          <div class="col-sm-7">
                              <input type="text" class="form-control" id="exclusions_ta" name="exclusions_ta" placeholder="Exclusions" >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Service Procedure (English)</label>
                          <div class="col-sm-7">
                            <textarea rows="4" type="text" class="form-control" id="service_procedure" name="service_procedure" placeholder="Service Procedure"></textarea>

                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Service Procedure (Tamil)</label>
                          <div class="col-sm-7">
                              <textarea rows="4" type="text" class="form-control" id="service_procedure_ta" name="service_procedure_ta" placeholder="Service Procedure" ></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Others (English)</label>
                          <div class="col-sm-7">
                            <textarea rows="4" type="text" class="form-control" id="others" name="others" placeholder="Others"></textarea>

                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-5 col-form-label">Others (Tamil)</label>
                          <div class="col-sm-7">
                              <textarea rows="4" type="text" class="form-control" id="others_ta" name="others_ta" placeholder="Others" ></textarea>
                          </div>
                        </div>
                      </div>
                    </div>


                    <div class="form-group">
                      <label for="latitude">Service Picture</label>
                      <input type="file" class="form-control" id="service_pic" name="service_pic" placeholder="">
                    </div>


                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Status</label>
                      <select class="form-control form-control-sm" id="status" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>

                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Create Service</button>

                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="row" id="list">

            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <?php if($this->session->flashdata('msg')) {
                $message = $this->session->flashdata('msg');?>
                <div class="<?php echo $message['class'] ?>">
                  <?php echo $message['message']; ?>
                </div>
              <?php  }  ?>
                <div class="card-body">
                  <h4 class="card-title">List of Service <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
              <table id="example" class="table table-striped table-bordered">
      <thead>
          <tr>
              <th>S.no</th>
              <th>Service</th>
              <th>Service Picture</th>
              <th>Advance Amt</th>
              <th>Rate</th>
              <th>Status</th>
              <?php if($role=='6'){

              }else{ ?><th>Actions</th><?php } ?>
          </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($res as $rows){ ?>


          <tr>
                <td><?php echo $i; ?></td>
              <td><?php echo $rows->service_name; ?> <br><br><?php echo $rows->service_ta_name; ?>
              </td>

              <td><img src="<?php echo base_url(); ?>assets/category/<?php echo $rows->service_pic; ?>" class="img-responsive" style="width:100px;    height: auto;"> </td>
                <td><?php echo $rows->advance_amount; ?></td>
                <td><?php echo $rows->rate_card; ?></td>
                  <td><?php if($rows->status=='Inactive'){ ?>
                <button type="button" class="badge badge-danger">Inactive</button>
            <?php   }else{ ?>
              <button type="button" class="badge badge-success">Active</button>
            <?php   }
               ?></td>

                <?php if($role=='6'){

                }else{ ?>
                    <td>
                  <a href="<?php echo base_url(); ?>masters/get_service_edit/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                    </td>
              <?php  } ?>
                <!-- <a title="Add Service" href="<?php echo base_url(); ?>masters/create_service/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-plus-square"></i></a> -->

          </tr>
        <?php  $i++;  }  ?>


      </tbody>

  </table>

</div>
</div>
</div>
          </div>
        </div>
      </div>
    </div>
    <script>
  $('#adv_amt').hide();
  $("#is_advance_payment").bind("change  keyup", function() {
     var ad=$(this).val();
     if(ad=='Y'){
       $('#adv_amt').show();
     }else{
        $('#adv_amt').hide();
     }
  });



      // $('#create_service').rules('remove', 'remote');
      $('#create_service').validate({

      rules: {

          service_name: { required: true
                    // remote: {
                    //        url: "<?php echo base_url(); ?>masters/checkservice",
                    //        type: "post"
                    //     }
              },

          service_ta_name: { required: true
                    // remote: {
                    //        url: "<?php echo base_url(); ?>masters/checkservicetamil",
                    //        type: "post"
                    //     }
           },
          is_advance_payment:{required:true},
          advance_amount:{required:true,number:true},
          rate_card:{required:true,digits: true},
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
          service_pic: {required: true,extension: "jpg,jpeg,png" }
      },
      messages: {
          service_pic:{
              required :"Select Service Picture",extension:"File must be JPG OR PNG"
          },
          is_advance_payment:{required:"Select Advance type"},
          advance_amount:{required:"Enter the amount",number:"Enter only numbers"},
          rate_card:{required:"Enter rate card value"},
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
      					 required: "Enter Service.",
      					 remote: "Service Name  already in Exist!"
      							 },
           service_ta_name: {
                 required: "Enter Service Tamil Name.",
                 remote: "Service Tamil Name  Already in Exist!"
                     },

      }

      });
    </script>
