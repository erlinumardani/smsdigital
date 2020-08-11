<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
        <div class="card-header">
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="datalist" class="table table-bordered table-hover table-striped nowrap datatable">
            <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Nomor Registrasi</th>
                <th>Jenis Dokumen</th>
                <th>Nama Vendor</th>
                <th>Nomor Invoice</th>
                <th>Nama Pekerjaan</th>
                <th>Nilai Invoice</th>
                <th>Tanggal Jatuh Tempo</th>
                <th>Status</th>
                <th>Nomor TP</th>
                <th>Nomor SPB</th>
                <th>Approval Mgr</th>
                <th>Approval VP</th>
                <th>Approval CA</th>
                <th>Approval Dirop</th>
                <th>Approval Dirkug</th>
                <th>Approval Dirut</th>
                <th>Status Payment</th>
                <th>Jumlah Dibayarkan</th>
                <th>Payment Date Plan</th>
                <th>Payment Date</th>
                
            </tr>
            </thead>
            <tbody>
            
            </tbody>
            
            </table>
        </div>
        <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

<div class="modal fade" id="modal-checkin">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post" action="#" id="checkin_form">
        <div class="modal-header bg-primary">
          <h4 class="modal-title"><i class="fas fa-lock"></i> Check-in For Posting</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            
                <?=$checkin_fieldset?>
    
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Check-In</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

  