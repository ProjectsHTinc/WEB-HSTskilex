<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom">
              <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"><span>City Master</span></li>
            </ol>
          </nav>
          <div class="row">

            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Create City </h4>

                  <form class="forms-sample" id="create_city" method="post">

                    <div class="form-group">
                      <label for="username">City name (English)</label>
                      <input type="text" class="form-control" id="city_name" name="city_name" placeholder="City Name">
                    </div>
                    <div class="form-group">
                      <label for="city_ta_name">City Name(Tamil)</label>
                      <input type="text" class="form-control" id="city_ta_name" name="city_ta_name" placeholder="City Tamil Name" >
                    </div>
                    <div class="form-group">
                      <label for="latitude">Latitude</label>
                      <input type="text" class="form-control" id="latitude" name="latitude" placeholder="latitude">
                    </div>
                    <div class="form-group">
                      <label for="longitude">Longitude</label>
                      <input type="text" class="form-control" id="longitude" name="longitude" placeholder="longitude">
                    </div>

                    <div class="form-group">
                      <label for="exampleFormControlSelect3">Status</label>
                      <select class="form-control form-control-sm" id="exampleFormControlSelect3" name="status">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>

                      </select>
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Create</button>

                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-8 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">List of City </h4>
              <table id="example" class="table table-striped table-bordered">
      <thead>
          <tr>
              <th>S.no</th>
              <th>City name</th>
              <th>Status</th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody>
        <?php $i=1; foreach($res as $rows){ ?>


          <tr>
                <td><?php echo $i; ?></td>
              <td><?php echo $rows->city_name; ?> <br><br><?php echo $rows->city_ta_name; ?>
              </td>
                <td><?php if($rows->status=='Inactive'){ ?>
                <button type="button" class="btn btn-danger btn-fw">Inactive</button>
            <?php   }else{ ?>
              <button type="button" class="btn btn-success btn-fw">Active</button>
            <?php   }
               ?></td>
              <td><a href="<?php echo base_url(); ?>masters/get_city_edit/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> &nbsp;&nbsp;
                <!-- <a href="<?php echo base_url(); ?>home/get_staff_details/<?php echo base64_encode($rows->id*98765); ?>"><i class="fa fa-edit"></i></a> -->
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
      </div>
    </div>
