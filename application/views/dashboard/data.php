<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
        <div class="card-header">
            <!-- <h3 class="card-title">DataTable with minimal features & hover style</h3> -->
            <!-- <a href="<?=$base_url.$page?>/data/form" class="btn btn-primary"><i class="fas fa-plus-square"></i> Add New</a> -->
            <div class="row"> 
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username">Start Date</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            </div>
                            <input type="text" name="startdate" id="startdate" class="form-control datetime" placeholder="Start Date">
                        </div>
                    </div>
                    <a href="#" class="btn btn-primary" id="get_report"><i class="fas fa-clipboard"></i> Get Report</a>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username">End Date</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            </div>
                            <input type="text" name="enddate" id="enddate" class="form-control datetime" placeholder="End Date">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="datalist" class="table table-bordered table-striped table-hover datatable nowrap">
            <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Nomor Registrasi</th>
                <th>Nama Vendor</th>
                <th>No TP</th>
                <th>Nominal Invoice</th>
                <th>status</th>
                <th>User PP</th>
                <th>Tanggal Request</th>
                <th>Registrasi Ulang</th>
                <th>Checker</th>
                <th>Start Checking</th>
                <th>End Checking</th>
                <th>Verificator</th>
                <th>Start Verification</th>
                <th>End Verification</th>
                <th>User Process</th>
                <th>Start Process</th>
                <th>End Process</th>
                <th>Validator</th>
                <th>Start Validation</th>
                <th>End Validation</th>
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

  