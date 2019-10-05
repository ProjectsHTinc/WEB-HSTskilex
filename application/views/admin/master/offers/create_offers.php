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
                  <h4 class="card-title">Create Offer</h4>

                  <form class="forms-sample" id="create_offer" method="post" action="<?php echo base_url(); ?>offers/create_offers" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-6"></div>
                      <div class="col-md-6"></div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="username">Offer  Title</label>
                          <input type="text" class="form-control" id="offer_title" name="offer_title" placeholder="offer  title">
                        </div>

                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Offer  Code</label>
                            <input type="text" class="form-control" id="offer_code" name="offer_code" placeholder="offer code">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="username">Offer Percent</label>
                          <input type="text" class="form-control" id="offer_percent" name="offer_percent" placeholder="offer percentage">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                            <label for="username">Maximum Discount Amount</label>
                            <input type="text" class="form-control" id="max_offer_amount" name="max_offer_amount">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                            <label for="username">Minimum Purchase Amount</label>
                            <input type="text" class="form-control" id="minimum_purchase_amt" name="minimum_purchase_amt">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Offer Descriptions</label>
                            <textarea id="offer_description" class="form-control" name="offer_description"></textarea>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="exampleFormControlSelect3">Status</label>
                          <select class="form-control form-control-sm" id="status" name="status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Create offer</button>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-12 grid-margin stretch-card" id="list">

              <div class="card">

                <?php if($this->session->flashdata('msg')) {
                $message = $this->session->flashdata('msg');?>
                <div class="<?php echo $message['class'] ?>">
                  <?php echo $message['message']; ?>
                </div>
              <?php  }  ?>

                <div class="card-body"  >
                  <h4 class="card-title">List of Offers </h4>

              <table id="example" class="table table-striped table-bordered">
      <thead >
          <tr>
              <th>S.no</th>
              <th>Title</th>
              <th>Code</th>
              <th>Percentage</th>
              <th>Max amt</th>
              <th>Status</th>
              <?php if($role=='6'){

              }else{ ?><th>Actions</th><?php } ?>
          </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($res as $rows){ ?>


          <tr>
                <td><?php echo $i; ?></td>
              <td><?php echo $rows->offer_title; ?>  </td>
                <td><?php echo $rows->offer_code; ?> </td>
                  <td><?php echo $rows->offer_percent; ?> </td>
                  <td><?php echo $rows->max_offer_amount; ?> </td>
                <td><?php if($rows->status=='Inactive'){ ?>
                <button type="button" class="btn btn-danger btn-fw">Inactive</button>
            <?php   }else{ ?>
              <button type="button" class="btn btn-success btn-fw">Active</button>
            <?php   }
               ?></td>
               <?php if($role=='6'){

               }else{ ?>
                 <td>
                   <a href="<?php echo base_url(); ?>offers/get_offer_edit/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                 </td>
              <?php  } ?>

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

      $('#create_offer').validate({
      rules: {

          offer_title: { required: true,
            remote: {
                 url: "<?php echo base_url(); ?>offers/checkoffer_title",
                   type: "post"
                }
              },
          offer_code: { required: true,
            remote: {
                     url: "<?php echo base_url(); ?>offers/checkoffer_code",
                   type: "post"
                }
              },
          offer_percent:{required:true,digits: true},
          max_offer_amount: {required:true,digits: true },
          minimum_purchase_amt:{required:true,digits: true },
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
