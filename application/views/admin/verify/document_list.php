<!-- <script src="<?php  echo base_url(); ?>assets/admin/js/modal-demo.js"></script> -->
<style>
th{
  width:100px;
}
</style>
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Document  list <a href="javascript:window.history.go(-1);" class="btn go_back_btn pull-right">Back</a></h4>
              <div class="container">
                  <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered  "  >

        <thead>
            <tr>
                <th >S.no</th>
                <th>Document Name</th>
                <th>Proof Number</th>
                <th>File</th>
                <?php  $cmp_type=$this->uri->segment(3);if($cmp_type=='Company'){ ?>
                  <th>Document Own's</th>
              <?php  }else{

                }  ?>

                <th>Status</th>
                <th>Uploaded on</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($res as $rows) { ?>


            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $rows->doc_name; ?></td>
                <td><?php if($rows->doc_proof_number==""){
                  echo "Not Uploaded";
                }else{
                  echo $rows->doc_proof_number;
                } ?></td>
                <td>
                  <?php  if($rows->file_name==""){
                    echo "No file found";
                  }else{ ?>
                    <a href="<?php echo base_url(); ?>assets/providers/documents/<?php echo $rows->file_name; ?>" target="_blank">Download here</a>
                  <?php }?></td>
                  <?php  $cmp_type=$this->uri->segment(3);if($cmp_type=='Company'){ ?>
                    <td><?php echo $rows->company_doc_type; ?></td>
                <?php  }else{

                  }  ?>
                <td><?php if($rows->status==""){
                    echo "Not Uploaded";
                }else{
                  $doc_stats=$rows->status;
                  if($doc_stats=="Pending"){ ?>
                    <button class="btn btn-success"><?php echo $doc_stats; ?></button>
                <?php  }else if($doc_stats=="Uploaded"){ ?>
                  <button class="btn btn-success"><?php echo $doc_stats; ?></button>
                <?php  }else if($doc_stats=="Processing"){ ?>
                  <button class="btn btn-success"><?php echo $doc_stats; ?></button>
                <?php  }else if($doc_stats=="Approved"){ ?>
                  <button class="btn btn-success"><?php echo $doc_stats; ?></button>
                <?php  }else if($doc_stats=="Rejected"){ ?>
                  <button class="btn btn-success"><?php echo $doc_stats; ?></button>
                <?php  }else if($doc_stats=="Verified"){ ?>
                  <button class="btn btn-success"><?php echo $doc_stats; ?></button>
                <?php  }else{ ?>
                  <button class="btn btn-success" ><?php echo $doc_stats; ?></button>
                <?php   }  } ?>
              </td>


                <td><?php echo  date('d-m-Y H:i:s',strtotime($rows->created_at)) ?></td>
                <td><?php if($rows->id==""){
                  echo "-";
                }else{ ?>
                  <!-- <button data-toggle="modal" data-target="#exampleModal-4" data-whatever="@mdo"><?php echo $rows->id; ?></button> -->
                <a  class="open-AddBookDialog" data-toggle="modal" data-target="#exampleModal-4" data-doc-status="<?php echo $rows->status; ?>" data-id="<?php echo $rows->id; ?>" title="Update here"><i class="fa fa-wrench" aria-hidden="true"></i></a>&nbsp;
                <a href="<?php echo base_url(); ?>verifyprocess/doc_history/<?php echo base64_encode($rows->id*98765); ?>" title="check history"><i class="fa fa-list-alt" aria-hidden="true"></i></a>


              <?php   } ?></td>
            </tr>
          <?php  $i++;  }  ?>


        </tbody>

    </table>
              </div>
            </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->

      </div>

    </div>

    <div class="modal fade" id="exampleModal-4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-4" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel-4">Update Document Status</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <div class="modal-body">
                            <form action="" method="post" id="doc_status_form">
                              <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Status</label>
                                <select class="form-control form-control-sm" id="doc_status" name="doc_status">
                                  <option value="Pending">Pending</option>
                                  <option value="Uploaded">Uploaded</option>
                                  <option value="Processing">Processing</option>
                                  <option value="Approved">Approved</option>
                                  <option value="Rejected">Rejected</option>
                                  <option value="Verified">Verified</option>
                                </select>
                              </div>
                             <input type="hidden" name="doc_dd_id" id="doc_dd_id" value=""/>
                             <!-- <input type="hidden" name="doc_status" id="doc_status_id" value=""/> -->

                              <div class="form-group">
                                <label for="message-text" class="col-form-label">Notes:</label>
                                <textarea class="form-control" name="notes" id="notes"></textarea>
                              </div>
                              <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Update status</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                              </div>
                            </form>
                          </div>

                        </div>
                      </div>
                    </div>


    <script>

      $('#exampleModal-4').on('shown.bs.modal', function() {

      })
      $(document).on("click", ".open-AddBookDialog", function () {
       var myBookId = $(this).data('id');
       $(".modal-body #doc_dd_id").val( myBookId );
       var doc_status = $(this).data('doc-status');
       $("doc_status").val(doc_status);
       $(".modal-body #doc_status").val( doc_status );

});

$('#doc_status_form').validate({
rules: {
    doc_status: {
        required: true
      },
      notes :{
        required: true
      }
},
messages: {
    doc_status: {
      required:"Select status"
    },
    notes: {
      required:"Please Enter Some notes"
    }

},
submitHandler: function(form) {
$.ajax({
           url: "<?php echo base_url(); ?>verifyprocess/update_doc_status",
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
