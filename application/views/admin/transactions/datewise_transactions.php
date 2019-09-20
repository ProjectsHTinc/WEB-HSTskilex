<script src="<?php echo base_url(); ?>assets/admin/js/datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.css" rel="stylesheet">

<style>
th{
  padding: 0px 0px 0px 0px;
}
table.dataTable thead th, table.dataTable thead td{
  padding: 0px;
}

</style>
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Transactions</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>From and To Date Transaction</span></li>
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
                          <h4 class="card-title">Date between Transactions</h4>
                          <form action="<?php echo base_url();  ?>transaction/from_date_to_date" method="post" id="">
                            <div class="col-md-12">
                              <div class="form-group row">
                                <label class="col-sm-2 col-form-label">From date :</label>
                                <div class="col-sm-3">
                                      <input type="text" name="from_date" class="form-control selector" value="" id="datepicker" autocomplete="off">

                                  </div>
                                  <label class="col-sm-2 col-form-label">To date  :</label>
                                  <div class="col-sm-3">
                                        <input type="text" name="to_date" class="form-control" value="" id="datepicker_1" autocomplete="off">

                                    </div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="col-md-6 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
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



    <style>

    </style>
    <script>
    $(function() {
      $("#datepicker").datepicker();
        $( "#datepicker_1" ).datepicker();
    });



$('#doc_status_form').validate({
rules: {

      transaction_notes :{
        required: true
      }
},
messages: {

    transaction_notes: {
      required:"Please Enter Some notes"
    }

},
submitHandler: function(form) {
$.ajax({
           url: "<?php echo base_url(); ?>transaction/update_trans_status",
           type: 'POST',
           data: $('#doc_status_form').serialize(),
           dataType: "json",
           success: function(response) {
              var stats=response.status;
               if (stats=="success") {
              swal('Status Updated')
               window.setTimeout(function(){location.reload()},1000)


             }else{

                   swal(stats)
                 }
           }
       });
     }
});


    </script>
