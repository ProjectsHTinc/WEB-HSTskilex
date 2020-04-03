<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">

            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Send SMS</h4>

                  <form class="forms-sample" id="send_sms" method="post">
                    <div class="form-group">
                      <label for="exampleTextarea1">SMS Content</label>
                      <textarea class="form-control" id="sms_content" name ="sms_content" rows="3" maxlength="150"></textarea>
                    </div>
					<div class="form-group">
                      <button type="submit" class="btn btn-primary mr-2">SEND</button>
                    </div>
					 <div class="form-group">
                      <img src="<?php echo base_url(); ?>assets/images/loading.gif" alt="loading" id="loading_gif" style="display:none">
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
$('#send_sms').validate({
rules: {
    sms_content: {required: true }
},
messages: {
    sms_content:{
      required :"Enter SMS Content"
    }
},
submitHandler: function(form) {
		document.getElementById('loading_gif').style.display = 'block';
$.ajax({
           url: "<?php echo base_url(); ?>masters/send_sms_content",
           type: 'POST',
           data: $('#send_sms').serialize(),
           dataType: "json",
           success: function(response) {
              var stats=response.status;
               if (stats=="success") {
				 document.getElementById('loading_gif').style.display = 'none';
                 swal('Message send to customers')
                 window.setTimeout(function () {
                  location.href = "<?php echo base_url(); ?>dashboard";
              }, 1500);

             }else{
                 $('#res').html(response.msg)
                 }
           }
       });
     }

});
</script>