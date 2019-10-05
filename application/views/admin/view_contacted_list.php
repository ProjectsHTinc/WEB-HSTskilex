<style>

</style>
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="card">
            <?php if($this->session->flashdata('msg')) {
            $message = $this->session->flashdata('msg');?>
            <div class="<?php echo $message['class'] ?>">
              <?php echo $message['message']; ?>
            </div>
          <?php  }  ?>
            <div class="card-body">
              <h4 class="card-title">Recent Contact list</h4>
              <div class="row">
                  <div class="col-md-12">
                <table id="example" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>S.no</th>
                <th>Name</th>
                <th style="width:100px;">Email </th>
                <th>Subject</th>
                <th>Phone Number</th>
                <th style="width:300px;">Message</th>
                <th style="width:200px;">Received On</th>
            </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach($res as $rows){ ?>


            <tr class="<?php if($rows->seen=='1'){
              echo "no_color";}
              else{
                echo "color_tab";
              } ?>">
                <td><?php echo $i; ?></td>
                <td><?php echo $rows->name; ?></td>
                <td><?php echo $rows->email; ?></td>
                <td><?php echo $rows->subject; ?></td>
                <td><?php echo $rows->phone_number; ?></td>
                <td><?php echo $rows->message; ?></td>
                <td>
                <?php echo date('d-m-Y H:s a', strtotime($rows->created_at));  ?>
              </td>

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
    <script>
    /*  $('#example').dataTable( {
    paging: false,
    searching: false
} ); */
    </script>
