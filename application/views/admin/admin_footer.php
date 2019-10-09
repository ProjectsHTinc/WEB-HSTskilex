
<footer class="footer">
          <div class="container clearfix">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">
              <p><a href="https://happysanztech.com/" target="_blank">Developed by <img src="<?php echo base_url(); ?>assets/happy_logo.png"></a></p>

            </span>
            <!-- <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hmp; made with <i class="mdi mdi-heart text-danger"></i></span> -->
          </div>
</footer>
</div>
<style>
input[type="search"]:before{
  content: "\f002";
  font-family: FontAwesome;
}
input[type="search"]:after{
  content: "\f002";
  font-family: FontAwesome;
}
</style>

<!-- <script src="<?php echo base_url(); ?>assets/admin/js/template.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>assets/admin/js/data-table.js"></script> -->
<script>
$('#example').DataTable();

/* var table = $('#example').DataTable({
  language: {
        searchPlaceholder: "Search",
        search: '<i class="fa fa-search"></i>'
    }
}); */
	new $.fn.dataTable.Responsive( table, {
	details: false
} );
</script>
<?php if($this->session->userdata('user_role')=='1'){

}else{ ?>
  <script>
  $(document).ready(function() {

      $(document)[0].oncontextmenu = function() { return false; }

      $(document).mousedown(function(e) {
          if( e.button == 2 ) {
              alert('Sorry, this functionality is disabled!');
              return false;
          } else {
              return true;
          }
      });
  });
  $(document).ready(function () {
     $('body').bind('cut copy paste', function (e) {
        e.preventDefault();
     });
  });
  </script>
<?php } ?>

</body>
</html>
