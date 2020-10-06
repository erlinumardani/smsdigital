<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">

        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard"></i> <?=$form_title?></h3>
        </div>
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
                            <input autocomplete="off" type="text" name="startdate" id="startdate" class="form-control datepicker" placeholder="Start Date">
                        </div>
                    </div>
                    <a href="#" class="btn btn-primary" id="get_history"><i class="fas fa-clipboard"></i> Get Data</a>
                    <a href="#" class="btn btn-primary" id="get_spreadsheet"><i class="fas fa-file-excel"></i> Export to Spreadsheet</a>
                    <a href="#" class="btn btn-primary" id="get_csv"><i class="fas fa-file-csv"></i> Export to CSV</a>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username">End Date</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            </div>
                            <input autocomplete="off" type="text" name="enddate" id="enddate" class="form-control datepicker" placeholder="End Date">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="history_data" class="table table-bordered table-striped table-hover datatable nowrap">
            <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Type</th>
                <th>Phone Number</th>
                <th>Message</th>
                <th>Sender</th>
                <th>Provider</th>
                <th>Status</th>
                <th>Reason</th>
                <th>MSGID</th>
                <th>Schedule</th>
                <th>Date Created</th>
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

  