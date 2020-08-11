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

                <?=$form?>

                <!-- /.form group -->
                <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Submit</button>
                <button type="submit" class="btn btn-danger" onclick="window.history.go(-1); return false;"/><i class="fas fa-ban"></i> Cancel</button>
                
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
  