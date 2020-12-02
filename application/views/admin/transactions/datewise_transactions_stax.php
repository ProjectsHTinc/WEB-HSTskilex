<script src="<?php echo base_url(); ?>assets/admin/js/datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.css" rel="stylesheet">

<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Transactions</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>From and To Date Transaction - Service Tax</span></li>
            </ol>
          </nav>
          <div class="row">
          <div class="container" id="list">
              <div class="card">
                  <?php if($this->session->flashdata('msg')){ ?>
                  <div class="alert alert-success" role="alert">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <?php  echo $this->session->flashdata('msg'); ?>
                  </div>
                  <?php } ?>
                      <div class="card-body"  >
                          <h4 class="card-title">Date between Tax Details</h4>
                          
						  <form action="<?php echo base_url();  ?>transaction/from_date_to_date_tax_details" method="post" id="trans_form" name="trans_form" onsubmit = "return isDateCompare();">
                            <div class="col-md-12">
                              <div class="form-group row">
                                <label class="col-sm-2 col-form-label">From date :</label>
                                <div class="col-sm-3">
                                      <input type="text" name="from_date" class="form-control selector" value="" id="from_date" autocomplete="off">

                                  </div>
                                  <label class="col-sm-2 col-form-label">To date  :</label>
                                  <div class="col-sm-3">
                                        <input type="text" name="to_date" class="form-control" value="" id="to_date" autocomplete="off">

                                    </div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="col-md-6 text-center">
                              <input type = "submit" value = "Submit" class="btn btn-primary" />
                              </div>

                            </div>

                            </div>


                          </form>

                      </div>
              </div>
          </div>




          </div>
        </div>
      </div>
    </div>

<script>
    $(function() {
		$( "#from_date" ).datepicker({
			format: 'dd-mm-yyyy',
			endDate: '-0d',
			autoclose: true
		});
		$( "#to_date" ).datepicker({
			format: 'dd-mm-yyyy',
			endDate: '-0d',
			autoclose: true
		});
    });

    function isDateCompare() {
         if( document.trans_form.from_date.value == "" ) {
            alert( "Please Select From date!" );
            document.trans_form.from_date.focus() ;
            return false;
         }
         if( document.trans_form.to_date.value == "" ) {
            alert( "Please Select To date!" );
            document.trans_form.to_date.focus() ;
            return false;
         }
		 
		var leadDate = document.trans_form.from_date.value;
		var closeDate = document.trans_form.to_date.value;

		var date1 = new Date();
		date1.setFullYear(leadDate.substr(6,4),(leadDate.substr(3,2)-1),leadDate.substr(0,2));

		var date2 = new Date();
		date2.setFullYear(closeDate.substr(6,4),(closeDate.substr(3,2)-1),closeDate.substr(0,2));

		if (date1> date2)
		{
			alert("To date cannot be less than Start date.");
			document.trans_form.to_date.focus() ;
			return false;
		}
         return true;
      }

</script>
