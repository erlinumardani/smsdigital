<!-- Main content -->
<section class="content">
  <form method="post" action="#" id="initiate_form">
    <div class="row">
    <div class="col-md-12">

            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard"></i> <?=$form_title?></h3>
              </div>
              <div class="card-body">

              <form method="post" action="#" id="initiate_form">

                <?=$form?>

                <button type="submit" class="btn btn-warning" onclick="window.history.go(-1); return false;"><i class="fas fa-backward"></i> Back</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Update</button>
                
              </form>
              
              

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
  </form>
</section>
<!-- /.content -->
<script>

$(document).ready(function() {
  <?=$privileges_check?>
});

</script>
  